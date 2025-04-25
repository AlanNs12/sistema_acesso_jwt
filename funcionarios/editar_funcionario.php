<?php
require_once 'conexao.php';
$id = $_POST['id'];
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$funcao = $_POST['funcao'];

$stmt = $pdo->prepare("UPDATE funcionarios SET nome = ?, cpf = ?, funcao = ? WHERE id = ?");
$stmt->execute([$nome, $cpf, $funcao, $id]);
header("Location: listar_funcionarios.php");
?>
