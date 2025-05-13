<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');
include 'includes/header.php';

$sql = "SELECT 
    e.id AS equipa_id,
    e.nome AS equipa_nome,
    c.id AS clube_id,
    c.nome AS clube_nome
FROM equipas e
INNER JOIN clubes c ON e.id_clube = c.id";

$link = connect_db();
$stmt = $link->prepare($sql);
$stmt->execute();
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organizar os dados:
$equipas = [];

foreach ($dados as $row) {
    // Preencher equipas
    $equipas[] = [
        'id' => $row['equipa_id'],
        'nome' => $row['equipa_nome'],
        'clube_id' => $row['clube_id']
    ];
}

// Código para processar a adição do jogador via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['idade'], $_POST['genero'], $_POST['posicao'], $_POST['id_equipa'])) {
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $idade = $_POST['idade'];
        $genero = htmlspecialchars($_POST['genero']);
        $posicao = $_POST['posicao'];
        $id_equipa = $_POST['id_equipa'];

        // Validações adicionais
        if (!is_numeric($idade) || !is_numeric($id_equipa)) {
            echo "<div class='alert alert-danger'>Parâmetros inválidos</div>";
        } else {
            $link = connect_db();
            // Query para adicionar o jogador
            $sql = "INSERT INTO jogadores (first_name, last_name, idade, genero, posicao, id_equipa, created_at) 
                    VALUES (:first_name, :last_name, :idade, :genero, :posicao, :id_equipa, NOW())";

            try {
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
                $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
                $stmt->bindParam(':posicao', $posicao, PDO::PARAM_STR);
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
                <select id="posicao" name="posicao" class="form-control" required>
                    <option value="">Selecione</option>
                    <option value="Guarda-Redes">Guarda-Redes</option>
                    <option value="Ponta de Lança">Ponta de Lança</option>
                    <option value="Lateral Direito">Lateral Direito</option>
                    <option value="Lateral Esquerdo">Lateral Esquerdo</option>
                    <option value="Médio Defensivo">Médio Defensivo</option>
                    <option value="Meio Campo">Meio Campo</option>
                    <option value="Extremo Direito">Extremo Direito</option>
                    <option value="Extremo Esquerdo">Extremo Esquerdo</option>
                    <option value="Atacante">Atacante</option>
                    <option value="Defesa">Defesa</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="equipa" class="form-label">Equipa</label>
                <select id="equipa" name="id_equipa" class="form-control" required>
                    <option value="">Selecione uma equipa</option>
                    <?php foreach ($equipas as $equipa): ?>
                        <option value="<?= $equipa['id'] ?>">
                            <?= htmlspecialchars($equipa['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Jogador</button>
        </form>
    </div>
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>