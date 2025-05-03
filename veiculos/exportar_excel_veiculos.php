<?php
require_once '../conexao.php';

$data_filtro = $_GET['data'] ?? date('Y-m-d');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=registros_veiculos_$data_filtro.xls");
header("Pragma: no-cache");
header("Expires: 0");

$stmt = $pdo->prepare("SELECT rv.*, v.placa, v.modelo 
                       FROM registros_veiculos rv 
                       JOIN veiculos v ON rv.veiculo_id = v.id 
                       WHERE DATE(rv.data_hora) = ?
                       ORDER BY rv.data_hora DESC");
$stmt->execute([$data_filtro]);
$registros = $stmt->fetchAll();

echo "<table border='1'>";
echo "<tr>
        <th>Data/Hora</th>
        <th>Placa</th>
        <th>Modelo</th>
        <th>Tipo</th>
        <th>Motorista</th>
        <th>Observações</th>
      </tr>";

foreach ($registros as $registro) {
    echo "<tr>
            <td>" . date('d/m/Y H:i', strtotime($registro['data_hora'])) . "</td>
            <td>{$registro['placa']}</td>
            <td>{$registro['modelo']}</td>
            <td>" . ucfirst($registro['tipo_registro']) . "</td>
            <td>{$registro['motorista_responsavel']}</td>
            <td>{$registro['observacoes']}</td>
          </tr>";
}
echo "</table>";
