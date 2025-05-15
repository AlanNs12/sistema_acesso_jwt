<?php
require_once '../conexao.php';
date_default_timezone_set('America/Sao_Paulo');

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit;
}

$dataHoraAtual = date('Y-m-d H:i:s');
$motorista = $_POST['motorista'];
$observacoes = $_POST['observacoes'];
$veiculo = $_POST['veiculo']; // formato: "placa - modelo"

// Separar placa para buscar o veículo
$placa = explode(' - ', $veiculo)[0];

// Buscar ID do veículo
$stmt = $pdo->prepare("SELECT id FROM veiculos WHERE placa = ?");
$stmt->execute([$placa]);
$veiculoInfo = $stmt->fetch();

if (!$veiculoInfo) {
    die("Veículo não encontrado.");
}

$veiculo_id = $veiculoInfo['id'];

// Buscar a última saída SEM retorno vinculada a esse veículo
$stmt = $pdo->prepare("
    SELECT id FROM registros_veiculos 
    WHERE veiculo_id = ? AND tipo_registro = 'saida' 
    AND id NOT IN (SELECT saida_id FROM registros_veiculos WHERE saida_id IS NOT NULL)
    ORDER BY data_hora DESC LIMIT 1
");
$stmt->execute([$veiculo_id]);
$saida = $stmt->fetch();

if (!$saida) {
    die("Nenhuma saída pendente encontrada para este veículo.");
}

$saida_id = $saida['id'];

// Inserir retorno vinculado à saída
$stmt = $pdo->prepare("
    INSERT INTO registros_veiculos (veiculo_id, tipo_registro, data_hora, motorista_responsavel, observacoes, saida_id)
    VALUES (?, 'retorno', ?, ?, ?, ?)
");
$stmt->execute([$veiculo_id, $dataHoraAtual, $motorista, $observacoes, $saida_id]);

header("Location: registros_veiculos_agrupados.php");
exit;
?>