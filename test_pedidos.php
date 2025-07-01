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
    
    echo "<h2>Teste de acesso direto aos pedidos</h2>";
    
    // Consulta SQL para obter todos os pedidos
    $sql = "SELECT p.*, c.nome_completo AS nome_cliente 
            FROM pedidos p 
            LEFT JOIN clientes c ON p.id_cliente = c.id_cliente 
            ORDER BY p.data_pedido DESC";
    
    $stmt = $pdo->query($sql);
    $pedidos = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "<h3>Total de pedidos encontrados: " . count($pedidos) . "</h3>";
    
    // Exibir tabela simplificada de pedidos
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Cliente</th><th>Código</th><th>Status</th><th>Total</th><th>Data</th></tr>";
    
    foreach ($pedidos as $pedido) {
        echo "<tr>";
        echo "<td>{$pedido->id_pedido}</td>";
        echo "<td>" . ($pedido->nome_cliente ?? "Cliente #{$pedido->id_cliente}") . "</td>";
        echo "<td>{$pedido->codigo_pedido}</td>";
        echo "<td>{$pedido->status}</td>";
        echo "<td>" . number_format($pedido->total, 2, ',', '.') . " €</td>";
        echo "<td>" . date('d/m/Y H:i', strtotime($pedido->data_pedido)) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<h2>Erro ao conectar ao banco de dados:</h2>";
    echo "<p>{$e->getMessage()}</p>";
}
?> 