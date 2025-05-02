<?php
require_once '../conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $veiculo_id = $_POST['veiculo_id'];
    $tipo_registro = $_POST['tipo_registro'];
    $motorista = $_POST['motorista'] ?? '';
    $observacoes = $_POST['observacoes'] ?? '';
    $horario_manual = $_POST['horario_manual'] ?? null;
    $data_hora = $horario_manual ? date('Y-m-d') . ' ' . $horario_manual : date('Y-m-d H:i:s');


    $stmt = $pdo->prepare("INSERT INTO registros_veiculos (veiculo_id, tipo_registro, data_hora, motorista_responsavel, observacoes)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$veiculo_id, $tipo_registro, $data_hora, $motorista, $observacoes]);

    echo "<div class='alert alert-success'>Registro de $tipo_registro realizado com sucesso!</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registro de Veículos</title>
    <link rel="stylesheet" href="styles/style-signin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>
<div class="container">
    
    <form method="POST" action="registro_veiculos.php" class="form-signin">
    <h1 class="h3 mb-3 font-weight-normal">Novo registro saida de veiculo</h1>
        <div class="form-group">
            <label for="veiculo_id" >Selecione o Veículo:</label>
            <select name="veiculo_id" class="form-control" required>
                <option value="" hidden>Selecione</option>
                <?php
                $stmt = $pdo->query("SELECT id, placa, modelo FROM veiculos");
                while ($veiculo = $stmt->fetch()) {
                    echo "<option value='{$veiculo['id']}'>{$veiculo['placa']} - {$veiculo['modelo']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tipo_registro">Tipo de Registro:</label>
            <select name="tipo_registro" class="form-control" required>
                <option value="" hidden>Selecione</option>
                <option value="saida">Saída</option>
                <option value="retorno">Retorno</option>
            </select>
        </div>

        <div class="form-group">
            <label for="motorista">Motorista Responsável:</label>
            <input type="text" name="motorista" class="form-control" placeholder="Ex: João da Silva">
        </div>

        <div class="form-group">
            <label for="observacoes">Observações:</label>
            <textarea name="observacoes" class="form-control" rows="3" placeholder="Ex: Veículo foi para manutenção ou passageiros"></textarea>
        </div>

        <div class="form-group">
            <label for="horario_manual">Horário Manual (opcional):</label>
            <input type="time" name="horario_manual" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Registrar</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
