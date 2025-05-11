<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php'); // Certifique-se de que o caminho está correto
include 'includes/header.php';

// Query para buscar os clubes existentes para preencher o campo id_clube no formulário
$sql = "SELECT id, nome FROM clubes";
try {
    $link = connect_db();
    $stmt = $link->prepare($sql);
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
                <label for="nome" class="form-label">Escalão da Equipa</label>
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
                    <?php foreach ($clubes as $clube): ?>
                        <option value="<?php echo $clube['id']; ?>"><?php echo htmlspecialchars($clube['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="nome_treinador" class="form-label">Nome do Treinador</label>
                <input type="text" id="nome_treinador" name="nome_treinador" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contacto_treinador" class="form-label">Contacto do Treinador</label>
                <input type="tel" id="contacto_treinador" name="contacto_treinador" class="form-control" pattern="[0-9]{9}" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Equipa</button>
        </form>
    </div>

    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>