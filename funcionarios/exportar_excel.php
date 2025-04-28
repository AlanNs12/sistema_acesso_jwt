<?php
require_once '../conexao.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=registros_" . date('Ymd_His') . ".xls");

$filtro_funcionario = $_GET['funcionario_id'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

$sql = "SELECT r.*, f.nome 
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
    'saida' => 'Saida (Exit)',
    'almoco' => 'Saida para Almoco (Lunch Out)',
    'retorno_almoco' => 'Retorno do Almoco (Lunch Return)',
];

echo "<table border='1'>";
echo "<tr><th>Employee (Funcionario)</th><th>Type (Tipo)</th><th> &#128338; Date and Time (Data e Hora)</th></tr>";

foreach ($registros as $r) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($r['nome']) . "</td>";
    echo "<td>" . ($tipos_registro[$r['tipo_registro']] ?? 'Desconhecido (Unknown)') . "</td>";
    echo "<td>" . date('d/m/Y H:i:s', strtotime($r['data_hora'])) . "</td>";
    echo "</tr>";
}

echo "</table>";
