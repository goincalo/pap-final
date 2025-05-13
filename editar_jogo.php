<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Inicializa a conexão com o banco de dados
$db = connect_db();

// Tratamento de requisição GET – Buscar dados do evento para edição
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $db->prepare("SELECT id, titulo, descricao FROM jogos_eventos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($jogo) {
            echo json_encode($jogo);
        } else {
            echo json_encode(['success' => false, 'message' => 'Evento não encontrado.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar evento: ' . $e->getMessage()]);
    }

    exit();
}

// Tratamento de requisição POST – Atualizar evento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['titulo'], $_POST['descricao'])) {
        $id = intval($_POST['id']);
        $titulo = htmlspecialchars(trim($_POST['titulo']));
        $descricao = htmlspecialchars(trim($_POST['descricao']));

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
                        caminho_imagem = COALESCE(:caminho_imagem, caminho_imagem), 
                        updated_at = NOW()
                    WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);

            // Define o valor de caminho_imagem (null se nenhuma imagem foi enviada)
            if ($nome_imagem !== null) {
                $stmt->bindParam(':caminho_imagem', $nome_imagem, PDO::PARAM_STR);
            } else {
                $stmt->bindValue(':caminho_imagem', null, PDO::PARAM_NULL);
            }

            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Evento atualizado com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar evento: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Parâmetros insuficientes fornecidos.']);
    }
} else if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>
