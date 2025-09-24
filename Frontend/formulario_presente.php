<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Frontend/css/stylelogin.css">
    <title>Cadastrar Presente</title>
</head>
<body>
    <div class="container">
    <div id="login-form">
    <h2>Cadastro de Presente</h2>
    <form action="../backend/cadastrar_presente.php" method="POST" enctype="multipart/form-data">
        <label>Evento ID:</label><br>
        <input type="number" name="evento_id" placeholder="ID do evento" required><br><br>

        <label>Nome do presente:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Preço:</label><br>
        <input type="number" step="0.01" name="preco" required><br><br>

        <label>Limite máximo:</label><br>
        <input type="number" name="limite_maximo" required><br><br>

        <label>Categoria:</label><br>
        <select name="categoria" required>
            <option value="">Selecione a categoria</option>
            <?php
            // Conectar ao banco e buscar categorias
            include "../backend/conexao.php";
            $categorias = $conn->query("SELECT id, nome FROM categorias ORDER BY nome");
            while($cat = $categorias->fetch_assoc()) {
                echo "<option value='{$cat['id']}'>{$cat['nome']}</option>";
            }
            $conn->close();
            ?>
        </select><br><br>

        <label>Imagem do presente:</label><br>
        <input type="file" name="imagem" accept="image/*" required><br><br>

        <label>Tipo de pagamento:</label><br>
        <select name="tipo_pagamento" required>
            <option value="">Selecione a forma de pagamento</option>
            <?php
            // Conectar ao banco e buscar formas de pagamento
            include "../backend/conexao.php";
            $pagamentos = $conn->query("SELECT id, nome FROM formas_pagamento ORDER BY nome");
            while($pag = $pagamentos->fetch_assoc()) {
                echo "<option value='{$pag['id']}'>{$pag['nome']}</option>";
            }
            $conn->close();
            ?>
        </select><br><br>

        <button type="submit">Cadastrar Presente</button>
    </form>

    <br>
    <a href="dashborad_anfitriao.html">Voltar ao Dashboard</a>
    </div>
  </div>
</body>
</html>