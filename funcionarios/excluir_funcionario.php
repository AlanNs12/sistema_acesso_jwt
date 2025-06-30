<?php
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login_page.php");
    exit;
}

// Verifica se o usuário é admin para excluir
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    die("Acesso negado. Você não tem permissão para excluir funcionários.");
}

require_once '../conexao.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM funcionarios WHERE id = ?");
$stmt->execute([$id]);
header("Location: listar_funcionarios.php");
?>