<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Frontend/css/stylelogin.css">
    <title>Cadastrar Evento</title>
</head>
<body>
     <div class="container">
    <div id="login-form">
    <h2>Cadastro de Eventos</h2>
    <form action="../backend/cadastra_evento.php" method="POST">
        <label>Nome do evento:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Data do evento:</label><br>
        <input type="date" name="data_evento" required><br><br>

        <label>Localização:</label><br>
        <input type="text" name="localizacao" required><br><br>

        <label>Anfitrião(ões):</label><br>
        <input type="text" name="anfitriao" required><br><br>

        <label>Senha do evento:</label><br>
        <input type="password" name="senha_evento" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>
     <a href="dashborad_anfitriao.html">Voltar ao Dashboard</a>
    </div>
    </div>
</body>
</html>