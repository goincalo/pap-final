<?php
include 'config.php'; // Inclui a configuração do banco de dados

// Consulta para obter todas as inscrições
$sql = "SELECT * FROM inscricoes";
$stmt = $db->query($sql);

// Verificar se há resultados
$inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <?php if ($inscricoes): ?>
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
                        <th>Ações</th> <!-- Nova coluna para ações -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscricoes as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["id"]); ?></td>
                            <td><?php echo htmlspecialchars($row["nome"]); ?></td>
                            <td><?php echo htmlspecialchars($row["idade"]); ?></td>
                            <td><?php echo htmlspecialchars($row["posicao"]); ?></td>
                            <td><?php echo htmlspecialchars($row["contacto_pai"]); ?></td>
                            <td><?php echo htmlspecialchars($row["contacto_atleta"]); ?></td>
                            <td><?php echo htmlspecialchars($row["data_inscricao"]); ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm remover-inscricao" data-id="<?php echo $row['id']; ?>">Remover</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Nenhuma inscrição encontrada.</p>
        <?php endif; ?>
    </div>

    <script src="public/JS/jquery.js"></script>
    <script src="includes/datatables/datatables.js"></script>
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#inscricoesTable').DataTable({
                language: {
                    url: "includes/datatables/langconfig.json" // Configuração de idioma
                },
                responsive: true
            });

            // Função para remover inscrição
            $(document).on('click', '.remover-inscricao', function () {
                let id = $(this).data('id');

                if (confirm('Tem certeza que deseja remover esta inscrição?')) {
                    $.ajax({
                        url: 'remover_inscricao.php', // Arquivo para processar a remoção
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
