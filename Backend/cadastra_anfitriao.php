<?php
require_once(__DIR__ . "/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome  = $_POST["nome"];
    $cpf   = $_POST["cpf"];
    $senha = ($_POST["senha"]);

    $sql = "INSERT INTO administradores (nome, cpf, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("❌ Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param("sss", $nome, $cpf, $senha);

    if ($stmt->execute()) {
        echo " Cadastro realizado com sucesso! <a href='../Frontend/teladelogin'>Fazer login</a>";
    } else {
        echo " Erro ao cadastrar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
