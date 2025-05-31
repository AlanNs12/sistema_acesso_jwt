<?php
require_once '../conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $horario_manual = $_POST['horario_manual'] ?? null;
    $observacoes = $_POST['observacoes'] ?? null;

    date_default_timezone_set('America/Sao_Paulo');

    $data_hora_completa = $horario_manual
        ? str_replace('T', ' ', $horario_manual) . ':00'
        : date('Y-m-d H:i:s');

    $data = substr($data_hora_completa, 0, 10);
    $hora = substr($data_hora_completa, 11, 8);

    // Verifica se já existe registro para o dia
    $stmt = $pdo->prepare("SELECT * FROM registros_funcionarios WHERE funcionario_id = ? AND data = ?");
    $stmt->execute([$funcionario_id, $data]);
    $registro = $stmt->fetch();

    if (!$registro) {
        // Se não existir, insere como entrada
        $stmt = $pdo->prepare("INSERT INTO registros_funcionarios (funcionario_id, data, hora_entrada, observacoes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$funcionario_id, $data, $hora, $observacoes]);
        echo "<div class=\"alert alert-success\"><strong>Entrada registrada com sucesso!</strong></div>";
    } elseif (!$registro['hora_saida']) {
        // Se já tem entrada mas não tem saída
        $stmt = $pdo->prepare("UPDATE registros_funcionarios SET hora_saida = ?, observacoes = ? WHERE id = ?");
        $stmt->execute([$hora, $observacoes, $registro['id']]);
        echo "<div class=\"alert alert-success\"><strong>Saída registrada com sucesso!</strong></div>";
    } else {
        // Se já tem entrada e saída, impede novo registro
        echo "<div class=\"alert alert-warning\"><strong>Registro já completo para hoje. Entrada e saída já registradas.</strong></div>";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Registro de Entrada/Saída</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
</head>

<body>
    <?php include '../menu.php'; ?>
    <div class="container">
        <div class="card">
            <h1 class="card-header">Registro de Funcionario</h1>
            <div class="card-body">
                <div class="container d-flex justify-content-center">
                    <div class="w-100" style="max-width: 400px;">
                        <form action="registro_hora.php" method="POST">
                            <div class="form-group">
                                <label>Funcionário:</label>
                                <select name="funcionario_id" class="form-control" required>
                                    <option value="" hidden>Selecione</option>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM funcionarios");
                                    $funcionarios = $stmt->fetchAll();
                                    foreach ($funcionarios as $f) {
                                        echo "<option value='{$f['id']}'>{$f['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Data e Hora Manual (opcional):</label>
                                <input type="datetime-local" name="horario_manual" class="form-control">
                                <label>Observações (opcional):</label>
                                <textarea name="observacoes" rows="3" class="form-control"
                                    placeholder="Ex: Saiu mais cedo por motivo de consulta médica, intervalo de almoço etc."></textarea>
                            </div>

                            <button class="btn btn-success" type="submit">Registrar</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>