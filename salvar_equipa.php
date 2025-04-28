<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

if (isset($_POST['nome'], $_POST['genero'], $_POST['tipo'], $_POST['id_clube'])) {
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $tipo = $_POST['tipo'];
    $id_clube = $_POST['id_clube'];

    $sql = "INSERT INTO equipas (nome, genero, tipo, id_clube, created_at) 
            VALUES (:nome, :genero, :tipo, :id_clube, NOW())";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':id_clube', $id_clube, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona com mensagem de sucesso
        header("Location: equipas.php?success=1");
    } catch (Exception $e) {
        // Mostra erro e termina o script
        echo "Erro ao salvar a equipa: " . $e->getMessage();
        exit();
    }
} else {
    echo "Parâmetros obrigatórios não enviados.";
    exit();
}
?>
