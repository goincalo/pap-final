<?php
// Configuração do banco de dados
$host = '127.0.0.1'; // Endereço do servidor
$dbname = 'club_manager'; // Nome do banco de dados (corrija aqui)
$username = 'Gonçalo'; // Nome de usuário do banco de dados
$password = '1234'; // Senha do banco de dados

try {
    // Cria a conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db = $pdo;
} catch (PDOException $e) {
    // Exibe uma mensagem de erro e encerra o script
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
