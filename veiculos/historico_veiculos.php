<?php
require_once '../conexao.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login_page.php");
    exit;
}

// Verifica se o usuário é administrador
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    $_SESSION['acesso_negado_erro'] = "Você não tem permissão para acessar o histórico de veículos.";
    header("Location: ../dashboard.php");
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
  <link rel="stylesheet" href="../styles/lumen_bootstrap.min.css">
  <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
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
</body>
</html>
