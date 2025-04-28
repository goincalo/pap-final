<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'club_manager'); // Substitua 'nome_do_banco' pelo nome do seu banco de dados

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Consulta para verificar o usuário
    $sql = "SELECT * FROM utilizadores WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login bem-sucedido
        $_SESSION['username'] = $username;
        header('Location: dashboard.php'); // Redireciona para a página de sucesso
        exit;
    } else {
        // Login falhou
        $_SESSION['error'] = "Usuário ou senha inválidos.";
        header('Location: login.php'); // Redireciona de volta para a página de login
        exit;
    }
} else {
    // Se o acesso ao arquivo não for via POST, redireciona para o login
    header('Location: login.php');
    exit;
}
?>