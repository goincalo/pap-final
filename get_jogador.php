<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Verifica se o parâmetro 'id' foi enviado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query para buscar os dados do jogador pelo ID
    $sql = "SELECT 
                jogadores.id,
                jogadores.first_name,
                jogadores.last_name,
                jogadores.idade,
                jogadores.genero,
                jogadores.id_posicao,
                jogadores.id_clube,
                jogadores.id_equipa,
                posicoes.nome AS posicao_nome,
                clubes.nome AS clube_nome,
                equipas.nome AS equipa_nome,
                jogadores.created_at
            FROM jogadores
            INNER JOIN posicoes ON jogadores.id_posicao = posicoes.id
            INNER JOIN clubes ON jogadores.id_clube = clubes.id
            LEFT JOIN equipas ON jogadores.id_equipa = equipas.id
            WHERE jogadores.id = :id";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch do resultado
        $jogador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($jogador) {
            // Retorna os dados do jogador em formato JSON
            echo json_encode($jogador);
        } else {
            echo json_encode(['success' => false, 'message' => 'Jogador não encontrado']);
        }
    } catch (Exception $e) {
        // Retorna uma mensagem de erro
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do jogador não fornecido']);
}
?>
