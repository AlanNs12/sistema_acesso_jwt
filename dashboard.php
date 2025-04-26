<?php
require_once 'conexao.php';
// Total de funcionários
$total_funcionarios = $pdo->query("SELECT COUNT(*) FROM funcionarios")->fetchColumn();

// Registros do dia
$data_hoje = date('Y-m-d');
$stmt = $pdo->prepare("SELECT tipo_registro, COUNT(*) as total 
                       FROM registros 
                       WHERE DATE(data_hora) = ? 
                       GROUP BY tipo_registro");
$stmt->execute([$data_hoje]);
$registros = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<?php
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
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<?php include 'menu.php'; ?>

  <div class="container">
  <div class="card">
  <img class="card-img-top" src=".../100px180/" alt="Imagem de capa do card">
  <h1 class="card-header">Dashboard</h1>
  <div class="card-body">
  <h4>Bem-vindo, <strong><?= $_SESSION['usuario_nome'] ?>!</strong></h4>
    <div class="card-group">
    
    <div class="row">
  <div class="col-sm-3">
     <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
  <div class="card-header">Registros </div>
  <div class="card-body">
    <h5 class="card-title">Registros de Hoje (<?= date('d/m/Y') ?>)</h5>
    <p class="card-text">
      <ul>
      <li>Entradas: <?= $registros['entrada'] ?? 0 ?></li>
      <li>Saídas: <?= $registros['saida'] ?? 0 ?></li>
      <li>Saída para almoço: <?= $registros['saida_almoco'] ?? 0 ?></li>
      <li>Retorno do almoço: <?= $registros['retorno_almoco'] ?? 0 ?></li>
    </ul>
  </p>
  <a href="funcionarios/listar_registros.php" class="btn btn-outline-light">Ver registros &#128203; </a>
  </div>
</div>
  </div><br>
  <div class="col-sm-3">
  <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
  <div class="card-header">Funcionários Cadastrados</div>
  <div class="card-body">
    <h5 class="card-title"></h5>
    <p class="card-text"><strong>Total de Funcionários:</strong> <?= $total_funcionarios ?></p>
    <a href="funcionarios/listar_funcionarios.php" class="btn btn-outline-light">Ver funcionários &#128100;</a>
  </div>
</div>
  </div> <br>
  <div class="col-sm-3">
  <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
  <div class="card-header">Cabeçalho</div>
  <div class="card-body">
    <h5 class="card-title">Título de Card Danger</h5>
    <p class="card-text">Um exemplo de texto rápido para construir o título do card e fazer preencher o conteúdo do card.</p>
  </div>
</div>
  </div> <br>
  <div class="col-sm-3">
  <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
  <div class="card-header">Cabeçalho</div>
  <div class="card-body">
    <h5 class="card-title">Título de Card Success</h5>
    <p class="card-text">Um exemplo de texto rápido para construir o título do card e fazer preencher o conteúdo do card.</p>
  </div>
</div>
  </div>

</div>
  </div>

</div>
<div class="card-footer text-muted">
    2 dias atrás
  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
