<?php
session_start();

require 'conexao.php';

$email = $_POST['email'];
$senha_postada = $_POST['senha']; // Renomeado para clareza

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

// Verifica se o usuário existe e se a senha postada corresponde à senha hasheada no banco
if ($usuario && password_verify($senha_postada, $usuario['senha'])) {
    session_regenerate_id(true); // Regenera o ID da sessão para segurança
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'];
    header("Location: dashboard.php");
    exit; // Importante adicionar exit após redirecionamento
} else {
    // Em vez de 'echo', redirecionar de volta para o login com uma mensagem de erro
    $_SESSION['login_erro'] = "E-mail ou senha inválidos.";
    header("Location: login.html?erro=1"); // Adiciona um parâmetro para indicar erro no JS se necessário
    exit; // Importante adicionar exit após redirecionamento
}

?>