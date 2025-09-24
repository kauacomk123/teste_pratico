<?php
include "conexao.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM eventos WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
} else {
    echo "ID inválido";
}

$conn->close();
?>