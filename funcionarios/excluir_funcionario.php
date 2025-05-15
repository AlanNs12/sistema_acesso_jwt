<?php
require_once '../conexao.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM funcionarios WHERE id = ?");
$stmt->execute([$id]);
header("Location: listar_funcionarios.php");
?>