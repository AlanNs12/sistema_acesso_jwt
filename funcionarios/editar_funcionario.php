<?php
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login_page.php");
    exit;
}

// Verifica se o usuário é admin para editar
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    // Pode redirecionar ou mostrar uma mensagem de erro mais amigável
    die("Acesso negado. Você não tem permissão para editar funcionários.");
}

require_once '../conexao.php';
$id = $_POST['id'];
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$funcao = $_POST['funcao'];

$stmt = $pdo->prepare("UPDATE funcionarios SET nome = ?, cpf = ?, funcao = ? WHERE id = ?");
$stmt->execute([$nome, $cpf, $funcao, $id]);
header("Location: listar_funcionarios.php");
?>