<?php
require_once 'conexao.php';

$nome = $_POST['nome'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$funcao = $_POST['funcao'] ?? '';

if ($nome && $cpf && $funcao) {
    $stmt = $pdo->prepare("INSERT INTO funcionarios (nome, cpf, funcao) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $cpf, $funcao]);

    // Exibe pop-up e redireciona
    echo "<script>
        alert('Funcionário cadastrado com sucesso. (Employee successfully registered.)');
        window.location.href = 'listar_registros.php';
    </script>";
} else {
    // Exibe pop-up de erro
    echo "<script>
        alert('Todos os campos são obrigatórios.');
        window.location.href = 'cadastro_funcionario.html';
    </script>";
}
?>