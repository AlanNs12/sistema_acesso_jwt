<?php
session_start();
require_once '../conexao.php';
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<div class='alert alert-danger'>Acesso negado. Apenas administradores podem excluir registros.</div>";
    exit;
}

$registro_id = $_POST['registro_id'] ?? null;

if (!$registro_id) {
    echo "ID do registro não fornecido.";
    exit;
}

// Apagar o retorno, se existir
$stmt = $pdo->prepare("DELETE FROM registros_veiculos WHERE saida_id = ?");
$stmt->execute([$registro_id]);

// Apagar o registro de saída
$stmt = $pdo->prepare("DELETE FROM registros_veiculos WHERE id = ?");
$stmt->execute([$registro_id]);

header("Location: registros_veiculos_agrupados.php");
exit;
?>