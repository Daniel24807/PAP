<?php
// Definir diretórios
$source_dir = 'public/assets/images/gyeon/';
$target_dir = 'public/assets/images/produtos/';

// Array com os arquivos a serem copiados e renomeados
$files_to_copy = [
    'gyeon-glass-2.jpg' => 'gyeon-glass.jpg',
    'gyeon-wet-coat-2.jpg' => 'gyeon-wet-coat.jpg'
];

// Copiar e renomear os arquivos
foreach ($files_to_copy as $source_file => $target_file) {
    if (file_exists($source_dir . $source_file)) {
        if (copy($source_dir . $source_file, $target_dir . $target_file)) {
            echo "Arquivo {$source_file} copiado e renomeado para {$target_file} com sucesso.<br>";
        } else {
            echo "Erro ao copiar o arquivo {$source_file}.<br>";
        }
    } else {
        echo "O arquivo {$source_file} não existe no diretório de origem.<br>";
    }
}

// Verificar os arquivos no diretório de destino após a cópia
echo "<h3>Arquivos no diretório de produtos após a cópia:</h3>";
$files = scandir($target_dir);
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>{$file}</li>";
    }
}
echo "</ul>";
?> 