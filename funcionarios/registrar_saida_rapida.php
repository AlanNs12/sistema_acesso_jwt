<?php
require_once '../conexao.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../login.html");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $registro_id = $_POST['registro_id'];

  // Define fuso horário de Brasília
  date_default_timezone_set('America/Sao_Paulo');

  // Pega data e hora atuais
  $data_saida = date('Y-m-d');
  $hora_saida = date('H:i:s');

  // Atualiza o registro no banco com data e hora de saída
  $stmt = $pdo->prepare("UPDATE registros_funcionarios 
                         SET data_saida = ?, hora_saida = ? 
                         WHERE id = ?");
  $stmt->execute([$data_saida, $hora_saida, $registro_id]);
}

// Redireciona de volta para a listagem
header("Location: listar_registros.php");
exit;
