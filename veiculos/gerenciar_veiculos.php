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

// Remoção (com tratamento de erro e desativação)
if (isset($_GET['remover'])) {
  $id = $_GET['remover'];

  try {
    $stmt = $pdo->prepare("DELETE FROM veiculos WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['mensagem_sucesso'] = "Veículo excluído com sucesso.";
  } catch (PDOException $e) {
    if ($e->getCode() === '23000') {
      // Desativa o veículo
      $pdo->prepare("UPDATE veiculos SET ativo = 0 WHERE id = ?")->execute([$id]);
      $_SESSION['mensagem_erro'] = "O veículo possui registros e não pode ser excluído. Ele foi desativado, mas os dados foram mantidos.";
    } else {
      $_SESSION['mensagem_erro'] = "Erro ao excluir veículo: " . $e->getMessage();
    }
  }

  header("Location: gerenciar_veiculos.php");
  exit;
}

// Consulta apenas veículos ativos
$stmt = $pdo->query("SELECT * FROM veiculos WHERE ativo = 1 ORDER BY id DESC");
$veiculos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Gerenciar Veículos</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
</head>

<body>
  <?php include '../menu.php'; ?>
  

  <div class="container">
    <div class="card">
      <h1 class="card-header">Gerenciar Veículos</h1>
      <div class="card-body">
        
        <div class="container d-flex justify-content-center">
          <div class="w-100" style="max-width: 1000px;">
            <div class="container mt-4">

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
              <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin'): ?>
  <div class="mb-3">
    <a href="historico_veiculos.php" class="btn btn-secondary">Visualizar Veículos desativados</a>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensagem_sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['mensagem_sucesso']; unset($_SESSION['mensagem_sucesso']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['mensagem_erro'])): ?>
    <div class="alert alert-warning"><?= $_SESSION['mensagem_erro']; unset($_SESSION['mensagem_erro']); ?></div>
<?php endif; ?>
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
                        <a href="?remover=<?= $v['id'] ?>" class="btn btn-danger btn-sm"
                          onclick="return confirm('Tem certeza que deseja remover este veículo?')">Remover</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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