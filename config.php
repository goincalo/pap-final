<?php
define('DBHOST', 'localhost');
define('DBNAME', "club_manager");
define('DBUSERNAME', 'Gonçalo');
define('DBPASSWORD', '1234');
 
function connect_db() {
    try {
        // Criação da string DSN
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
 
        // Criação da conexão PDO
        $pdo = new PDO($dsn, DBUSERNAME, DBPASSWORD);
 
        // Configurar o modo de erro do PDO para exceções
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        return $pdo; // Retorna o objeto PDO
    } catch (PDOException $e) {
        // Exibe mensagem de erro em caso de falha
        die("Erro ao conectar à base de dados: " . $e->getMessage());
    }
}
