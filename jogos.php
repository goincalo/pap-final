<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');
include 'includes/header.php';

// Inicia a sessão
//session_start();

// Query para buscar todos os dados da tabela jogos_eventos
$sql = "SELECT id, titulo, descricao, caminho_imagem, created_at, updated_at FROM jogos_eventos";

try {
    // Prepare e execute a consulta usando PDO
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém os dados como array associativo
} catch (Exception $e) {
    echo "Erro ao executar a consulta: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Jogos/Eventos</title>
    <link rel="stylesheet" href="includes/datatables/datatables.css">
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Lista de Eventos</h1>
        <a href="adicionar_jogo.php" class="btn btn-success btn-sm" style="margin-bottom:20px">Adicionar Evento</a>
        <hr>
        <table id="jogosTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Cartaz</th>
                    <th>Data de Criação</th>
                    <th>Atualização</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <?php
            // Verifica se o usuário é administrador
            $isAdmin = isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'administrador';
            ?>
            <tbody>
                <?php
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        echo "<tr>
                    <td>{$row['titulo']}</td>
                    <td>{$row['descricao']}</td>
                    <td><img src='public/Imagens/{$row['caminho_imagem']}' alt='Cartaz' style='max-width: 100px; height: auto;'></td>
                    <td>{$row['created_at']}</td>
                    <td>{$row['updated_at']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm editar' data-id='{$row['id']}'>Editar</button>
                        <button class='btn btn-danger btn-sm remover' data-id='{$row['id']}'>Remover</button>
                    </td>
                  </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Sem dados para exibir</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="editId" name="id">
                            <div class="mb-3">
                                <label for="editTitulo" class="form-label">Título</label>
                                <input type="text" id="editTitulo" name="titulo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDescricao" class="form-label">Descrição</label>
                                <textarea id="editDescricao" name="descricao" class="form-control" rows="4"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editCaminhoImagem" class="form-label">Cartaz do Evento (Nova Imagem -
                                    Opcional)</label>
                                <input type="file" id="editCaminhoImagem" name="caminho_imagem" class="form-control"
                                    accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="public/JS/jquery.js"></script>
    <script src="includes/datatables/datatables.js"></script>
    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            $('#jogosTable').DataTable({
                language: {
                    url: "includes/datatables/langconfig.json"
                },
                responsive: true
            });
        });

        $(document).on('click', '.remover', function () {
            let id = $(this).data('id');

            if (confirm('Tem certeza que deseja remover este evento?')) {
                $.ajax({
                    url: 'apagar_jogo.php',
                    method: 'POST',
                    data: { id: id },
                    success: function (response) {
                        let result = JSON.parse(response);
                        if (result.success) {
                            alert('Evento removido com sucesso!');
                            location.reload(); // Atualiza a tabela
                        } else {
                            alert('Erro ao remover evento: ' + result.message);
                        }
                    }
                });
            }
        });

        $(document).on('click', '.editar', function () {
            let id = $(this).data('id');

            // Enviar AJAX para buscar os dados do evento
            $.ajax({
                url: 'get_jogo.php',
                method: 'GET',
                data: { id: id },
                success: function (response) {
                    let jogo = JSON.parse(response);

                    if (jogo.success === false) {
                        alert(jogo.message);
                        return;
                    }

                    // Preenche os campos na modal com os dados do evento
                    $('#editId').val(jogo.id);
                    $('#editTitulo').val(jogo.titulo);
                    $('#editDescricao').val(jogo.descricao);
                    $('#editCaminhoImagem').val(''); // Limpa o campo de upload de imagem

                    // Abre a modal de edição
                    $('#editModal').modal('show');
                },
                error: function () {
                    alert('Erro ao buscar os dados do evento.');
                }
            });
        });

        $('#editForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: 'editar_jogo.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    let result = JSON.parse(response);
                    if (result.success) {
                        alert('Evento atualizado com sucesso!');
                        location.reload(); // Atualiza a tabela
                    } else {
                        alert('Erro ao atualizar evento: ' + result.message);
                    }
                },
                error: function () {
                    alert('Erro ao enviar os dados para o servidor.');
                }
            });
        });
    </script>
</body>

</html>