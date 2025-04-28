<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
$conn = new mysqli('127.0.0.1', 'Gonçalo', '1234', 'club_manager'); // Substitua 'club_manager' pelo nome do seu banco de dados

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Consulta para verificar o usuário
    $sql = "SELECT * FROM utilizadores WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($password, $user['senha'])) {
            // Login bem-sucedido
            $_SESSION['username'] = $username;
            header('Location: index.php'); // Redireciona para a página inicial
            exit;
        } else {
            // Senha incorreta
            $error = "Usuário ou senha inválidos.";
        }
    } else {
        // Usuário não encontrado
        $error = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center mb-4">Login</h1>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center" cargo="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Utilizador</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
    <!-- Link para o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>