<?php
// Inicia a sessão
//session_start();
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrição de Jogador</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1>Inscrição de Jogador</h1>
            </div>
            <div class="card-body">
                <form action="processa_inscricao.php" method="POST">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Campo</th>
                                <th>Preencher</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Campo Nome -->
                            <tr>
                                <td><label for="nome" class="form-label">Nome</label></td>
                                <td>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do jogador" required>
                                </td>
                            </tr>
                            <!-- Campo Idade -->
                            <tr>
                                <td><label for="idade" class="form-label">Idade</label></td>
                                <td>
                                    <input type="number" class="form-control" id="idade" name="idade" placeholder="Digite a idade do jogador" min="1" max="100" required>
                                </td>
                            </tr>
                            <!-- Campo Posição -->
                            <tr>
                                <td><label for="posicao" class="form-label">Posição</label></td>
                                <td>
                                    <select class="form-select" id="posicao" name="posicao" required>
                                        <option value="" disabled selected>Selecione a posição</option>
                                        <option value="Guarda-Redes">Guarda-Redes</option>
                                        <option value="Defesa">Defesa</option>
                                        <option value="Médio">Médio</option>
                                        <option value="Avançado">Avançado</option>
                                    </select>
                                </td>
                            </tr>
                            <!-- Campo Contacto do Pai -->
                            <tr>
                                <td><label for="contacto_pai" class="form-label">Contacto do Responsável</label></td>
                                <td>
                                    <input type="tel" class="form-control" id="contacto_pai" name="contacto_pai" placeholder="Digite o contacto do pai" pattern="[0-9]{9}" required>
                                </td>
                            </tr>
                            <!-- Campo Contacto do Atleta -->
                            <tr>
                                <td><label for="contacto_atleta" class="form-label">Contacto do Atleta</label></td>
                                <td>
                                    <input type="tel" class="form-control" id="contacto_atleta" name="contacto_atleta" placeholder="Digite o contacto do atleta" pattern="[0-9]{9}" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Botão de Enviar -->
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success">Inscrever</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>