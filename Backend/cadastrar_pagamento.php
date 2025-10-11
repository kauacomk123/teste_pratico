<?php
include "conexao.php";

// Pegando os dados do formulário
$nome = trim($_POST['nome']);

// Validar se o nome não está vazio
if (empty($nome)) {
    header("Location: ../frontend/cadastra_pagamento.php?sucesso=0&mensagem=Por+favor+informe+o+nome+da+forma+de+pagamento");
    exit();
}

// Inserindo no banco (usando prepared statements para segurança)
$sql = "INSERT INTO formas_pagamento (nome) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nome);

if ($stmt->execute()) {
    header("Location: ../frontend/cadastra_pagamento.php?sucesso=1&mensagem=forma+de+pagamento+cadastrada+com+sucesso");
} else {
    header("Location: ../frontend/cadastra_pagamento.php?sucesso=0&mensagem=Erro+ao+cadastrar+forma+de+pagamento");
}

$stmt->close();
$conn->close();
?>