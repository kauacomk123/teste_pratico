<?php
include "conexao.php";

// Pegando os dados do formulário
$nome_evento  = $_POST['nome'];
$data_evento  = $_POST['data_evento'];
$localizacao  = $_POST['localizacao'];
$anfitriao    = $_POST['anfitriao'];
$senha_evento = ($_POST['senha_evento']);

// Inserindo no banco
$sql = "INSERT INTO eventos (nome, data_evento, localizacao, anfitriao, senha_evento)
        VALUES ('$nome_evento', '$data_evento', '$localizacao', '$anfitriao', '$senha_evento')";

if ($conn->query($sql) === TRUE) {
    echo "Evento cadastrado com sucesso!";
    echo "<br><a href='../frontend/formulario_evento.php'>Voltar</a>";
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>