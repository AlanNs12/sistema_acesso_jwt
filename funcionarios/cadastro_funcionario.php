<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.html");
  exit;
}

require_once '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'] ?? '';
  $cpf = $_POST['cpf'] ?? '';
  $funcao = $_POST['funcao'] ?? '';

  if ($nome && $cpf && $funcao) {
    $stmt = $pdo->prepare("INSERT INTO funcionarios (nome, cpf, funcao) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $cpf, $funcao]);

    echo "<script>
            alert('Funcionário cadastrado com sucesso. (Employee successfully registered.)');
            window.location.href = 'listar_registros.php';
        </script>";
    exit;
  } else {
    echo "<script>
            alert('Todos os campos são obrigatórios.');
            window.location.href = 'cadastro_funcionario.php';
        </script>";
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Funcionário</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
  <?php include '../menu.php'; ?>

  <div class="container">
    <h1>Cadastrar Funcionário</h1>
    <form action="cadastro_funcionario.php" method="POST">
      <label>Nome:</label>
      <input type="text" name="nome" required>
      <label>CPF:</label>
      <input type="text" name="cpf" required>
      <label>Função:</label>
      <input type="text" name="funcao" required>
      <button type="submit">Cadastrar</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>