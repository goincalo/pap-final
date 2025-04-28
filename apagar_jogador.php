<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Verifica se o parâmetro 'id' foi enviado via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query para apagar o jogador
    $sql = "DELETE FROM jogadores WHERE id = :id";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Verifica se uma linha foi realmente removida
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum jogador encontrado com o ID fornecido']);
        }
    } catch (Exception $e) {
        // Retorna mensagem de erro em formato JSON
        echo json_encode(['success' => false, 'message' => 'Erro ao apagar jogador: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do jogador não fornecido']);
}
?>
