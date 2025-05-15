<?php
require_once '../conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit;
}



// Obter todos os registros do dia, ordenados por veículo e data
$stmt = $pdo->query("
    SELECT rv.*, v.placa, v.modelo 
    FROM registros_veiculos rv 
    JOIN veiculos v ON rv.veiculo_id = v.id 
    ORDER BY v.id, rv.data_hora ASC
");
$registros = $stmt->fetchAll();


// Agrupar por veículo: cada saída seguida de seu retorno
$agrupados = [];

// Indexar registros de retorno por saida_id
$retornos_por_saida = [];
foreach ($registros as $registro) {
    if ($registro['tipo_registro'] === 'retorno' && $registro['saida_id']) {
        $retornos_por_saida[$registro['saida_id']] = $registro;
    }
}

foreach ($registros as $registro) {
    if ($registro['tipo_registro'] === 'saida') {
        $retorno = $retornos_por_saida[$registro['id']] ?? null;

        $agrupados[] = [
            'id' => $registro['id'], // ADICIONE ISSO
            'saida_data' => date('d/m/Y', strtotime($registro['data_hora'])),
            'saida_hora' => date('H:i', strtotime($registro['data_hora'])),
            'motorista' => $registro['motorista_responsavel'],
            'veiculo' => $registro['placa'] . ' - ' . $registro['modelo'],
            'observacoes' => $registro['observacoes'],
            'retorno_data' => $retorno ? date('d/m/Y', strtotime($retorno['data_hora'])) : '',
            'retorno_hora' => $retorno ? date('H:i', strtotime($retorno['data_hora'])) : ''
        ];

    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Registros Agrupados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
    <?php include '../menu.php'; ?>

    <div class="container">
        <h1>Registros de Veículos (Agrupados)</h1>

        <form method="GET" class="form-inline mb-3">
            <label for="data" class="mr-2">Filtrar por data:</label>
            <input type="date" name="data" id="data" value="<?= $data_filtro ?>" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="exportar_excel_agrupado.php" class="btn btn-success ml-2">Exportar para Excel</a>
        </form>

        <table class="table table-bordered table-striped">
            <?php
            $mostrar_coluna_acoes = false;
            foreach ($agrupados as $linha) {
                if (empty($linha['retorno_data']) || $_SESSION['usuario_tipo'] === 'admin') {
                    $mostrar_coluna_acoes = true;
                    break;
                }
            }
            ?>

            <thead class="thead-dark">
                <tr>
                    <th>Data Saída</th>
                    <th>Hora Saída</th>
                    <th>Condutor</th>
                    <th>Veículo</th>
                    <th>Data Retorno</th>
                    <th>Hora Retorno</th>
                    <th>Observações</th>
                    <?php if ($mostrar_coluna_acoes): ?>
                        <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <?php if ($agrupados): ?>
                    <?php foreach ($agrupados as $linha): ?>
                        <tr>
                            <td><?= $linha['saida_data'] ?></td>
                            <td><?= $linha['saida_hora'] ?></td>
                            <td><?= $linha['motorista'] ?></td>
                            <td><?= $linha['veiculo'] ?></td>
                            <td><?= $linha['retorno_data'] ?></td>
                            <td><?= $linha['retorno_hora'] ?></td>
                            <td><?= $linha['observacoes'] ?></td>

                            <?php if ($mostrar_coluna_acoes): ?>
                                <td>
                                    <?php if (empty($linha['retorno_data'])): ?>
                                        <form method="POST" action="registrar_retorno_rapido.php" style="margin-bottom: 5px;">
                                            <input type="hidden" name="veiculo" value="<?= htmlspecialchars($linha['veiculo']) ?>">
                                            <input type="hidden" name="motorista" value="<?= htmlspecialchars($linha['motorista']) ?>">
                                            <input type="hidden" name="observacoes"
                                                value="<?= htmlspecialchars($linha['observacoes']) ?>">
                                            <button type="submit" class="btn btn-sm btn-warning">Registrar Retorno</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
                                        <form method="POST" action="excluir_registro_veiculo.php"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este registro?');">
                                            <input type="hidden" name="registro_id" value="<?= $registro['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>

                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $mostrar_coluna_acoes ? 8 : 7 ?>">Nenhum registro encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>

</html>