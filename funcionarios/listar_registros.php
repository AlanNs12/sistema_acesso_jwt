<?php
require_once '../conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.html");
  exit;
}

$filtro_funcionario = $_GET['funcionario_id'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

$itens_por_pagina = 25;
$pagina_atual = $_GET['pagina'] ?? 1;
$inicio = ($pagina_atual - 1) * $itens_por_pagina;

// Buscar funcionários
$stmt_func = $pdo->query("SELECT id, nome FROM funcionarios ORDER BY nome");
$funcionarios = $stmt_func->fetchAll();

// Consulta principal
$sql = "SELECT rf.*, f.nome 
        FROM registros_funcionarios rf
        JOIN funcionarios f ON f.id = rf.funcionario_id
        WHERE 1=1";
$params = [];

if ($filtro_funcionario) {
  $sql .= " AND f.id = ?";
  $params[] = $filtro_funcionario;
}
if ($filtro_data_inicio && $filtro_data_fim) {
  $sql .= " AND rf.data BETWEEN ? AND ?";
  $params[] = $filtro_data_inicio;
  $params[] = $filtro_data_fim;
}
$sql .= " ORDER BY rf.data DESC LIMIT $inicio, $itens_por_pagina";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll();

// Total para paginação
$sql_total = "SELECT COUNT(*) FROM registros_funcionarios rf JOIN funcionarios f ON f.id = rf.funcionario_id WHERE 1=1";
$params_total = [];

if ($filtro_funcionario) {
  $sql_total .= " AND f.id = ?";
  $params_total[] = $filtro_funcionario;
}
if ($filtro_data_inicio && $filtro_data_fim) {
  $sql_total .= " AND rf.data BETWEEN ? AND ?";
  $params_total[] = $filtro_data_inicio;
  $params_total[] = $filtro_data_fim;
}

$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params_total);
$total_itens = $stmt_total->fetchColumn();
$total_paginas = ceil($total_itens / $itens_por_pagina);

// Verifica registros sem saída
$aviso_pendente = false;
foreach ($registros as $r) {
  if (empty($r['hora_saida'])) {
    $aviso_pendente = true;
    break;
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Registros</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /* Estilo para a caixa de observações */
    .observacoes-box {
      width: 100%;
      max-width: 300px; /* ajuste conforme sua necessidade */
      padding: 5px;
      border: 1px solid #dee2e6;
      border-radius: .25rem;
      background-color: #f8f9fa;
      overflow: auto;
      word-wrap: break-word;
    }
  </style>
</head>

<body>
  <?php include '../menu.php'; ?>

  <div class="container my-4">
    <h1>Registros de Funcionários</h1>
    <h2>Entrada, Saída e Observações</h2>

    <?php if ($aviso_pendente): ?>
      <div class="alert alert-warning">
        Atenção: Há funcionários com registro de entrada sem saída registrada.
      </div>
    <?php endif; ?>

    <form method="GET" class="form-inline mb-3">
      <label class="mr-2">Funcionário:</label>
      <select name="funcionario_id" class="form-control mr-2">
        <option value="">Todos</option>
        <?php foreach ($funcionarios as $f): ?>
          <option value="<?= $f['id'] ?>" <?= $filtro_funcionario == $f['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($f['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label class="mr-2">De:</label>
      <input type="date" name="data_inicio" value="<?= $filtro_data_inicio ?>" class="form-control mr-2">

      <label class="mr-2">Até:</label>
      <input type="date" name="data_fim" value="<?= $filtro_data_fim ?>" class="form-control mr-2">

      <button type="submit" class="btn btn-primary">Filtrar &#128204;</button>
    </form>

    <a href="exportar_excel.php?funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>"
       target="_blank">
      <button type="button" class="btn btn-success mb-3">Exportar para Excel &#128190;</button>
    </a>

    <!-- Tabela dentro de um container responsivo -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="thead-dark">
          <tr>
            <th>Funcionário</th>
            <th>Data de Entrada</th>
            <th>Hora de Entrada</th>
            <th>Data de Saída</th>
            <th>Hora de Saída</th>
            <th>Observações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($registros as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['nome']) ?></td>
              <td><?= date('d/m/Y', strtotime($r['data'])) ?></td>
              <td><?= $r['hora_entrada'] ?? '-' ?></td>
              <td><?= date('d/m/Y', strtotime($r['data_saida'] ?? $r['data'])) ?></td>
              <td>
  <?php if (empty($r['hora_saida'])): ?>
    <form method="POST" action="registrar_saida_rapida.php" style="display:inline;">
      <input type="hidden" name="registro_id" value="<?= $r['id'] ?>">
      <button type="submit" class="btn btn-sm btn-danger">Registrar Saída</button>
    </form>
  <?php else: ?>
    <?= date('H:i:s', strtotime($r['hora_saida'])) ?>
  <?php endif; ?>
</td>
              <td>
                <div class="observacoes-box mb-2">
                  <?= !empty($r['observacoes']) ? htmlspecialchars($r['observacoes']) : '-' ?>
                </div>
                <div class="text-right">
                  <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editarModal<?= $r['id'] ?>">Editar &#128221;</button>
                </div>

                <!-- Modal de Edição -->
                <div class="modal fade" id="editarModal<?= $r['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel<?= $r['id'] ?>" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <form action="editar_observacao.php" method="POST">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editarModalLabel<?= $r['id'] ?>">Editar Observação</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="registro_id" value="<?= $r['id'] ?>">
                          <textarea name="observacoes" class="form-control" rows="4"><?= htmlspecialchars($r['observacoes']) ?></textarea>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- Fim Modal -->
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <ul class="pagination justify-content-center">
      <li class="page-item <?= ($pagina_atual <= 1) ? 'disabled' : '' ?>">
        <a class="page-link" href="?pagina=<?= $pagina_atual - 1 ?>&funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>">Anterior</a>
      </li>

      <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <li class="page-item <?= ($i == $pagina_atual) ? 'active' : '' ?>">
          <a class="page-link" href="?pagina=<?= $i ?>&funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <li class="page-item <?= ($pagina_atual >= $total_paginas) ? 'disabled' : '' ?>">
        <a class="page-link" href="?pagina=<?= $pagina_atual + 1 ?>&funcionario_id=<?= $filtro_funcionario ?>&data_inicio=<?= $filtro_data_inicio ?>&data_fim=<?= $filtro_data_fim ?>">Próximo</a>
      </li>
    </ul>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>