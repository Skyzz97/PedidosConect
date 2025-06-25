<?php
$arquivo = 'pedidos.txt';
$pedidos = [];

if (file_exists($arquivo)) {
  $linhas = file($arquivo);
  foreach ($linhas as $linha) {
    $pedido = json_decode($linha, true);
    if ($pedido) $pedidos[] = $pedido;
  }
}

echo json_encode($pedidos);
