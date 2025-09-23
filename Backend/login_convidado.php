<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>pagina inicial</title>
</head>
<body>
   
    <form method="post" action="../Backend/validar_convidado.php">
        <input type="text" name="cpf" id="cpf" placeholder="Digite seu CPF" required>
        <input type="password" name="senha_evento" id="senha_evento" placeholder="Digte a senha do evento" required>

        <button type="submit">Entrar</button>
    </form>
     <a href="../Frontend/teladelogin.html">Voltar</a>
</body>
</html>