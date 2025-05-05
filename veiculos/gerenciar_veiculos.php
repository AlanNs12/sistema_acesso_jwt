<?php
require_once '../conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

// Inserção
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placa'], $_POST['modelo'])) {
    $placa = $_POST['placa'];
    $modelo = $_POST['modelo'];

    $stmt = $pdo->prepare("INSERT INTO veiculos (placa, modelo) VALUES (?, ?)");
    $stmt->execute([$placa, $modelo]);
    header("Location: gerenciar_veiculos.php");
    exit;
}

// Remoção
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    $stmt = $pdo->prepare("DELETE FROM veiculos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: gerenciar_veiculos.php");
    exit;
}

// Consulta
$stmt = $pdo->query("SELECT * FROM veiculos ORDER BY id DESC");
$veiculos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Gerenciar Veículos</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>

<div class="container mt-4">
  <h1>Gerenciar Veículos</h1>

  <!-- Formulário de cadastro -->
  <form method="POST" class="mb-4">
    <div class="form-row">
      <div class="form-group col-md-3">
        <label>Placa</label>
        <input type="text" name="placa" class="form-control" required>
      </div>
      <div class="form-group col-md-5">
        <label>Modelo</label>
        <input type="text" name="modelo" class="form-control" required>
      </div>
      <div class="form-group col-md-1 d-flex align-items-end">
        <button type="submit" class="btn btn-success">Adicionar</button>
      </div>
    </div>
  </form>

  <!-- Tabela de veículos -->
  <table class="table table-bordered">
    <thead class="thead-dark">
      <tr>
        <th>Placa</th>
        <th>Modelo</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($veiculos as $v): ?>
        <tr>
          <td><?= htmlspecialchars($v['placa']) ?></td>
          <td><?= htmlspecialchars($v['modelo']) ?></td>
          <td>
            <a href="?remover=<?= $v['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover este veículo?')">Remover</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
