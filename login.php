<?php
session_start();

require 'conexao.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && $senha === $usuario['senha']) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome']; // <-- salvando o nome
    $_SESSION['usuario_tipo'] = $usuario['tipo']; // após o login bem-sucedido
    header("Location: dashboard.php");
} else {
    echo "Login inválido.";
}

?>