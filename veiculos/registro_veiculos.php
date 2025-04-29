<?php
require_once 'conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $veiculo_id = $_POST['veiculo_id'];
    $tipo_registro = $_POST['tipo_registro'];

    // Verifica se o último registro é compatível com o novo (evita 2 saídas seguidas, por exemplo)
    $stqt = $pdo->prepare("SELECT tipo_registro FROM registros_veiculos WHERE veiculo_id = ? ORDER BY data_hora DESC LIMIT 1");
    $stmt->execute([$veiculo_id]);
    $ultimo = $stmt->fetchColumn();

    $erro = false;
    if ($tipo_registro === 'saida' && $ultimo === 'saida') {
        $erro = true;
        $mensagem = 'Erro: este veículo já foi registrado como "Saída" e ainda não retornou.';
    } elseif ($tipo_registro === 'retorno' && $ultimo !== 'saida') {
        $erro = true;
        $mensagem = 'Erro: só é possível registrar o retorno após uma saída.';
    }

    if (!$erro) {
        $stmt = $pdo->prepare("INSERT INTO registros_veiculos (veiculo_id, tipo_registro) VALUES (?, ?)");
        $stmt->execute([$veiculo_id, $tipo_registro]);
        $mensagem = "Registro de {$tipo_registro} realizado com sucesso!";
        $classe = 'alert-success';
    } else {
        $classe = 'alert-danger';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Registro de Veículos</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>
<div class="container mt-4">
    <h1>Controle de Entrada e Saída de Veículos</h1>
    <?php if (isset($mensagem)): ?>
        <div class="alert <?= $classe ?>"><?= $mensagem ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Veículo:</label>
        <select name="veiculo_id" required>
            <option value="" hidden>Selecione</option>
            <?php
            $stmt = $pdo->query("SELECT * FROM veiculos");
            while ($v = $stmt->fetch()) {
                echo "<option value='{$v['id']}'>{$v['placa']} - {$v['modelo']}</option>";
            }
            ?>
        </select><br>
        <label>Tipo de Registro:</label>
        <select name="tipo_registro" required>
            <option value="" hidden>Selecione</option>
            <option value="saida">Saída</option>
            <option value="retorno">Retorno</option>
        </select><br><br>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
</body>
</html>
