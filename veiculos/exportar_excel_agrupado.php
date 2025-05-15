<?php
require_once '../conexao.php';
date_default_timezone_set('America/Sao_Paulo');


header("Content-Type: application/vnd.ms-excel");
$hoje = date('Y-m-d');
header("Content-Disposition: attachment; filename=registros_agrupados_$hoje.xls");
header("Pragma: no-cache");
header("Expires: 0");

$stmt = $pdo->query("
    SELECT rv.*, v.placa, v.modelo 
    FROM registros_veiculos rv 
    JOIN veiculos v ON rv.veiculo_id = v.id 
    ORDER BY v.id, rv.data_hora ASC
");
$registros = $stmt->fetchAll();


$agrupados = [];
foreach ($registros as $registro) {
    $veiculo_id = $registro['veiculo_id'];
    if ($registro['tipo_registro'] === 'saida') {
        $agrupados[] = [
            'saida_data' => date('d/m/Y', strtotime($registro['data_hora'])),
            'saida_hora' => date('H:i', strtotime($registro['data_hora'])),
            'motorista' => $registro['motorista_responsavel'],
            'veiculo' => $registro['placa'] . ' - ' . $registro['modelo'],
            'observacoes' => $registro['observacoes'],
            'retorno_data' => '',
            'retorno_hora' => ''
        ];
    } elseif ($registro['tipo_registro'] === 'retorno') {
        for ($i = count($agrupados) - 1; $i >= 0; $i--) {
            if (
                $agrupados[$i]['veiculo'] === $registro['placa'] . ' - ' . $registro['modelo']
                && $agrupados[$i]['retorno_data'] === ''
            ) {
                $agrupados[$i]['retorno_data'] = date('d/m/Y', strtotime($registro['data_hora']));
                $agrupados[$i]['retorno_hora'] = date('H:i', strtotime($registro['data_hora']));
                break;
            }
        }
    }
}

echo "<table border='1'>";
echo "<tr>
        <th>Data Saída</th>
        <th>Hora Saída</th>
        <th>Condutor</th>
        <th>Veículo</th>
        <th>Data Retorno</th>
        <th>Hora Retorno</th>
        <th>Observações</th>
      </tr>";

foreach ($agrupados as $linha) {
    echo "<tr>
            <td>{$linha['saida_data']}</td>
            <td>{$linha['saida_hora']}</td>
            <td>{$linha['motorista']}</td>
            <td>{$linha['veiculo']}</td>
            <td>{$linha['retorno_data']}</td>
            <td>{$linha['retorno_hora']}</td>
            <td>{$linha['observacoes']}</td>
          </tr>";
}
echo "</table>";
