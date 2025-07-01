<?php
// Incluir arquivo de configuração
require_once 'config.php';

// Criar conexão com o banco de dados
$conn = new mysqli(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8");

// Primeiro, extrair apenas os nomes dos arquivos
$sql1 = "UPDATE produtos 
         SET imagem = SUBSTRING_INDEX(imagem, '/', -1)
         WHERE imagem IS NOT NULL";

if ($conn->query($sql1) === TRUE) {
    echo "Caminhos resetados para apenas nomes de arquivos.<br>";
} else {
    echo "Erro ao resetar caminhos: " . $conn->error . "<br>";
}

// Adicionar o caminho correto
$sql2 = "UPDATE produtos 
         SET imagem = CONCAT('assets/images/produtos_loja/', imagem)
         WHERE imagem NOT LIKE 'assets/images/produtos_loja/%' AND imagem IS NOT NULL";

if ($conn->query($sql2) === TRUE) {
    echo "Prefixo correto 'assets/images/produtos_loja/' adicionado com sucesso.<br>";
} else {
    echo "Erro ao adicionar prefixo: " . $conn->error . "<br>";
}

// Verificar os resultados após a atualização
$result = $conn->query("SELECT id_produto, nome, imagem FROM produtos");

if ($result->num_rows > 0) {
    echo "<h3>Caminhos das imagens atualizados:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Caminho da Imagem</th><th>Arquivo Existe?</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        $image_path = $row["imagem"];
        $file_exists = file_exists(str_replace("assets/", "public/assets/", $image_path)) ? "Sim" : "Não";
        
        echo "<tr>";
        echo "<td>" . $row["id_produto"] . "</td>";
        echo "<td>" . $row["nome"] . "</td>";
        echo "<td>" . $image_path . "</td>";
        echo "<td>" . $file_exists . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "Nenhum produto encontrado.";
}

$conn->close();
?> 