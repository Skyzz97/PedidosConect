<?php
header('Content-Type: application/json');

$filePath = __DIR__ . '/culturas.txt';

if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(['error' => 'Arquivo culturas_com_codigo.txt não encontrado.']);
    exit;
}

$produtos = [];
$lines = explode("\n", file_get_contents($filePath));
$currentCultura = '';
$currentProduto = [];
$produtoId = 1;

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) {
        if (!empty($currentProduto)) {
            $produtos[] = $currentProduto;
            $currentProduto = [];
            $produtoId++;
        }
        continue;
    }

    if (strpos($line, 'Cultura:') === 0) {
        $currentCultura = trim(str_replace('Cultura:', '', $line));
    } elseif (strpos($line, 'Variedade:') === 0) {
        if (!empty($currentProduto)) {
            $produtos[] = $currentProduto;
            $produtoId++;
        }

        $currentProduto = [
            'id' => $produtoId,
            'nome' => trim(str_replace('Variedade:', '', $line)),
            'categoria' => 'sementes',
            'cultura' => $currentCultura,
            'descricao' => '',
            'resistencias' => '',
            'periodo' => '',
            'empresa' => '',
            'imagemPrincipal' => '',
            'outrasImagens' => []
        ];
    } elseif (strpos($line, 'Código:') === 0) {
        $currentProduto['codigo'] = trim(str_replace('Código:', '', $line));
    } elseif (strpos($line, 'Características:') === 0) {
        $currentProduto['descricao'] = trim(str_replace('Características:', '', $line));
    } elseif (strpos($line, 'Resistências:') === 0) {
        $currentProduto['resistencias'] = trim(str_replace('Resistências:', '', $line));
    } elseif (strpos($line, 'Período de Plantio:') === 0) {
        $currentProduto['periodo'] = trim(str_replace('Período de Plantio:', '', $line));
    } elseif (strpos($line, 'Empresa:') === 0) {
        $currentProduto['empresa'] = trim(str_replace('Empresa:', '', $line));
    } elseif (strpos($line, 'Imagem Principal:') === 0) {
        $currentProduto['imagemPrincipal'] = trim(str_replace('Imagem Principal:', '', $line));
    } elseif (strpos($line, 'Outras Imagens:') === 0) {
        $imgs = trim(str_replace('Outras Imagens:', '', $line));
        $currentProduto['outrasImagens'] = array_filter(array_map('trim', explode(',', $imgs)));
    }
}

if (!empty($currentProduto)) {
    $produtos[] = $currentProduto;
}

echo json_encode($produtos);
