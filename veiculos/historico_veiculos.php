<?php
require_once '../conexao.php';
session_start();

// Verifica se o usuário é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<div class='alert alert-danger'>Acesso negado. Apenas administradores podem visualizar o histórico de veículos.</div>";
    exit;
}

// Reativar veículo
if (isset($_GET['reativar'])) {
    $id = $_GET['reativar'];

    $stmt = $pdo->prepare("UPDATE veiculos SET ativo = 1 WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['mensagem_sucesso'] = "Veículo reativado com sucesso!";
    header("Location: historico_veiculos.php");
    exit;
}

// Buscar veículos desativados
$stmt = $pdo->prepare("SELECT * FROM veiculos WHERE ativo = 0 ORDER BY id DESC");
$stmt->execute();
$veiculos_desativados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Veículos</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
  <?php include '../menu.php'; ?>

  <div class="container mt-5">
    <h2>Histórico de Veículos Desativados</h2>

    <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
      <div class="alert alert-success"><?= $_SESSION['mensagem_sucesso']; unset($_SESSION['mensagem_sucesso']); ?></div>
    <?php endif; ?>

    <table class="table table-bordered mt-3">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Placa</th>
          <th>Modelo</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($veiculos_desativados as $veiculo): ?>
          <tr>
            <td><?= $veiculo['id'] ?></td>
            <td><?= htmlspecialchars($veiculo['placa']) ?></td>
            <td><?= htmlspecialchars($veiculo['modelo']) ?></td>
            <td>
              <a href="?reativar=<?= $veiculo['id'] ?>" class="btn btn-success btn-sm"
                onclick="return confirm('Deseja reativar este veículo?')">Reativar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (count($veiculos_desativados) === 0): ?>
          <tr>
            <td colspan="4" class="text-center">Nenhum veículo desativado encontrado.</td>
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
