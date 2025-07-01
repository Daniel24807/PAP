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

// Resetar todos os caminhos para padronizar
$reset_sql = "UPDATE produtos 
              SET imagem = SUBSTRING_INDEX(imagem, '/', -1)
              WHERE imagem IS NOT NULL";

if ($conn->query($reset_sql) === TRUE) {
    echo "Caminhos resetados para apenas nomes de arquivos.<br>";
} else {
    echo "Erro ao resetar caminhos: " . $conn->error . "<br>";
}

// Agora, adicionar o prefixo correto a todos
$update_sql = "UPDATE produtos 
               SET imagem = CONCAT('assets/images/produtos/', imagem)
               WHERE imagem NOT LIKE 'assets/images/produtos/%' AND imagem IS NOT NULL";

if ($conn->query($update_sql) === TRUE) {
    echo "Prefixo 'assets/images/produtos/' adicionado com sucesso.<br>";
} else {
    echo "Erro ao adicionar prefixo: " . $conn->error . "<br>";
}

// Verificar os resultados após a atualização
$result = $conn->query("SELECT id_produto, nome, imagem FROM produtos");

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Imagem</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_produto"] . "</td>";
        echo "<td>" . $row["nome"] . "</td>";
        echo "<td>" . $row["imagem"] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "Nenhum produto encontrado.";
}

$conn->close();
?> 