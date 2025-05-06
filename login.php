<?php
session_start();
require(__DIR__ . '/config.php');
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $senha = $_POST["senha"];
 
    $link = connect_db('');
    $sql = "SELECT * FROM utilizadores WHERE nome = :nome";
    $stmt = $link->prepare($sql);
    $stmt->execute([":nome" => $nome]);
 
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // Verifica a senha
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['nome'] = $nome;
            $_SESSION['cargo'] = strtolower(trim($user['cargo']));
            header('Location: index.php');
            exit;
        } else {
            $error = "Utilizador ou senha inválidos.";
        }
    } else {
        $error = "Utilizador ou senha inválidos.";
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
                    <label for="nome" class="form-label">Utilizador</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
    <!-- Link para o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
 