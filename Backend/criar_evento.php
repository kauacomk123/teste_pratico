<?php
session_start();
require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_evento = $_POST["nome"];
    $data_evento = $_POST["data_evento"];
    $localizacao = $_POST["localizacao"];
    $anfitrioes = $_POST["anfitriao"];
    $senha_evento = $_POST["senha_evento"];

    $sql = "INSERT INTO eventos (nome, data_evento, localizacao, anfitriao, senha_evento) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nome_evento, $data_evento, $localizacao, $anfitrioes, $senha_evento);
    
    if ($stmt->execute()) {
        echo "sucesso";
    } else {
        echo "erro: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>