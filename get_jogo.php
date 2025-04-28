<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Verifica se o parâmetro 'id' foi enviado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query para buscar os dados do jogo/evento pelo ID
    $sql = "SELECT id, titulo, descricao, caminho_imagem, created_at, updated_at FROM jogos_eventos WHERE id = :id";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch do resultado
        $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($jogo) {
            // Retorna os dados do jogo em formato JSON
            echo json_encode($jogo);
        } else {
            echo json_encode(['success' => false, 'message' => 'Evento não encontrado']);
        }
    } catch (Exception $e) {
        // Retorna mensagem de erro em formato JSON
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar o evento: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do evento não fornecido']);
}
?>
