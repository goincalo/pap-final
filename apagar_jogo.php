<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Verifica se o parâmetro 'id' foi enviado via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query para apagar o jogo/evento
    $sql = "DELETE FROM jogos_eventos WHERE id = :id";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Verifica se alguma linha foi realmente removida
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Jogo/Evento removido com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum evento encontrado com o ID fornecido']);
        }
    } catch (Exception $e) {
        // Retorna mensagem de erro
        echo json_encode(['success' => false, 'message' => 'Erro ao remover o evento: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do evento não fornecido']);
}
?>
