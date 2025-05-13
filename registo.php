<?php
require(__DIR__ . '/config.php');

// Inclui o cabeçalho
include 'includes/header.php';


// Inicia a sessão
//session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $senha = isset($_POST['senha']) ? $_POST['senha'] : null;
    $cargo = isset($_POST['cargo']) ? $_POST['cargo'] : null;

    // Verifica se os campos foram preenchidos
    if (!$nome || !$email || !$senha || !$cargo) {
        $error = "Todos os campos devem ser preenchidos.";
    } else {
        try {
            $link = connect_db();
            // Verifica se o email já está registrado
            $sqlCheck = "SELECT COUNT(*) FROM utilizadores WHERE email = :email";
            $stmtCheck = $link->prepare($sqlCheck);
            $stmtCheck->execute([':email' => $email]);
            $emailExists = $stmtCheck->fetchColumn();

            if ($emailExists) {
                $error = "O email já está registrado. Por favor, use outro.";
            } else {
                // Criptografa a senha
                $hashedSenha = password_hash($senha, PASSWORD_DEFAULT);

                // Prepara a consulta para inserir os dados no banco de dados
                $sql = "INSERT INTO utilizadores (nome, email, senha, cargo) VALUES (:nome, :email, :senha, :cargo)";
                $stmt = $link->prepare($sql);

                // Executa a consulta com os valores
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $hashedSenha,
                    ':cargo' => $cargo,
                ]);

                // Redireciona para a página de login após o registro
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = "Erro ao criar conta: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">Criar Conta</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome de utilizador</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" name="senha" id="senha" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <select name="cargo" id="cargo" class="form-select" required>
                            <option value="">Selecione</option>
                            <option value="administrador">Administrador</option>
                            <option value="treinador">Treinador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Criar Conta</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
