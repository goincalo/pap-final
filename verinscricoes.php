<?php
require(__DIR__ . '/config.php');
include 'includes/header.php';

// Verifica se a sessão foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexão à base de dados
$link = connect_db();
$sql = "SELECT * FROM inscricoes";
$stmt = $link->query($sql);
$inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o usuário é administrador
$isAdmin = isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'administrador';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Inscrições</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="includes/datatables/datatables.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Lista de Inscrições</h1>

        <table id="inscricoesTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Idade</th>
                    <th>Posição</th>
                    <th>Contacto do Responsável</th>
                    <th>Contacto do Atleta</th>
                    <th>Data da Inscrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($inscricoes) > 0): ?>
                    <?php foreach ($inscricoes as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["id"]) ?></td>
                            <td><?= htmlspecialchars($row["nome"]) ?></td>
                            <td><?= htmlspecialchars($row["idade"]) ?></td>
                            <td><?= htmlspecialchars($row["posicao"]) ?></td>
                            <td><?= htmlspecialchars($row["contacto_pai"]) ?></td>
                            <td><?= htmlspecialchars($row["contacto_atleta"]) ?></td>
                            <td><?= htmlspecialchars($row["data_inscricao"]) ?></td>
                            <td>
                                <?php if ($isAdmin): ?>
                                    <button class="btn btn-danger btn-sm remover" data-id="<?= $row["id"] ?>">Remover</button>
                                <?php else: ?>
                                    <span class="text-muted">Sem ações</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Nenhuma inscrição encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="public/JS/jquery.js"></script>
    <script src="includes/datatables/datatables.js"></script>
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#inscricoesTable').DataTable({
                language: {
                    url: "includes/datatables/langconfig.json"
                },
                responsive: true
            });

            // Ação de remover
            $(document).on('click', '.remover', function () {
                let id = $(this).data('id');

                if (confirm('Tem certeza que deseja remover esta inscrição?')) {
                    $.ajax({
                        url: 'remover_inscricao.php',
                        method: 'POST',
                        data: { id: id },
                        success: function (response) {
                            let result = JSON.parse(response);
                            if (result.success) {
                                alert('Inscrição removida com sucesso!');
                                location.reload();
                            } else {
                                alert('Erro ao remover inscrição: ' + result.message);
                            }
                        },
                        error: function () {
                            alert('Erro ao processar a solicitação.');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
