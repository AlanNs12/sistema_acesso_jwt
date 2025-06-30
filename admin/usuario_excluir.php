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

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
if ($stmt->execute([$id])) {
    header("Location: ../usuarios.php?msg=excluido");
    exit;
} else {
    echo "<div class='alert alert-danger'>Erro ao excluir usuário.</div>";
}
