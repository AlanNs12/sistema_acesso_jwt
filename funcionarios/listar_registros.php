<?php
require_once '../conexao.php';

// Filtros
$filtro_funcionario = $_GET['funcionario_id'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

$itens_por_pagina = 25;
$pagina_atual = $_GET['pagina'] ?? 1;
$inicio = ($pagina_atual - 1) * $itens_por_pagina;

// Buscar todos os funcionários para o filtro
$stmt_func = $pdo->query("SELECT id, nome FROM funcionarios ORDER BY nome");
$funcionarios = $stmt_func->fetchAll();

// Monta query base
$sql = "SELECT r.*, f.nome 
        FROM registros r 
        JOIN funcionarios f ON f.id = r.funcionario_id 
        WHERE 1=1";

$params = [];

if ($filtro_funcionario) {
    $sql .= " AND f.id = ?";
    $params[] = $filtro_funcionario;
}

if ($filtro_data_inicio && $filtro_data_fim) {
    $sql .= " AND DATE(r.data_hora) BETWEEN ? AND ?";
    $params[] = $filtro_data_inicio;
    $params[] = $filtro_data_fim;
}

$sql .= " ORDER BY r.data_hora DESC LIMIT $inicio, $itens_por_pagina";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll();

// Paginação
$sql_total = "SELECT COUNT(*) FROM registros r JOIN funcionarios f ON f.id = r.funcionario_id WHERE 1=1";
$params_total = [];

if ($filtro_funcionario) {
    $sql_total .= " AND f.id = ?";
    $params_total[] = $filtro_funcionario;
}

if ($filtro_data_inicio && $filtro_data_fim) {
    $sql_total .= " AND DATE(r.data_hora) BETWEEN ? AND ?";
    $params_total[] = $filtro_data_inicio;
    $params_total[] = $filtro_data_fim;
}

$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params_total);
$total_itens = $stmt_total->fetchColumn();
$total_paginas = ceil($total_itens / $itens_por_pagina);

// Sessão
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Registros</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>

<div class="container">
  <h1>Entry, Exit and Lunch Records</h1>
  <h2>Registros de Entrada, Saída e Almoço</h2>
  <form method="GET">
    <label>Employee (Funcionário):</label>
    <select name="funcionario_id">
      <option value="">Todos</option>
      <?php foreach ($funcionarios as $f): ?>
        <option value="<?= $f['id'] ?>" <?= $filtro_funcionario == $f['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($f['nome']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>De:</label>
    <input type="date" name="data_inicio" value="<?= $filtro_data_inicio ?>">

    <label>Até:</label>
    <input type="date" name="data_fim" value="<?= $filtro_data_fim ?>">

    <button type="submit">Filter (Filtrar) &#128204;</button>
  </form>

  <a href="exportar_excel.php?funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>" target="_blank">
    <button type="button">Export to Excel (Exportar para Excel) &#128190;</button>
  </a>

  <table border="1" class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Funcionário</th>
        <th>Tipo</th>
        <th>Data e Hora</th>
        <th>Observações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($registros as $r): ?>
        <?php
          $tipos_registro = [
            'entrada' => 'Entrada (Entry)',
            'saida' => 'Saída (Exit)',
            'almoco' => 'Saída para Almoço (Lunch Out)',
            'retorno_almoco' => 'Retorno do Almoço (Lunch Return)',
          ];
        ?>
        <tr>
          <td><?= htmlspecialchars($r['nome']) ?></td>
          <td><?= $tipos_registro[$r['tipo_registro']] ?? 'Desconhecido (Unknown)' ?></td>
          <td><?= date('d/m/Y H:i:s', strtotime($r['data_hora'])) ?></td>
          <td><?= !empty($r['observacoes']) ? htmlspecialchars($r['observacoes']) : '-' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Paginação -->
  <div>
  <ul class="pagination justify-content-center">

<!-- Botão "Anterior" -->
<li class="page-item <?= ($pagina_atual <= 1) ? 'disabled' : '' ?>">
  <a class="page-link" href="?pagina=<?= $pagina_atual - 1 ?>&funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>">Anterior</a>
</li>

<!-- Números das páginas -->
<?php for ($i = 1; $i <= $total_paginas; $i++): ?>
  <li class="page-item <?= ($i == $pagina_atual) ? 'active' : '' ?>">
    <a class="page-link" href="?pagina=<?= $i ?>&funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>">
      <?= $i ?>
    </a>
  </li>
<?php endfor; ?>

<!-- Botão "Próximo" -->
<li class="page-item <?= ($pagina_atual >= $total_paginas) ? 'disabled' : '' ?>">
  <a class="page-link" href="?pagina=<?= $pagina_atual + 1 ?>&funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>">Próximo</a>
</li>

</ul>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
