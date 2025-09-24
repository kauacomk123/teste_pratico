<?php
include "../backend/conexao.php";

if (!isset($_GET['id'])) {
    die("ID do evento não informado!");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM eventos WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Evento não encontrado!");
}

$evento = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../frontend/css/stylelogin.css">
    <title>Editar Evento</title>
</head>
<body>
    <div class="container">
        <div id="login-form">
    <h2>Editar Evento</h2>
    <form action="../backend/salvar_edicao_evento.php" method="POST">
        <input type="hidden" name="id" value="<?= $evento['id'] ?>">

        <label>Nome do evento:</label><br>
        <input type="text" name="nome" value="<?= $evento['nome'] ?>" required><br><br>

        <label>Data do evento:</label><br>
        <input type="date" name="data_evento" value="<?= $evento['data_evento'] ?>" required><br><br>

        <label>Localização:</label><br>
        <input type="text" name="localizacao" value="<?= $evento['localizacao'] ?>" required><br><br>

        <label>Anfitrião(ões):</label><br>
        <input type="text" name="anfitriao" value="<?= $evento['anfitriao'] ?>" required><br><br>

        <label>Senha do evento:</label><br>
        <input type="password" name="senha_evento" value="<?= $evento['senha_evento'] ?>" required><br><br>

        <button type="submit">Salvar Alterações</button>
        <a href="painel_anfitriao.php">voltar</a>
    </form>
    </div>
</div>
</body>
</html>