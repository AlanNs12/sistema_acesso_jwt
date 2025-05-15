<?php
require_once '../conexao.php';
session_start();
$data_filtro = isset($_GET['data']) ? $_GET['data'] : null;


if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit;
}

$registros_por_pagina = 20;

$pagina_atual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Contar total de registros de saída (para paginação)
if ($data_filtro) {
    $total_stmt = $pdo->prepare("
        SELECT COUNT(*) FROM registros_veiculos 
        WHERE tipo_registro = 'saida' AND DATE(data_hora) = :data
    ");
    $total_stmt->execute([':data' => $data_filtro]);
} else {
    $total_stmt = $pdo->query("
        SELECT COUNT(*) FROM registros_veiculos WHERE tipo_registro = 'saida'
    ");
}
$total_registros = $total_stmt->fetchColumn();

$total_paginas = ceil($total_registros / $registros_por_pagina);

// Buscar os registros com paginação
if ($data_filtro) {
    $sql = "
        SELECT rv.*, v.placa, v.modelo 
        FROM registros_veiculos rv 
        JOIN veiculos v ON rv.veiculo_id = v.id 
        WHERE rv.tipo_registro = 'saida' AND DATE(rv.data_hora) = :data
        ORDER BY rv.data_hora DESC 
        LIMIT :limite OFFSET :offset
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':data', $data_filtro);
} else {
    $sql = "
        SELECT rv.*, v.placa, v.modelo 
        FROM registros_veiculos rv 
        JOIN veiculos v ON rv.veiculo_id = v.id 
        WHERE rv.tipo_registro = 'saida'
        ORDER BY rv.data_hora DESC 
        LIMIT :limite OFFSET :offset
    ";
    $stmt = $pdo->prepare($sql);
}

$stmt->bindValue(':limite', $registros_por_pagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$stmt->bindValue(':limite', $registros_por_pagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$saidas = $stmt->fetchAll();

$ids_saidas = array_column($saidas, 'id');

if (!empty($ids_saidas)) {
    $placeholders = implode(',', array_fill(0, count($ids_saidas), '?'));
    $stmt_retornos = $pdo->prepare("
        SELECT * FROM registros_veiculos 
        WHERE tipo_registro = 'retorno' AND saida_id IN ($placeholders)
    ");
    $stmt_retornos->execute($ids_saidas);
    $retornos = $stmt_retornos->fetchAll();

    $retornos_por_saida = [];
    foreach ($retornos as $retorno) {
        $retornos_por_saida[$retorno['saida_id']] = $retorno;
    }
}



// Agrupar por veículo: cada saída seguida de seu retorno
$agrupados = [];

foreach ($saidas as $saida) {
    $retorno = $retornos_por_saida[$saida['id']] ?? null;

    $agrupados[] = [
        'id' => $saida['id'],
        'saida_data' => date('d/m/Y', strtotime($saida['data_hora'])),
        'saida_hora' => date('H:i', strtotime($saida['data_hora'])),
        'motorista' => $saida['motorista_responsavel'],
        'veiculo' => $saida['placa'] . ' - ' . $saida['modelo'],
        'observacoes' => $saida['observacoes'],
        'retorno_data' => $retorno ? date('d/m/Y', strtotime($retorno['data_hora'])) : '',
        'retorno_hora' => $retorno ? date('H:i', strtotime($retorno['data_hora'])) : ''
    ];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Registros Veiculos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
    <?php include '../menu.php'; ?>
    <div class="container">
        <div class="card">
            <h1 class="card-header">Registros de Veículos</h1>
            <div class="card-body">
                <div class="container d-flex justify-content-center">
                    <div class="w-100">
                        <form method="GET" class="form-inline mb-3">
                            <label for="data" class="mr-2">Filtrar por data:</label>
                            <input type="date" name="data" id="data" value="<?= $data_filtro ?>"
                                class="form-control mr-2">
                            <button type="submit" class="btn btn-primary">&#x1F4CC; Filtrar</button>
                            <a href="exportar_excel_agrupado.php" class="btn btn-success ml-2">&#128190; Exportar para
                                Excel</a>
                        </form>

                        <table class="table table-bordered table-striped table-responsive">
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
                                                        <form method="POST" action="registrar_retorno_rapido.php"
                                                            style="margin-bottom: 5px;">
                                                            <input type="hidden" name="veiculo"
                                                                value="<?= htmlspecialchars($linha['veiculo']) ?>">
                                                            <input type="hidden" name="motorista"
                                                                value="<?= htmlspecialchars($linha['motorista']) ?>">
                                                            <input type="hidden" name="observacoes"
                                                                value="<?= htmlspecialchars($linha['observacoes']) ?>">
                                                            <button type="submit" class="btn btn-sm btn-warning">Registrar
                                                                Retorno</button>
                                                        </form>
                                                    <?php endif; ?>

                                                    <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
                                                        <form method="POST" action="excluir_registro_veiculo.php"
                                                            onsubmit="return confirm('Tem certeza que deseja excluir este registro?');">
                                                            <input type="hidden" name="registro_id" value="<?= $linha['id'] ?>">
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
                </div>
            </div>
            <div class="card-footer text-muted">
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="page-item <?= $i == $pagina_atual ? 'active' : '' ?>">
                                <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
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