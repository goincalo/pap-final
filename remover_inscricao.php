<?php
require 'config.php'; // Inclui a configuração do banco de dados

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o ID da inscrição enviado via POST
    $id = $_POST['id'];

    try {
        // Prepara a consulta para remover a inscrição
        $stmt = $db->prepare("DELETE FROM inscricoes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Retorna uma resposta de sucesso em formato JSON
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Retorna uma resposta de erro em formato JSON
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Retorna uma resposta de erro se o método não for POST
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}