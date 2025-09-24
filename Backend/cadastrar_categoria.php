<?php
include "conexao.php";

// Pegando os dados do formulário
$nome_categoria = trim($_POST['nome']);

// Validar se o nome não está vazio
if (empty($nome_categoria)) {
    header("Location: ../frontend/cadastro_categoria.php?sucesso=0&mensagem=Por+favor+informe+o+nome+da+categoria");
    exit();
}

// Inserindo no banco (usando prepared statements para segurança)
$sql = "INSERT INTO categorias (nome) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nome_categoria);

if ($stmt->execute()) {
    header("Location: ../frontend/cadastro_categoria.php?sucesso=1&mensagem=Categoria+cadastrada+com+sucesso");
} else {
    header("Location: ../frontend/cadastro_categoria.php?sucesso=0&mensagem=Erro+ao+cadastrar+categoria");
}

$stmt->close();
$conn->close();
?>