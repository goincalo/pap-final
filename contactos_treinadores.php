<?php
include 'config.php'; // Inclui a conexão ao banco de dados
include 'includes/header.php';
?>

<!-- Bootstrap CSS -->
<link href="public/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables CSS -->
<link rel="stylesheet" href="includes/datatables/datatables.css">

<div class="container mt-4">
    <h2 class="text-center mb-4">Contactos dos Treinadores</h2>
    <table id="treinadoresTable" class="table table-striped table-hover table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Nome</th>
                <th>Contacto</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Treinador 1</td>
                <td>+351 912345678</td>
                <td>treinador1@email.com</td>
            </tr>
            <tr>
                <td>Treinador 2</td>
                <td>+351 987654321</td>
                <td>treinador2@email.com</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- jQuery e DataTables JS -->
<script src="public/JS/jquery.js"></script>
<script src="includes/datatables/datatables.js"></script>
<script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
    $(function () {
        $('#treinadoresTable').DataTable({
            language: {
                url: "includes/datatables/langconfig.json"
            },
            responsive: true
        });
    });
</script>