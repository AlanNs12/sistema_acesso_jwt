<?php
session_start();
require_once '../conexao.php';
date_default_timezone_set('America/Sao_Paulo');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login_page.php");
    exit;
}

// Verifica se o usuário é admin para excluir
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    $_SESSION['acesso_negado_erro'] = "Você não tem permissão para excluir registros de veículos.";
    header("Location: gerenciar_veiculos.php"); // Redireciona de volta para a gerência
    exit;
}

$veiculo_id = $_POST['veiculo_id'] ?? null;

if (!$veiculo_id) {
    echo "ID do veículo não fornecido.";
    exit;
}

try {
    // Tentar excluir o veículo
    $stmt = $pdo->prepare("DELETE FROM veiculos WHERE id = ?");
    $stmt->execute([$veiculo_id]);

    $_SESSION['mensagem_sucesso'] = "Veículo excluído com sucesso.";
} catch (PDOException $e) {
    // Caso ocorra erro de integridade (referência existente), desativar o veículo
    if ($e->getCode() === '23000') {
        $pdo->prepare("UPDATE veiculos SET ativo = 0 WHERE id = ?")->execute([$veiculo_id]);
        $_SESSION['mensagem_erro'] = "O veículo possui registros vinculados e não pode ser excluído. Ele foi desativado, mas os dados foram mantidos.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao excluir veículo: " . $e->getMessage();
    }
}

header("Location: gerenciar_veiculos.php");
exit;
?>
