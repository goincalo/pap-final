<?php
require(__DIR__ . '/config.php'); // Certifique-se de que a conexão com o banco está funcionando

if (isset($_POST['id'], $_POST['nome'], $_POST['genero'], $_POST['tipo'])) {
    $id = $_POST['id'];
    $nome = htmlspecialchars($_POST['nome']);
    $genero = htmlspecialchars($_POST['genero']);
    $tipo = htmlspecialchars($_POST['tipo']);

    $sql = "UPDATE equipas 
            SET nome = :nome, 
                genero = :genero, 
                tipo = :tipo 
            WHERE id = :id";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Parâmetros insuficientes fornecidos.']);
}
?>