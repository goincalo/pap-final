<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');
include 'includes/header.php';

// Código para processar a adição do jogador via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['idade'], $_POST['genero'], $_POST['id_posicao'], $_POST['id_clube'])) {
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $idade = $_POST['idade'];
        $genero = htmlspecialchars($_POST['genero']);
        $id_posicao = $_POST['id_posicao'];
        $id_clube = $_POST['id_clube'];
        $id_equipa = $_POST['id_equipa'] ?? NULL; // `id_equipa` é opcional e pode ser NULL

        // Validações adicionais
        if (!is_numeric($idade) || !is_numeric($id_posicao) || !is_numeric($id_clube)) {
            echo "<div class='alert alert-danger'>Parâmetros inválidos</div>";
        } else {
            // Query para adicionar o jogador
            $sql = "INSERT INTO jogadores (first_name, last_name, idade, genero, id_posicao, id_clube, id_equipa, created_at) 
                    VALUES (:first_name, :last_name, :idade, :genero, :id_posicao, :id_clube, :id_equipa, NOW())";

            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
                $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
                $stmt->bindParam(':id_posicao', $id_posicao, PDO::PARAM_INT);
                $stmt->bindParam(':id_clube', $id_clube, PDO::PARAM_INT);
                $stmt->bindParam(':id_equipa', $id_equipa, PDO::PARAM_INT);
                $stmt->execute();

                echo "<div class='alert alert-success'>Jogador adicionado com sucesso</div>";
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>Erro ao adicionar jogador: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Parâmetros insuficientes fornecidos</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Jogador</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Adicionar Novo Jogador</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="firstName" class="form-label">Primeiro Nome</label>
                <input type="text" id="firstName" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Último Nome</label>
                <input type="text" id="lastName" name="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="idade" class="form-label">Idade</label>
                <input type="number" id="idade" name="idade" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select id="genero" name="genero" class="form-control" required>
                    <option value="">Selecione</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="posicao" class="form-label">Posição</label>
                <select id="posicao" name="id_posicao" class="form-control" required>
                    <option value="">Selecione uma posição</option>
                    <option value="1">Goleiro</option>
                    <option value="2">Zagueiro</option>
                    <option value="3">Lateral Direito</option>
                    <option value="4">Lateral Esquerdo</option>
                    <option value="5">Volante</option>
                    <option value="6">Meia</option>
                    <option value="7">Ponta Direita</option>
                    <option value="8">Ponta Esquerda</option>
                    <option value="9">Atacante</option>
                    <option value="10">Centroavante</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="clube" class="form-label">Clube</label>
                <select id="clube" name="id_clube" class="form-control" required>
                    <option value="1">Viseu United</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="equipa" class="form-label">Equipa</label>
                <select id="equipa" name="id_equipa" class="form-control">
                    <option value="">Nenhuma</option>
                    <option value="1">Profissional</option>
                    <option value="2">Em Formação</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Jogador</button>
        </form>
    </div>
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
