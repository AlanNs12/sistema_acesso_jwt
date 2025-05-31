<?php
require_once '../conexao.php';

// Headers para forçar download como Excel (.xls)
header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");
header("Content-Disposition: attachment; filename=registros_funcionarios.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Emite BOM para UTF-16LE (evita problemas com acentuação)
echo chr(255) . chr(254);

// Função para converter para UTF-16LE (compatível com Excel)
function encodeExcel($string) {
    return mb_convert_encoding($string, "UTF-16LE", "UTF-8");
}

// Filtros recebidos via GET
$filtro_funcionario = $_GET['funcionario_id'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

// SQL base
$sql = "SELECT rf.*, f.nome 
        FROM registros_funcionarios rf
        JOIN funcionarios f ON f.id = rf.funcionario_id
        WHERE 1=1";

$params = [];

if ($filtro_funcionario) {
    $sql .= " AND f.id = ?";
    $params[] = $filtro_funcionario;
}
if ($filtro_data_inicio && $filtro_data_fim) {
    $sql .= " AND rf.data BETWEEN ? AND ?";
    $params[] = $filtro_data_inicio;
    $params[] = $filtro_data_fim;
}

$sql .= " ORDER BY rf.data DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll();

// Início da tabela HTML com bordas
echo encodeExcel("<table border='1'>");
echo encodeExcel("<tr>
        <th>Funcionário</th>
        <th>Data de Entrada</th>
        <th>Hora de Entrada</th>
        <th>Data de Saída</th>
        <th>Hora de Saída</th>
        <th>Observações</th>
    </tr>");

foreach ($registros as $r) {
    echo encodeExcel("<tr>");
    echo encodeExcel("<td>" . htmlspecialchars($r['nome']) . "</td>");
    echo encodeExcel("<td>" . date('d/m/Y', strtotime($r['data'])) . "</td>");
    echo encodeExcel("<td>" . ($r['hora_entrada'] ?? '-') . "</td>");

    // Data de Saída formatada
    echo encodeExcel("<td>" . (!empty($r['data_saida']) && $r['data_saida'] != '0000-00-00'
        ? date('d/m/Y', strtotime($r['data_saida']))
        : '-') . "</td>");

    echo encodeExcel("<td>" . ($r['hora_saida'] ?? '-') . "</td>");
    echo encodeExcel("<td>" . (!empty($r['observacoes']) ? htmlspecialchars($r['observacoes']) : '-') . "</td>");
    echo encodeExcel("</tr>");
}

echo encodeExcel("</table>");
?>
