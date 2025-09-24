<?php
include "conexao.php";

$sql = "SELECT id, nome, data_evento, localizacao, anfitriao FROM eventos ORDER BY data_evento ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='card'>
            <h3>" . htmlspecialchars($row['nome']) . "</h3>
            <p><strong>Data:</strong> " . htmlspecialchars($row['data_evento']) . "</p>
            <p><strong>Local:</strong> " . htmlspecialchars($row['localizacao']) . "</p>
            <p><strong>Anfitrião:</strong> " . htmlspecialchars($row['anfitriao']) . "</p>

            <button onclick=\"window.location.href='../backend/editar_evento.php?id=" . $row['id'] . "'\">Editar</button>
            <button onclick=\"excluirEvento(" . $row['id'] . ")\">Excluir</button>
        </div>
        ";
    }
} else {
    echo "<p>Nenhum evento cadastrado ainda.</p>";
}

$conn->close();
?>