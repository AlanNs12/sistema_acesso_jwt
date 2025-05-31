<?php
require_once '../conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $registro_id = $_POST['registro_id'] ?? '';
  $observacoes = $_POST['observacoes'] ?? '';

  if ($registro_id) {
    $stmt = $pdo->prepare("UPDATE registros_funcionarios SET observacoes = ? WHERE id = ?");
    $stmt->execute([$observacoes, $registro_id]);
  }
}

header("Location: listar_registros.php");
exit;
