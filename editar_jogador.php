<?php
require(__DIR__ . '/config.php');

$link = connect_db('');

// Verifica se é uma requisição GET (buscar dados do jogador)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $link->prepare("SELECT * FROM jogadores WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $jogador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($jogador) {
            echo json_encode($jogador);
        } else {
            echo json_encode(['success' => false, 'message' => 'Jogador não encontrado']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
    exit;
}

// Verifica se é uma requisição POST (atualizar jogador)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $idade = $_POST['idade'] ?? null;
    $genero = $_POST['genero'] ?? '';
    $posicao = $_POST['posicao'] ?? null;
    $id_equipa = $_POST['id_equipa'] ?? null;

    if (!$id || !$first_name || !$last_name || !$idade || !$genero || !$posicao || !$id_equipa) {
        echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos.']);
        exit;
    }

    try {
        $stmt = $link->prepare("UPDATE jogadores SET 
            first_name = :first_name,
            last_name = :last_name,
            idade = :idade,
            genero = :genero,
            posicao = :posicao,
            id_equipa = :id_equipa
        WHERE id = :id");

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':posicao', $posicao);
        $stmt->bindParam(':id_equipa', $id_equipa, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
    exit;
}
