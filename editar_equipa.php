<?php
require(__DIR__ . '/config.php');
$db = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $db->prepare("SELECT id, nome, genero, tipo FROM equipas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $equipa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($equipa) {
            echo json_encode($equipa);
        } else {
            echo json_encode(['success' => false, 'message' => 'Equipa não encontrada.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
    exit;
}

// Atualização via POST (como já tinhas)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['nome'], $_POST['genero'], $_POST['tipo'])) {
        $id = intval($_POST['id']);
        $nome = htmlspecialchars(trim($_POST['nome']));
        $genero = htmlspecialchars(trim($_POST['genero']));
        $tipo = htmlspecialchars(trim($_POST['tipo']));

        if (empty($nome) || empty($genero) || empty($tipo)) {
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        try {
            $stmt = $db->prepare("UPDATE equipas SET nome = :nome, genero = :genero, tipo = :tipo WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Equipa atualizada com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar equipa: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Parâmetros insuficientes.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}
