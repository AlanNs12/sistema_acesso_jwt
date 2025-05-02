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
<nav class="navbar navbar-expand-lg navbar-light bg-dark ">
    <a class="navbar-brand text-light" href="#">
        <img src="/sistema_acesso_jwt/images/logo-circle.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Embassy Of the Philippines
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse " id="navbarNavDropdown">
        
          <ul class="navbar-nav">
              <li class="nav-item dropdown">
              <a href="/sistema_acesso_jwt/dashboard.php" class="ml-auto p-2 bd-highlight"><button  class="btn btn-primary my-2 my-sm-0" type="submit">Home</button></a>
              </li>
              
              <li class="nav-item dropdown">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options (Opções)</button>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink my-lg-0">
          <a href="/sistema_acesso_jwt/funcionarios/listar_registros.php" class="dropdown-item">List of records(Consulta de Registros)</a>
          <a href="/sistema_acesso_jwt/funcionarios/listar_funcionarios.php" class="dropdown-item">List of Employee (Lista Funcionários)</a>
          <a href="/sistema_acesso_jwt/funcionarios/registro_hora.php" class="dropdown-item">New register (Novo Registro)</a>
          <a href="/sistema_acesso_jwt/funcionarios/cadastro_funcionario.php" class="dropdown-item">Register new employee (Cadastrar novo funcionário)</a>
          </div>
          </ul>
          <a href="/sistema_acesso_jwt/logout.php" class="ml-auto p-2 bd-highlight"><button class="btn btn-danger my-2 my-sm-0" type="submit"> Logout (Sair)</button></a>
      </div>  
</nav>


    

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
