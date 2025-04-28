<?php
require(__DIR__ . '/config.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM equipas WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
