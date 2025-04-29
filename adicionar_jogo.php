<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');
include 'includes/header.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['titulo'], $_POST['descricao']) && isset($_FILES['caminho_imagem'])) {
        $titulo = htmlspecialchars($_POST['titulo']);
        $descricao = htmlspecialchars($_POST['descricao']);

        // Verifica se a imagem foi enviada e processa o upload
        if (isset($_FILES['caminho_imagem']) && $_FILES['caminho_imagem']['error'] === UPLOAD_ERR_OK) {
            $nome_imagem = basename($_FILES['caminho_imagem']['name']);
            $destino_imagem = __DIR__ . '/public/Imagens/' . $nome_imagem;

            // Move o arquivo enviado para o diretório de imagens
            if (move_uploaded_file($_FILES['caminho_imagem']['tmp_name'], $destino_imagem)) {
                try {
                    // Insere os dados no banco de dados
                    $sql = "INSERT INTO jogos_eventos (titulo, descricao, caminho_imagem, created_at) 
                            VALUES (:titulo, :descricao, :caminho_imagem, NOW())";

                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                    $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
                    $stmt->bindParam(':caminho_imagem', $nome_imagem, PDO::PARAM_STR);
                    $stmt->execute();

                    // Redireciona para a página jogos.php
                    header("Location: jogos.php");
                    exit();
                } catch (Exception $e) {
                    echo "<div class='alert alert-danger'>Erro ao adicionar evento: " . $e->getMessage() . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Erro ao fazer upload da imagem.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Imagem não enviada ou inválida.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Preencha todos os campos obrigatórios.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Evento</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Adicionar Novo Evento</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" id="titulo" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="caminho_imagem" class="form-label">Cartaz do Evento (Imagem)</label>
                <input type="file" id="caminho_imagem" name="caminho_imagem" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Evento</button>
        </form>
    </div>
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
