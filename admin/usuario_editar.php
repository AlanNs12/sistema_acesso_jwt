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

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='alert alert-danger'>ID do usuário não informado.</div>";
    exit;
}

// Buscar usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo "<div class='alert alert-danger'>Usuário não encontrado.</div>";
    exit;
}

// Atualização
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($nome && $email) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $id]);
        header("Location: ../usuarios.php?msg=atualizado");
        echo "<div class='alert alert-success'>Usuário atualizado com sucesso!</div>";

        $usuario['nome'] = $nome;
        $usuario['email'] = $email;
    } else {
        echo "<div class='alert alert-danger'>Preencha todos os campos!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../styles/lumen_bootstrap.min.css">
    <link rel="shortcut icon" href="../images/logo-dfa.png" type="image/x-icon">
</head>

<body>
    <?php include '../menu.php'; ?>
    <div class="container mt-4">
        <h2>Editar Usuário</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>"
                    required>
            </div>
            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="../usuarios.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>