<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');

// Inclui o header
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clube Manager</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="public/css/index.css"> <!-- CSS personalizado -->
</head>
<body>
    <?php
    // Query para buscar os 3 eventos mais recentes
    $sql = "SELECT titulo, descricao, caminho_imagem FROM jogos_eventos ORDER BY created_at DESC LIMIT 3";
    try {
        // Preparar e executar a consulta
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erro ao buscar eventos: " . $e->getMessage() . "</div>";
        $eventos = [];
    }
    ?>
    <div class="content">
        <h1>Bem-vindo ao Clube Manager</h1>
       
    </div>

    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($eventos as $index => $evento): ?>
                <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="<?= $index ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>" aria-current="true"
                    aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($eventos as $index => $evento): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="public/Imagens/<?= htmlspecialchars($evento['caminho_imagem']) ?>"
                        class="d-block w-100 carousel-image" alt="Imagem do <?= htmlspecialchars($evento['titulo']) ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?= htmlspecialchars($evento['titulo']) ?></h5>
                        <p><?= htmlspecialchars($evento['descricao']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>
    <style>
        .carousel-image {
            max-height: 400px;
            object-fit: cover;
        }
    </style>
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
</body>
</html>

<?php
// Inclui o footer
include 'includes/footer.php';
?>
