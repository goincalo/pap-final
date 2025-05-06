<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Verifica se os parâmetros necessários foram enviados via POST
if (isset($_POST['id'], $_POST['first_name'], $_POST['last_name'], $_POST['idade'], $_POST['genero'], $_POST['id_posicao'], $_POST['id_clube'])) {
    $id = $_POST['id'];
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $idade = $_POST['idade'];
    $genero = htmlspecialchars($_POST['genero']);
    $id_posicao = $_POST['id_posicao'];
    $id_clube = $_POST['id_clube'];
    $id_equipa = isset($_POST['id_equipa']) && is_numeric($_POST['id_equipa']) ? $_POST['id_equipa'] : NULL;

    // Validações adicionais
    if (!is_numeric($id) || !is_numeric($idade) || !is_numeric($id_posicao) || !is_numeric($id_clube)) {
        echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
        exit();
    }

    // Query para atualizar os dados do jogador
    $sql = "UPDATE jogadores 
            SET first_name = :first_name, 
                last_name = :last_name, 
                idade = :idade, 
                genero = :genero, 
                id_posicao = :id_posicao, 
                id_clube = :id_clube, 
                id_equipa = :id_equipa, 
                updated_at = NOW()
            WHERE id = :id";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
        $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
        $stmt->bindParam(':id_posicao', $id_posicao, PDO::PARAM_INT);
        $stmt->bindParam(':id_clube', $id_clube, PDO::PARAM_INT);
        $stmt->bindParam(':id_equipa', $id_equipa, PDO::PARAM_INT);
        $stmt->execute();

        // Verifica se a atualização afetou alguma linha
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma alteração foi feita']);
        }
    } catch (Exception $e) {
        // Registra o erro e retorna uma mensagem genérica
        error_log('Erro no editar_jogador.php: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro interno ao atualizar os dados']);
    }
} else {
    // Retorna erro caso os parâmetros obrigatórios não tenham sido enviados
    echo json_encode(['success' => false, 'message' => 'Parâmetros insuficientes fornecidos']);
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>