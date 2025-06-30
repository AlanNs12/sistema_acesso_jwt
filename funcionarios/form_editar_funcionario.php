<?php
require_once '../conexao.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE id = ?");
$stmt->execute([$id]);
$funcionario = $stmt->fetch();

session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login_page.php"); // Alterado para login_page.php
    exit;
}

// Verifica se o usuário é admin para acessar este formulário de edição
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    $_SESSION['acesso_negado_erro'] = "Você não tem permissão para editar funcionários.";
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Editar Funcionário</title>
  <link rel="stylesheet" href="../styles/lumen_bootstrap.min.css">
  <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
</head>

<body>
  <?php include '../menu.php'; ?>


  <div class="container">
    <h1>Editar Funcionário</h1>
    <form action="editar_funcionario.php" method="POST">
      <input type="hidden" name="id" value="<?= $funcionario['id'] ?>">
      <label>Nome:</label>
      <input type="text" name="nome" value="<?= $funcionario['nome'] ?>" required>
      <label>CPF:</label>
      <input type="text" name="cpf" value="<?= $funcionario['cpf'] ?>" required>
      <label>Função:</label>
      <input type="text" name="funcao" value="<?= $funcionario['funcao'] ?>" required>
      <button type="submit">Salvar</button>
    </form>
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