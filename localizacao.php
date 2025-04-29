<?php
// Inicia a sessão
include 'includes/header.php';
// Obtém o link aleatório da URL ou usa um link padrão
$link = isset($_GET['link']) ? htmlspecialchars($_GET['link']) : 'https://www.google.com/maps/place/Campo+de+Futebol+de+S%C3%A3o+Jo%C3%A3o+de+Lourosa/@40.6107937,-7.910399,16z/data=!4m14!1m7!3m6!1s0xd23374a10013b71:0x268051528fb28ea3!2sPastelaria+Trigueirinha+S%C3%A3o+Jo%C3%A3o,+Lda!8m2!3d40.6120673!4d-7.9064296!16s%2Fg%2F11h_9v7z64!3m5!1s0xd2336d2a9aaaaab:0xb60e9177299ade27!8m2!3d40.6078193!4d-7.9049901!16s%2Fg%2F11kj901qhq?entry=ttu&g_ep=EgoyMDI1MDQyMy4wIKXMDSoASAFQAw%3D%3D';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Localização</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1>Localização</h1>
            </div>
            <div class="card-body">
                <p class="text-center mt-3">
                    Clique no botão abaixo para abrir a localização no Google Maps:
                </p>
                <div class="text-center">
                    <a href="<?php echo $link; ?>" target="_blank" class="btn btn-primary">Abrir Localização</a>
                </div>
            </div>
           
        </div>
    </div>

    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>