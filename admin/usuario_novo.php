<?php
require_once '../conexao.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: ../login_page.php"); // Ajustado o caminho e nome da página
    exit; // Encerra o script para evitar execução adicional
}

// Verifica se o usuário é admin para acessar esta página
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    // Define uma mensagem de erro e redireciona se não for admin
    $_SESSION['acesso_negado_erro'] = "Você não tem permissão para acessar esta página.";
    header("Location: ../dashboard.php"); // Ajustado o caminho
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && $senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_hash]);

        echo "<div class='alert alert-success'>Usuário cadastrado com sucesso!</div>";
        header("Location: ../usuarios.php?");
    } else {
        echo "<div class='alert alert-danger'>Preencha todos os campos!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Novo Usuário</title>
    <link rel="stylesheet" href="../styles/lumen_bootstrap.min.css">
    <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
</head>

<body>
    <?php include '../menu.php'; ?>
    <div class="container mt-4">
        <h2>Cadastrar Novo Usuário</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="../usuarios.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>