<?php
require_once 'conexao.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<div class='alert alert-danger'>Acesso negado. Apenas administradores podem acessar esta página.</div>";
    exit;
}


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='alert alert-danger'>ID do usuário não informado.</div>";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
if ($stmt->execute([$id])) {
    header("Location: usuarios.php?msg=excluido");
    exit;
} else {
    echo "<div class='alert alert-danger'>Erro ao excluir usuário.</div>";
}
