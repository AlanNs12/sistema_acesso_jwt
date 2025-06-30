<?php
require_once '../conexao.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login_page.php");
    exit;
}

// A edição de observação pode ser permitida para não admins,
// mas é bom ter a verificação de login.
// Se apenas admins puderem editar, descomente e ajuste o bloco abaixo:
/*
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    $_SESSION['acesso_negado_erro'] = "Você não tem permissão para editar observações.";
    header("Location: listar_registros.php");
    exit;
}
*/

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
