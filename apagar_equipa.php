<?php
require(__DIR__ . '/config.php');

$link = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Verifica se existem jogadores associados à equipa
        $check = $link->prepare("SELECT COUNT(*) FROM jogadores WHERE id_equipa = :id");
        $check->bindParam(':id', $id, PDO::PARAM_INT);
        $check->execute();
        $total = $check->fetchColumn();

        if ($total > 0) {
            echo json_encode(['success' => false, 'message' => 'Não é possível apagar esta equipa porque tem jogadores associados.']);
            exit;
        }

        // Se não houver jogadores, apaga a equipa
        $stmt = $link->prepare("DELETE FROM equipas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao apagar equipa.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID não recebido ou método inválido.']);
}
?>