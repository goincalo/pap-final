<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');
include 'includes/header.php';
// Query para buscar os clubes existentes para preencher o campo id_clube no formulário
$sql = "SELECT id, nome FROM clubes";
try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $clubes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erro ao buscar clubes: " . $e->getMessage();
    exit();
}
?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success text-center">
        Equipa adicionada com sucesso!
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Equipa</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Adicionar Nova Equipa</h1>
        <form action="salvar_equipa.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Equipa</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Gênero</label>
                <select id="genero" name="genero" class="form-control" required>
                    <option value="">Selecione</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="">Selecione</option>
                    <option value="Profissional">Profissional</option>
                    <option value="Em Formação">Em Formação</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_clube" class="form-label">Clube</label>
                <select id="id_clube" name="id_clube" class="form-control" required>
                    <option value="">Selecione um clube</option>
                    <option value="Viseu United">Viseu United</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Equipa</button>
        </form>
    </div>

    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
