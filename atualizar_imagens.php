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

// SQL para atualizar os caminhos das imagens
$sql = "UPDATE produtos 
        SET imagem = CONCAT('assets/images/produtos/', imagem)
        WHERE imagem NOT LIKE 'assets/images/produtos/%'";

// Executar a consulta
if ($conn->query($sql) === TRUE) {
    echo "Caminhos das imagens atualizados com sucesso.<br>";
    
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
} else {
    echo "Erro ao atualizar caminhos das imagens: " . $conn->error;
}

$conn->close();
?> 