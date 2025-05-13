<?php

// Inclui a conexão com o banco de dados
require(__DIR__ . '/config.php');

// Inclui o cabeçalho
include 'includes/header.php';

// Função para obter o cargo do usuário
function cargoAtual() {
    return isset($_SESSION['cargo']) ? strtolower(trim($_SESSION['cargo'])) : null;
}

// Query com INNER JOIN para buscar dados das equipas e clubes
$sql = "SELECT 
            equipas.id,
            equipas.nome AS equipa_nome,
            equipas.genero,
            equipas.tipo,
            IFNULL(equipas.sub_nome, 'N/A') AS sub_nome,
            equipas.created_at,
            clubes.nome AS clube_nome
        FROM equipas
        INNER JOIN clubes ON equipas.id_clube = clubes.id";

try {
    $link = connect_db();
    $stmt = $link->prepare($sql); // Corrigido 'prepare' em vez de 'prepar'
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erro ao executar a consulta: " . $e->getMessage();
    exit();
}
?>

<link rel="stylesheet" href="includes/datatables/datatables.css">
<link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">

<div class="container mt-5">
    <h1 class="text-center mb-4">Lista de Equipas</h1>

    <?php
    // Verifica se o usuário é administrador
    $isAdmin = isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'administrador';
    if ($isAdmin): ?>
        <a href="adicionar_equipa.php" class="btn btn-success btn-sm" style="margin-bottom:20px">Adicionar Equipa</a>
    <?php endif; ?>

    <hr>
    <table id="equipasTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Escalão da Equipa</th>
                <th>Género</th>
                <th>Tipo</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if (count($result) > 0) {
                foreach ($result as $row) {
                    echo "<tr>
                            <td>{$row['equipa_nome']}</td>
                            <td>{$row['genero']}</td>
                            <td>{$row['tipo']}</td>
                            <td>{$row['created_at']}</td>
                            <td>";

                            if ($isAdmin) {
                               echo "<button class='btn btn-warning btn-sm editar' data-id='{$row['id']}'>Editar</button>
                                <button class='btn btn-danger btn-sm remover' data-id='{$row['id']}'>Remover</button>";
                            }
                            echo "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Sem dados para exibir</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modal de edição -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Equipa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editNome" class="form-label">Escalão</label>
                            <input type="text" id="editNome" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGenero" class="form-label">Género</label>
                             <select id="genero" name="genero" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editTipo" class="form-label">Tipo</label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="Profissional">Profissional</option>
                                <option value="Em Formação">Em Formação</option>
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
    $('#equipasTable').DataTable({
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
            url: 'apagar_equipa.php',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                let result = JSON.parse(response);
                if (result.success) {
                    alert('Item removido com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao remover: ' + result.message);
                }
            }
        });
    }
});

$(document).on('click', '.editar', function () {
    let id = $(this).data('id');

    $.ajax({
        url: 'editar_equipa.php',
        method: 'GET',
        data: { id: id },
        success: function (response) {
            let equipa = JSON.parse(response);

            $('#editId').val(equipa.id);
            $('#editNome').val(equipa.nome);
            $('#editGenero').val(equipa.genero);
            $('#editTipo').val(equipa.tipo);

            $('#editModal').modal('show');
        },
        error: function () {
            alert('Erro ao buscar dados da equipa.');
        }
    });
});

$('#editForm').submit(function (e) {
    e.preventDefault();

    $.ajax({
        url: 'editar_equipa.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            let result = JSON.parse(response);
            if (result.success) {
                alert('Dados atualizados com sucesso!');
                location.reload();
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