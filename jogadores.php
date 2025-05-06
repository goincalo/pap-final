<?php
// Conexão com a base de dados
require(__DIR__ . '/config.php');
include 'includes/header.php';

// Query com INNER JOIN para buscar os dados dos jogadores e seus clubes
$sql = "SELECT
            jogadores.id,
            jogadores.first_name,
            jogadores.last_name,
            jogadores.idade,
            jogadores.genero,
            jogadores.id_posicao,
            jogadores.id_clube,
            jogadores.id_equipa,
            posicoes.nome AS posicao_nome,
            clubes.nome AS clube_nome,
            equipas.nome AS equipa_nome,
            jogadores.created_at
        FROM jogadores
        INNER JOIN posicoes ON jogadores.id_posicao = posicoes.id
        INNER JOIN clubes ON jogadores.id_clube = clubes.id
        LEFT JOIN equipas ON jogadores.id_equipa = equipas.id
        WHERE clubes.nome = 'Viseu United'"; // Filtro para clubes com nome

try {
    // Prepare e execute a consulta usando PDO
    $link = connect_db('');
    $stmt = $link->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém os dados como array associativo
} catch (Exception $e) {
    echo "Erro ao executar a consulta: " . $e->getMessage();
    exit();
}
?>

<link rel="stylesheet" href="includes/datatables/datatables.css">
<link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">

<div class="container mt-5">
    <h1 class="text-center mb-4">Lista de Jogadores</h1>

    <?php
    // Verifica se o usuário é administrador
    $isAdmin = isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'administrador';
    if ($isAdmin): ?>
        <a href="adicionar_jogador.php" class="btn btn-success btn-sm" style="margin-bottom:20px">Adicionar Jogador</a>
    <?php endif; ?>

    <hr>
    <table id="jogadoresTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Primeiro Nome</th>
                <th>Último Nome</th>
                <th>Idade</th>
                <th>Género</th>
                <th>Posição</th>
                <th>Clube</th>
                <th>Equipa</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($result) > 0) {
                foreach ($result as $row) {
                    echo "<tr>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['idade']}</td>
                                <td>{$row['genero']}</td>
                                <td>{$row['posicao_nome']}</td>
                                <td>{$row['clube_nome']}</td>
                                <td>{$row['equipa_nome']}</td>
                                <td>{$row['created_at']}</td>
                                <td>";
                    // Exibe os botões apenas se for administrador
                    if ($isAdmin) {
                        echo "<button class='btn btn-warning btn-sm editar' data-id='{$row['id']}'>Editar</button>
                              <button class='btn btn-danger btn-sm remover' data-id='{$row['id']}'>Remover</button>";
                    }
                    echo "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>Sem dados para exibir</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modal de edição -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Jogador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editFirstName" class="form-label">Primeiro Nome</label>
                            <input type="text" id="editFirstName" name="first_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Último Nome</label>
                            <input type="text" id="editLastName" name="last_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editIdade" class="form-label">Idade</label>
                            <input type="number" id="editIdade" name="idade" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGenero" class="form-label">Género</label>
                            <input type="text" id="editGenero" name="genero" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPosicao" class="form-label">Posição</label>
                            <select id="editPosicao" name="id_posicao" class="form-control" required>
                                <option value="">Selecione uma posição</option>
                                <option value="1">Guarda-Redes</option>
                                <option value="2">Ponta de Lança</option>
                                <option value="3">Lateral Direito</option>
                                <option value="4">Lateral Esquerdo</option>
                                <option value="5">Médio Defensivo</option>
                                <option value="6">Meio Campo</option>
                                <option value="7">Extremo Direito</option>
                                <option value="8">Extremo Esquerdo</option>
                                <option value="9">Atacante</option>
                                <option value="10">Defesa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editClube" class="form-label">Clube</label>
                            <select id="editClube" name="id_clube" class="form-control" required>
                                <option value="1">Viseu United</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editEquipa" class="form-label">Equipa</label>
                            <select id="editEquipa" name="id_equipa" class="form-control">
                                <option value="1">Profissional</option>
                                <option value="2">Em Formação</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
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
        $('#jogadoresTable').DataTable({
            language: {
                url: "includes/datatables/langconfig.json"
            },
            responsive: true
        });
    });

    $(document).on('click', '.remover', function () {
        let id = $(this).data('id');

        if (confirm('Tem certeza que deseja remover este item?')) {
            $.ajax({
                url: 'apagar_jogador.php',
                method: 'POST',
                data: { id: id },
                success: function (response) {
                    let result = JSON.parse(response);
                    if (result.success) {
                        alert('Item removido com sucesso!');
                        location.reload(); // Atualiza a tabela
                    } else {
                        alert('Erro ao remover: ' + result.message);
                    }
                }
            });
        }
    });

    $(document).on('click', '.editar', function () {
        let id = $(this).data('id');

        // Busca os dados via AJAX
        $.ajax({
            url: 'get_jogador.php',
            method: 'GET',
            data: { id: id },
            success: function (response) {
                let jogador = JSON.parse(response);

                // Preencha os campos no formulário
                $('#editId').val(jogador.id);
                $('#editFirstName').val(jogador.first_name);
                $('#editLastName').val(jogador.last_name);
                $('#editIdade').val(jogador.idade);
                $('#editGenero').val(jogador.genero);

                // Mostre a modal após preencher os campos
                $('#editModal').modal('show');
            },
            error: function () {
                alert('Erro ao buscar dados do jogador.');
            }
        });
    });

    $('#editForm').submit(function (e) {
        e.preventDefault();

        console.log($(this).serialize()); // Exibe os dados que estão sendo enviados

        $.ajax({
            url: 'editar_jogador.php',
            method: 'POST',
            data: $(this).serialize(), // Envia todos os dados do formulário
            success: function (response) {
                let result = JSON.parse(response);
                if (result.success) {
                    alert('Dados atualizados com sucesso!');
                    location.reload(); // Atualiza a tabela
                } else {
                    alert('Erro ao atualizar: ' + result.message);
                }
            },
            error: function () {
                alert('Erro ao enviar dados para o servidor.');
            }
        });
    });
</script>