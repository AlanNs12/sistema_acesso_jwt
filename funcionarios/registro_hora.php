<?php
require_once '../conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $tipo_registro = $_POST['tipo_registro'];
    $horario_manual = $_POST['horario_manual'] ?? null;
    $observacoes = $_POST['observacoes'] ?? null;

    date_default_timezone_set('America/Sao_Paulo');

    if ($horario_manual) {
        $data_hora = str_replace('T', ' ', $horario_manual) . ':00'; // adiciona segundos
    } else {
        $data_hora = date('Y-m-d H:i:s');
    }

    // Busca o último registro do funcionário
    $stmt = $pdo->prepare("SELECT tipo_registro FROM registros WHERE funcionario_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$funcionario_id]);
    $ultimo = $stmt->fetch();

    $erro = false;

    // Validações de sequência
    if ($tipo_registro === 'entrada' && $ultimo && $ultimo['tipo_registro'] === 'entrada') {
        $erro = 'Erro: Já existe uma entrada registrada sem uma saída.';
    } elseif ($tipo_registro === 'saida' && (!$ultimo || ($ultimo['tipo_registro'] !== 'entrada' && $ultimo['tipo_registro'] !== 'retorno_almoco'))) {
        $erro = 'Erro: Saída só pode ser registrada após uma entrada ou retorno do almoço.';
    } elseif ($tipo_registro === 'almoco' && (!$ultimo || $ultimo['tipo_registro'] !== 'entrada')) {
        $erro = 'Erro: Saída para almoço só pode ser registrada após uma entrada.';
    } elseif ($tipo_registro === 'retorno_almoco' && (!$ultimo || $ultimo['tipo_registro'] !== 'almoco')) {
        $erro = 'Erro: Retorno do almoço só pode ser registrado após saída para almoço.';
    }

    if ($erro) {
        echo "<div class=\"alert alert-danger\"><strong>$erro</strong></div>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO registros (funcionario_id, tipo_registro, data_hora, observacoes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$funcionario_id, $tipo_registro, $data_hora, $observacoes]);
        echo "<div class=\"alert alert-success\"><strong>Registro de {$tipo_registro} realizado com sucesso!</strong></div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Registro de Entrada/Saída</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<?php include '../menu.php'; ?>

<div class="container">
  <h1>Registrar Entrada, Saída ou Almoço</h1>
  <form action="registro_hora.php" method="POST">
    <label>Funcionário:</label>
    <select name="funcionario_id" required>
      <option value="" hidden>Selecione</option>
      <?php
      $stmt = $pdo->query("SELECT * FROM funcionarios");
      $funcionarios = $stmt->fetchAll();
      foreach ($funcionarios as $f) {
          echo "<option value='{$f['id']}'>{$f['nome']}</option>";
      }
      ?>
    </select>
    <br><br>

    <label>Tipo de Registro:</label>
    <select name="tipo_registro" required>
      <option value="" hidden>Selecione uma opção</option>
      <option value="entrada">Entrada (Entry)</option>
      <option value="saida">Saída (Exit)</option>
      <option value="almoco">Saída para Almoço (Leaving for Lunch)</option>
      <option value="retorno_almoco">Retorno do Almoço (Return from Lunch)</option>
    </select>
    <br><br>

    <label>Data e Hora Manual (opcional):</label>
    <input type="datetime-local" name="horario_manual">
    <br><br>

    <label>Observações (opcional):</label>
    <textarea name="observacoes" rows="3" class="form-control" placeholder="Ex: Saiu mais cedo por motivo de consulta médica"></textarea>
    <br>

    <button class="btn btn-primary" type="submit">Registrar</button>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
