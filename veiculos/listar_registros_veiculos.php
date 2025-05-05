<?php
require_once '../conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit;
}

$data_filtro = $_GET['data'] ?? date('Y-m-d');

$stmt = $pdo->prepare("SELECT rv.*, v.placa, v.modelo 
                       FROM registros_veiculos rv 
                       JOIN veiculos v ON rv.veiculo_id = v.id 
                       WHERE DATE(rv.data_hora) = ?
                       ORDER BY rv.data_hora DESC");
$stmt->execute([$data_filtro]);
$registros = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registros de Veículos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>

<div class="container">
    <h1>Registros de Veículos</h1>

    <form method="GET" class="form-inline mb-3">
        <label for="data" class="mr-2">Filtrar por data:</label>
        <input type="date" name="data" id="data" value="<?= $data_filtro ?>" class="form-control mr-2">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="exportar_excel_veiculos.php?data=<?= $data_filtro ?>" class="btn btn-success ml-2">Exportar para Excel</a>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Data/Hora</th>
                <th>Veículo</th>
                <th>Tipo</th>
                <th>Motorista</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($registros): ?>
                <?php foreach ($registros as $registro): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($registro['data_hora'])) ?></td>
                        <td><?= $registro['placa'] ?> - <?= $registro['modelo'] ?></td>
                        <td><?= ucfirst($registro['tipo_registro']) ?></td>
                        <td><?= $registro['motorista_responsavel'] ?></td>
                        <td><?= $registro['observacoes'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum registro encontrado para a data selecionada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
