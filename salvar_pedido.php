<?php
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
  http_response_code(400);
  echo json_encode(['status' => 'erro', 'mensagem' => 'JSON inválido']);
  exit;
}

$arquivo = 'pedidos.txt';
$registro = json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;

if (file_put_contents($arquivo, $registro, FILE_APPEND)) {
  echo json_encode(['status' => 'ok']);
} else {
  echo json_encode(['status' => 'erro', 'mensagem' => 'Falha ao salvar']);
}
