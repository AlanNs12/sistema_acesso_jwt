<?php
require_once '../conexao.php';
$stmt = $pdo->query("SELECT * FROM funcionarios");
$funcionarios = $stmt->fetchAll();

session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login_page.php"); // Alterado para login_page.php
    exit;
}

// Apenas admins podem listar e gerenciar todos os funcionários desta forma
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    $_SESSION['acesso_negado_erro'] = "Você não tem permissão para gerenciar funcionários.";
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Funcionários</title>
  <link rel="stylesheet" href="../styles/lumen_bootstrap.min.css">
  <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
</head>

<body>
  <?php include '../menu.php'; ?>

  <div class="container">
    <h1> &#128100; Funcionários Cadastrados</h1>
    <div class="w-100" style="max-width: 400px;">
      <table border="1" cellpadding="10" cellspacing="0">
        <tr>
          <th>Nome</th>
          <th>CPF</th>
          <th>Função</th>
          <th>Ações</th>
        </tr>
        <?php foreach ($funcionarios as $f): ?>
          <tr>
            <td><?= htmlspecialchars($f['nome']) ?></td>
            <td><?= htmlspecialchars($f['cpf']) ?></td>
            <td><?= htmlspecialchars($f['funcao']) ?></td>
            <td>
              &#128221; <a href="form_editar_funcionario.php?id=<?= $f['id'] ?>"> Editar</a> |
              &#10060; <a href="excluir_funcionario.php?id=<?= $f['id'] ?>"
                onclick="return confirm('Deseja excluir?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
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