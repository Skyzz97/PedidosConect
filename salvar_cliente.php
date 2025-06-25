
<?php

$arquivo = 'clientes.txt';
if (!file_exists($arquivo)) {
  file_put_contents($arquivo, json_encode([]));
}

$clientes = json_decode(file_get_contents($arquivo), true);
header('Content-Type: application/json');

$acao = $_GET['acao'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true);

  if ($acao === 'editar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (isset($clientes[$id])) {
      $clientes[$id] = $input;
      file_put_contents($arquivo, json_encode($clientes, JSON_PRETTY_PRINT));
      echo json_encode(['status' => 'sucesso']);
    } else {
      echo json_encode(['status' => 'erro', 'mensagem' => 'Cliente não encontrado.']);
    }
    exit;
  }

  $clientes[] = $input;
  file_put_contents($arquivo, json_encode($clientes, JSON_PRETTY_PRINT));
  echo json_encode(['status' => 'sucesso']);
  exit;
}

if ($acao === 'excluir' && isset($_GET['id'])) {
  $id = (int)$_GET['id'];
  if (isset($clientes[$id])) {
    array_splice($clientes, $id, 1);
    file_put_contents($arquivo, json_encode($clientes, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'sucesso']);
  } else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Cliente não encontrado.']);
  }
  exit;
}

if ($acao === 'ler') {
  echo json_encode($clientes);
  exit;
}

echo json_encode(['status' => 'erro', 'mensagem' => 'Ação inválida.']);
