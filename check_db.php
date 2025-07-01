<?php
// Configurações do banco de dados
define('MYSQL_SERVER',    'localhost');
define('MYSQL_DATABASE',  'php_store');
define('MYSQL_USER',     'root');
define('MYSQL_PASS',     '');
define('MYSQL_CHARSET',  'utf8');

try {
    // Conectar ao banco de dados
    $pdo = new PDO(
        'mysql:host=' . MYSQL_SERVER . ';dbname=' . MYSQL_DATABASE . ';charset=' . MYSQL_CHARSET,
        MYSQL_USER,
        MYSQL_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>Conexão com o banco de dados estabelecida com sucesso!</h2>";
    
    // Verificar a estrutura da tabela pedidos
    $stmt = $pdo->query("DESCRIBE pedidos");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Estrutura da tabela 'pedidos':</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Contar registros na tabela pedidos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pedidos");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Total de registros na tabela 'pedidos': {$count['total']}</h3>";
    
    // Listar todos os pedidos
    $stmt = $pdo->query("SELECT * FROM pedidos");
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Listagem de todos os pedidos:</h3>";
    
    if (count($pedidos) > 0) {
        echo "<table border='1'>";
        echo "<tr>";
        foreach (array_keys($pedidos[0]) as $header) {
            echo "<th>{$header}</th>";
        }
        echo "</tr>";
        
        foreach ($pedidos as $pedido) {
            echo "<tr>";
            foreach ($pedido as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Nenhum pedido encontrado na tabela.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>Erro ao conectar ao banco de dados:</h2>";
    echo "<p>{$e->getMessage()}</p>";
}
?> 