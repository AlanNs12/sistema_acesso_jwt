
<?php
require_once 'conexao.php';

$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO administradores (email, senha) VALUES (?, ?)");
$stmt->execute([$email, $senha]);

echo "Administrador cadastrado com sucesso.";
?>
