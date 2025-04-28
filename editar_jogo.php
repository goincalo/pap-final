<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['titulo'], $_POST['descricao'])) {
        $id = $_POST['id'];
        $titulo = htmlspecialchars($_POST['titulo']);
        $descricao = htmlspecialchars($_POST['descricao']);

        // Verifica se uma nova imagem foi enviada
        $nome_imagem = null;
        if (isset($_FILES['caminho_imagem']) && $_FILES['caminho_imagem']['error'] === UPLOAD_ERR_OK) {
            $nome_imagem = basename($_FILES['caminho_imagem']['name']);
            $destino_imagem = __DIR__ . '/public/Imagens/' . $nome_imagem;

            // Move o arquivo enviado para o diretório de imagens
            if (!move_uploaded_file($_FILES['caminho_imagem']['tmp_name'], $destino_imagem)) {
                echo json_encode(['success' => false, 'message' => 'Erro ao fazer upload da imagem.']);
                exit();
            }
        }

        try {
            // Atualiza os dados no banco de dados
            $sql = "UPDATE jogos_eventos 
                    SET titulo = :titulo, descricao = :descricao, 
                        caminho_imagem = COALESCE(:caminho_imagem, caminho_imagem), updated_at = NOW()
                    WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->bindParam(':caminho_imagem', $nome_imagem, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar evento: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Parâmetros insuficientes fornecidos.']);
    }
}
?>
