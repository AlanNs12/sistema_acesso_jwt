<?php
require_once '../conexao.php';

// Cabeçalhos com charset e BOM UTF-8
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=registros_" . date('Ymd_His') . ".xls");

// UTF-8 BOM
echo "\xEF\xBB\xBF";  // Importante! Corrige os acentos no Excel

$filtro_funcionario = $_GET['funcionario_id'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

$sql = "SELECT r.id, r.tipo_registro, r.data_hora, r.observacoes, f.nome 
        FROM registros r 
        JOIN funcionarios f ON f.id = r.funcionario_id 
        WHERE 1=1";

$params = [];

if ($filtro_funcionario) {
    $sql .= " AND f.id = ?";
    $params[] = $filtro_funcionario;
}

if ($filtro_data_inicio && $filtro_data_fim) {
    $sql .= " AND DATE(r.data_hora) BETWEEN ? AND ?";
    $params[] = $filtro_data_inicio;
    $params[] = $filtro_data_fim;
}

$sql .= " ORDER BY r.data_hora DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll();

$tipos_registro = [
    'entrada' => 'Entrada (Entry)',
    'saida' => 'Saída (Exit)',
    'almoco' => 'Saída para Almoço (Lunch Out)',
    'retorno_almoco' => 'Retorno do Almoço (Lunch Return)',
];

// Estilo da tabela
echo "
<style>
    table {
        border-collapse: collapse;
        width: 60%;
        font-family: Arial, sans-serif;
        
    }
    th {
        background-color: #2c3e50;
        color: white;
        padding: 6px;
        border: 1px solid #ccc;
        text-align: left;
    }
    td {
        padding: 6px;
        border: 1px solid #ccc;
        vertical-align: top;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
";

echo "<table>";
echo "<tr>
        <th>Employee (Funcionário)</th>
        <th>Type (Tipo)</th>
        <th>Date (Data)</th>
        <th>Time (Hora)</th>
        <th>Observations (Observações)</th>
      </tr>";

foreach ($registros as $r) {
    $data = date('d/m/Y', strtotime($r['data_hora']));
    $hora = date('H:i:s', strtotime($r['data_hora']));
    echo "<tr>";
    echo "<td>" . htmlspecialchars($r['nome']) . "</td>";
    echo "<td>" . ($tipos_registro[$r['tipo_registro']] ?? 'Desconhecido (Unknown)') . "</td>";
    echo "<td>$data</td>";
    echo "<td>$hora</td>";
    echo "<td>" . htmlspecialchars($r['observacoes'] ?? '') . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
