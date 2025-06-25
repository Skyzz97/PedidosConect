<?php
if (ob_get_level()) {
    ob_clean();
}

chdir(__DIR__); // garante que caminhos relativos funcionem no Render

require('tfpdf/tfpdf.php');

$dados = json_decode($_POST['json'], true);
if (!$dados || !isset($dados['cliente']) || !isset($dados['itens'])) {
    http_response_code(400);
    echo "Dados inválidos.";
    exit;
}

$c = $dados['cliente'];
$itens = $dados['itens'];
$obs = $dados['observacoes'] ?? '';
$pagamento = $dados['prazoPagamento'] ?? ($dados['condicoes'] ?? '');
$frete_tipo = $dados['frete'] ?? 'CIF';
$transportadora = $dados['transportadora'] ?? '';
$pedidoNum = $dados['numero'] ?? 'N/A';
$dataPedido = date('d/m/Y');

$pdf = new tFPDF();
$pdf->AddPage();
$pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu', 'B', 'DejaVuSans-Bold.ttf', true);
$pdf->SetFont('DejaVu', '', 10);

// Logo
$pdf->Image('logo.png', 10, 10, 40);

// Cabeçalho do pedido
$pdf->SetXY(150, 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 6, "PEDIDO", 0, 2, 'C');
$pdf->SetFont('DejaVu', 'B', 14);
$pdf->SetTextColor(255, 0, 0);
$pdf->Cell(50, 6, $pedidoNum, 0, 2, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('DejaVu', '', 9);
$pdf->Cell(50, 6, "Data da emissão", 0, 2, 'C');
$pdf->SetTextColor(7, 64, 82);
$pdf->Cell(50, 6, $dataPedido, 0, 2, 'C');
$pdf->Ln(15);

// ===============================
// DADOS DO CLIENTE
// ===============================
function label($pdf, $texto) {
    $pdf->SetFont('DejaVu', 'B', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($texto[0], 6, $texto[1], 1, 0, 'L', true);
    $pdf->SetFont('DejaVu', '', 9);
    $pdf->SetTextColor(7, 64, 82);
}

$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

// Nome
label($pdf, [25, 'NOME:']);
$pdf->Cell(0, 6, $c['nome'], 1, 1, 'L', true);

// Endereço
label($pdf, [25, 'ENDEREÇO:']);
$pdf->Cell(91, 6, $c['endereco'], 1, 0, 'L', true);
label($pdf, [6, 'UF:']);
$pdf->Cell(40, 6, $c['uf'], 1, 0, 'L', true);
label($pdf, [8, 'CEP:']);
$pdf->Cell(0, 6, $c['cep'], 1, 1, 'L', true);

// Cidade, Fone, Email
label($pdf, [25, 'CIDADE:']);
$pdf->Cell(50, 6, $c['cidade'], 1, 0, 'L', true);
label($pdf, [15, 'FONE:']);
$pdf->Cell(30, 6, $c['telefone'], 1, 0, 'L', true);
label($pdf, [12, 'EMAIL:']);
$pdf->Cell(0, 6, $c['email'], 1, 1, 'L', true);

// CNPJ, Inscrição Estadual, Comprador
label($pdf, [25, 'CNPJ:']);
$pdf->Cell(50, 6, $c['cnpj'], 1, 0, 'L', true);
label($pdf, [25, 'INSC.EST.:']);
$pdf->Cell(50, 6, $c['inscEstadual'], 1, 0, 'L', true);
label($pdf, [23, 'COMPRADOR:']);
$pdf->Cell(0, 6, $c['comprador'], 1, 1, 'L', true);

// Endereço de cobrança
label($pdf, [35, 'END. DE COBRANÇA:']);
$pdf->Cell(50, 6, $c['endCobranca'] ?? '', 1, 0, 'L', true);
label($pdf, [6, 'UF:']);
$pdf->Cell(10, 6, $c['ufCobranca'] ?? '', 1, 0, 'L', true);
label($pdf, [9, 'CEP:']);
$pdf->Cell(25, 6, $c['cepCobranca'] ?? '', 1, 0, 'L', true);
label($pdf, [15, 'CIDADE:']);
$pdf->Cell(0, 6, $c['cidadeCobranca'] ?? '', 1, 1, 'L', true);

// Contato cobrança
label($pdf, [20, 'CONTATO:']);
$pdf->Cell(0, 6, $c['contatoCobranca'] ?? '', 1, 1, 'L', true);

// ===============================
// TABELA DE PRODUTOS
// ===============================
$pdf->Ln(4);
$pdf->SetFont('DejaVu', 'B', 9);
$pdf->SetFillColor(220);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(20, 7, 'QUANT.', 1, 0, 'C', true);
$pdf->Cell(25, 7, 'CÓDIGO', 1, 0, 'C', true);
$pdf->Cell(75, 7, 'PRODUTO', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'VALOR UNITÁRIO', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'VALOR TOTAL', 1, 1, 'C', true);


$pdf->SetFont('DejaVu', '', 9);
$pdf->SetTextColor(7, 64, 82);
$totalGeral = 0;
$maxLinhas = 15;
$linhasUsadas = 0;

foreach ($itens as $item) {
    $qtd = (int) $item['qtd'];
    $preco = (float) $item['preco'];
    $total = $qtd * $preco;
    $totalGeral += $total;

   $pdf->Cell(20, 7, $item['qtd'], 1, 0, 'C');
$pdf->Cell(25, 7, $item['codigo'] ?? '', 1, 0, 'C');
$pdf->Cell(75, 7, $item['nome'], 1, 0, 'L');
$pdf->Cell(35, 7, number_format($item['preco'], 2, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 7, number_format($total, 2, ',', '.'), 1, 1, 'R');

    $linhasUsadas++;
}

// Linhas em branco
for ($i = $linhasUsadas; $i < $maxLinhas; $i++) {
    $pdf->Cell(20, 7, '', 1);
$pdf->Cell(25, 7, '', 1);
$pdf->Cell(75, 7, '', 1);
$pdf->Cell(35, 7, '', 1);
$pdf->Cell(35, 7, '', 1, 1);

}

// TOTAL
$pdf->SetFont('DejaVu', 'B', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(155, 7, 'TOTAL', 1, 0, 'R');
$pdf->SetTextColor(7, 64, 82);
$pdf->Cell(35, 7, number_format($totalGeral, 2, ',', '.'), 1, 1, 'R');

$pdf->Ln(5);

// OBSERVAÇÕES
$pdf->SetFont('DejaVu', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(10, 6, 'OBS.:');
$pdf->SetTextColor(7, 64, 82);
$pdf->MultiCell(0, 6, $obs);

// Rodapé de frete e prazo
$pdf->Cell(35, 6, 'TRANSPORTADORA:', 0, 0);
$pdf->Cell(80, 6, $transportadora, 0, 0);
$pdf->Cell(40, 6, 'FRETE:');
$pdf->Cell(10, 6, $frete_tipo == 'CIF' ? '(x)' : '( )');
$pdf->Cell(10, 6, 'CIF');
$pdf->Cell(10, 6, $frete_tipo == 'FOB' ? '(x)' : '( )');
$pdf->Cell(10, 6, 'FOB');

$pdf->Ln(7);
$pdf->Cell(60, 6, 'PRAZO DE PAGAMENTO:', 0, 0);
$pdf->SetTextColor(255, 0, 0);
$pdf->Cell(120, 6, $pagamento);
$pdf->SetTextColor(0);

// Assinaturas
$pdf->Ln(15);
$pdf->Cell(95, 6, '_________________________________', 0, 0, 'C');
$pdf->Cell(95, 6, '_________________________________', 0, 1, 'C');
$pdf->Cell(95, 6, $c['vendedor'] ?? 'ASSINATURA DO VENDEDOR', 0, 0, 'C');
$pdf->Cell(95, 6, $c['comprador'] ?? 'ASSINATURA DO CLIENTE', 0, 1, 'C');

// Output
$pdf->Output('I', 'Pedido_' . preg_replace('/[^a-zA-Z0-9]/', '_', $c['nome']) . '.pdf');
