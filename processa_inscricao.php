<?php
session_start();

include 'config.php'; // Inclui a configuração do banco de dados

$link = connect_db();
// Verificar se a conexão com o banco de dados está disponível
if (!isset($link)) {
    die("Erro: Conexão com o banco de dados não encontrada.");
}

// Obter dados do formulário com validação básica
$nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;
$idade = isset($_POST['idade']) ? (int)$_POST['idade'] : null;
$posicao = isset($_POST['posicao']) ? trim($_POST['posicao']) : null;
$contacto_pai = isset($_POST['contacto_pai']) ? trim($_POST['contacto_pai']) : null;
$contacto_atleta = isset($_POST['contacto_atleta']) ? trim($_POST['contacto_atleta']) : null;

// Verificar se todos os campos obrigatórios foram preenchidos
if (!$nome || !$idade || !$posicao || !$contacto_pai || !$contacto_atleta) {
    echo "<script>alert('Por favor, preencha todos os campos obrigatórios.'); window.history.back();</script>";
    exit;
}

// Inserir dados na tabela usando a conexão do config.php
$sql = "INSERT INTO inscricoes (nome, idade, posicao, contacto_pai, contacto_atleta) VALUES (:nome, :idade, :posicao, :contacto_pai, :contacto_atleta)";
$stmt = $link->prepare($sql);

// Bind dos parâmetros
$stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
$stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
$stmt->bindParam(':posicao', $posicao, PDO::PARAM_STR);
$stmt->bindParam(':contacto_pai', $contacto_pai, PDO::PARAM_STR);
$stmt->bindParam(':contacto_atleta', $contacto_atleta, PDO::PARAM_STR);

// Executar a inserção
$stmt->execute();

echo "<script>alert('Inscrição realizada com sucesso!'); window.location.href='index.php';</script>";
?>
