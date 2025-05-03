<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado e define a variável de administrador
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']; // Supondo que os dados do usuário estejam armazenados na sessão
    if (isset($user['role']) && $user['role'] === 'admin') { // Verifica se o usuário é administrador
        $_SESSION['is_admin'] = true;
    } else {
        $_SESSION['is_admin'] = false;
    }
} else {
    $_SESSION['is_admin'] = false; // Define como não administrador se o usuário não estiver logado
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Futebol</title>
    <link rel="stylesheet" href="public/css/header.css"> <!-- Caminho atualizado para o CSS -->
    <!-- Link para o Bootstrap CSS (necessário para a dropdown) -->
    <link href="/public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <style>
        /* Estilo para o dropdown */
        .dropdown-menu {
            background-color: #343a40;
            /* Fundo escuro */
            border: none;
        }

        .dropdown-menu .dropdown-item {
            color: #ffffff;
            /* Texto branco */
            transition: background-color 0.3s ease;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #495057;
            /* Fundo cinza ao passar o mouse */
        }

        .dropdown-menu .dropdown-item.text-danger {
            color: #dc3545;
            /* Texto vermelho para "Terminar Sessão" */
        }

        .dropdown-menu .dropdown-item.text-danger:hover {
            background-color: #bd2130;
            /* Fundo vermelho escuro ao passar o mouse */
        }

        /* Estilo para os botões do header */
        .nav-button {
            display: inline-block;
            background-color: #007bff;
            /* Azul */
            color: #fff;
            /* Texto branco */
            padding: 10px 20px;
            /* Espaçamento interno */
            text-decoration: none;
            /* Remove o sublinhado */
            border-radius: 5px;
            /* Bordas arredondadas */
            font-size: 16px;
            /* Tamanho do texto */
            width: 120px;
            /* Largura fixa */
            text-align: center;
            /* Centraliza o texto */
            transition: background-color 0.3s ease;
            /* Efeito de transição */
            margin: 5px;
            /* Espaçamento entre os botões */
        }

        /* Efeito hover nos botões */
        .nav-button:hover {
            background-color: #0056b3;
            /* Azul mais escuro */
        }

        /* Alinhamento dos botões */
        .nav-menu {
            display: flex;
            justify-content: center;
            /* Centraliza os botões horizontalmente */
            align-items: center;
            /* Alinha os botões verticalmente */
            list-style: none;
            /* Remove os marcadores da lista */
            padding: 0;
            margin: 0;
        }

        /* Estilo para os botões de Login e Registo */
        .nav-button.login-button {
            background-color: #ff7043;
            /* Vermelho claro meio para o laranja */
            color: #fff;
            /* Texto branco */
            padding: 10px 20px;
            /* Espaçamento interno */
            text-decoration: none;
            /* Remove o sublinhado */
            border-radius: 5px;
            /* Bordas arredondadas */
            font-size: 16px;
            /* Tamanho do texto */
            width: 120px;
            /* Largura fixa */
            text-align: center;
            /* Centraliza o texto */
            transition: background-color 0.3s ease;
            /* Efeito de transição */
            margin: 5px;
            /* Espaçamento entre os botões */
        }

        /* Efeito hover nos botões de Login e Registo */
        .nav-button.login-button:hover {
            background-color: #e64a19;
            /* Vermelho mais escuro ao passar o mouse */
        }

        /* Estilo para o botão Inscrever-se */
        .nav-button.inscrever-button {
            background-color: #ffc107;
            /* Amarelo */
            color: #000;
            /* Texto preto */
            padding: 10px 20px;
            /* Espaçamento interno */
            text-decoration: none;
            /* Remove o sublinhado */
            border-radius: 5px;
            /* Bordas arredondadas */
            font-size: 16px;
            /* Tamanho do texto */
            width: 120px;
            /* Largura fixa */
            text-align: center;
            /* Centraliza o texto */
            transition: background-color 0.3s ease;
            /* Efeito de transição */
            margin: 5px;
            /* Espaçamento entre os botões */
            height: 40px;
            /* Altura fixa para igualar aos outros botões */
            line-height: 20px;
            /* Centraliza o texto verticalmente */
        }

        /* Efeito hover no botão Inscrever-se */
        .nav-button.inscrever-button:hover {
            background-color: #e0a800;
            /* Amarelo mais escuro ao passar o mouse */
        }
    </style>
    <header>
        <h1>Gestão de Futebol</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-button">Início</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li>
                        <a class="nav-button" href="#" id="equipasDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Equipas</a>
                        <ul class="dropdown-menu" aria-labelledby="equipasDropdown">
                            <li><a href="equipas.php" class="btn btn-primary w-100 mb-2">Ver Equipas</a></li>
                            <li><a href="contactos_treinadores.php" class="btn btn-secondary w-100">Contactos dos Treinadores</a></li>
                        </ul>
                    </li>
                    <li><a href="jogadores.php" class="nav-button">Jogadores</a></li>
                <?php endif; ?>
                <li><a href="jogos.php" class="nav-button">Eventos</a></li>
                <li>
                    <a href="inscrever.php" class="nav-button inscrever-button">Inscreve-te</a>
                </li>
                <?php if (!isset($_SESSION['username'])): ?>
                    <!-- Botões de Login e Registo para utilizadores não logados -->
                    <li><a href="login.php" class="nav-button login-button">Login</a></li>
                    <li><a href="registo.php" class="nav-button login-button">Registar</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Dropdown para o usuário logado -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                            <?php endif; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="btn btn-danger" href="logout.php">Terminar Sessão</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <!-- Link para o Bootstrap JS -->
    <script src="/public/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>