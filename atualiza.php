<?php
$versaoAtual = "1.0.0"; // versão atual do seu app

// 1. Solicita a versão mais recente à VM
$dados = json_decode(file_get_contents("http://34.56.1.206/atualizador.php?token=SENHA123"), true);
$versaoNova = $dados["versao"];
$link = $dados["link"];

echo "Versão atual: $versaoAtual\n";
echo "Versão disponível: $versaoNova\n";

if (version_compare($versaoNova, $versaoAtual, '>')) {
    echo "Nova versão disponível! Baixando...\n";

    // 2. Baixa o ZIP (link direto necessário)
    $zipPath = __DIR__ . "/update.zip";

    // OBS: Só funciona com link direto para o arquivo ZIP
    $arquivo = file_get_contents($link);
    if (!$arquivo) {
        die("Erro ao baixar o arquivo. Verifique o link.");
    }

    file_put_contents($zipPath, $arquivo);

    echo "Extraindo atualização...\n";
    $zip = new ZipArchive;
    if ($zip->open($zipPath) === TRUE) {
        $zip->extractTo(__DIR__); // ou outro diretório do seu app
        $zip->close();
        echo "Atualização concluída com sucesso!\n";
    } else {
        echo "Erro ao abrir o arquivo ZIP.\n";
    }

    unlink($zipPath); // remove o zip depois
} else {
    echo "Você já está na versão mais recente.\n";
}
