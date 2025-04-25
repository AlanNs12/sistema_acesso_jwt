<?php
require 'conexao.php';

$usuarios = [
    ['Alan Nascimento', 'alanteste@teste.com', '123456'],
    ['Maria Souza', 'maria@empresa.com', '123456'],
    ['Carlos Lima', 'carlos@empresa.com', '123456'],
    ['Ana Paula', 'ana@empresa.com', '123456'],
    ['Bruno Rocha', 'bruno@empresa.com', '123456'],
    ['Juliana Mendes', 'juliana@empresa.com', '123456']
];

foreach ($usuarios as $usuario) {
    $nome = $usuario[0];
    $email = $usuario[1];
    $senha = password_hash($usuario[2], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $senha]);
}

echo "Usuários cadastrados com sucesso!";
?>
