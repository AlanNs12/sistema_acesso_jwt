<?php
require_once 'conexao.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login_page.php"); // Alterado para login_page.php
    exit; // Encerra o script para evitar execução adicional
}

// Verifica se o usuário é admin para acessar esta página
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    $_SESSION['acesso_negado_erro'] = "Você não tem permissão para acessar esta página.";
    header("Location: dashboard.php"); // Redireciona para o dashboard ou outra página
    exit;
}

$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link rel="stylesheet" href="styles/lumen_bootstrap.min.css">
    <link rel="shortcut icon" href="images/logo-dfa.png" type="image/x-icon">
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-4">
        <h2>Lista de Usuários</h2>
        <a href="admin/usuario_novo.php" class="btn btn-success mb-3">Novo Usuário</a>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'excluido'): ?>
            <div class="alert alert-success">Usuário excluído com sucesso!</div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nome']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <a href="admin/usuario_editar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="admin/usuario_excluir.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>