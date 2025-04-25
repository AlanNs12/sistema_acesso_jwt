<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $tipo_registro = $_POST['tipo_registro'];

    $stmt = $pdo->prepare("INSERT INTO registros (funcionario_id, tipo_registro) VALUES (?, ?)");
    $stmt->execute([$funcionario_id, $tipo_registro]);
    $nome_classe = 'alert alert-success';
    echo "
    <div class=\"{$nome_classe}\">
    <strong>Registro de {$tipo_registro} realizado com sucesso!</strong>
    </div>";

}
?>
<?php
//validação necessidade de login
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Registro de Entrada/Saída</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<?php include '../menu.php'; ?>

  <div class="container">
    <h1>Registrar Entrada, Saída ou Almoço</h1>
    <h1>Entry/Exit Record</h1>
    <form action="registro_hora.php" method="POST">
      <label>Funcionário:</label>
      <select name="funcionario_id" required>
        <?php
        $stmt = $pdo->query("SELECT * FROM funcionarios");
        $funcionarios = $stmt->fetchAll();
        foreach ($funcionarios as $f) {
            echo "<option value='{$f['id']}'>{$f['nome']}</option>";
        }
        ?>
      </select>
      <br>
      <label>Tipo de Registro:</label>
      <select name="tipo_registro" required>
      <option value="entrada">Entrada</option>
      <option value="saida">Saída</option>
      <option value="almoco">Saída para Almoço</option>
      <option value="retorno_almoco">Retorno do Almoço</option> 
      </select>
      <br><br>
      <button type="submit">Registrar</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
