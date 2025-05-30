<?php
session_start();

require 'conexao.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome']; // <-- salvando o nome
    $_SESSION['usuario_tipo'] = $usuario['tipo'];
    header("Location: dashboard.php");
} else {
    echo "Login inválido.";
}

?>