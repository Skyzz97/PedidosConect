<?php
require(__DIR__ . '/../makefont.php');


// Gera a fonte normal
MakeFont('tfpdf/font/unifont/DejaVuSans.ttf', 'cp1252', true);

// Move os arquivos gerados para o diretório correto
rename('DejaVuSans.php', 'tfpdf/font/DejaVuSans.php');
rename('DejaVuSans.z', 'tfpdf/font/DejaVuSans.z');

// Gera a fonte bold
MakeFont('tfpdf/font/unifont/DejaVuSans-Bold.ttf', 'cp1252', true);

// Move os arquivos bold
rename('DejaVuSans-Bold.php', 'tfpdf/font/DejaVuSans-Bold.php');
rename('DejaVuSans-Bold.z', 'tfpdf/font/DejaVuSans-Bold.z');
