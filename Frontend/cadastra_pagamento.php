<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylelogin.css">
    <title>Cadastro de Categorias</title>
</head>
<body>
    <div class="container">
        <div id="login-form">
            <h2>cadastro de pagamento</h2>
            
            <!-- Mostrar mensagem se existir -->
            <?php if (isset($_GET['mensagem'])): ?>
                <div class="<?php echo $_GET['sucesso'] ? 'sucesso' : 'erro'; ?>">
                    <?php echo $_GET['mensagem']; ?>
                </div>
            <?php endif; ?>
            
            <form action="../backend/cadastrar_pagamento.php" method="POST">
                <label>Nome da forma de pagamento</label>
                <input type="text" name="nome" required>
                <button type="submit">Cadastrar</button>
            </form>
            
            <!-- Lista de categorias cadastradas -->
            <div class="categoria-lista">
                <h3>formas de pagamento cadastrada:</h3>
                <?php
                include "../backend/conexao.php";
                $formas_pagamento = $conn->query("SELECT id, nome FROM formas_pagamento ORDER BY id DESC");
                
                if ($formas_pagamento->num_rows > 0) {
                    while($cat = $formas_pagamento->fetch_assoc()) {
                        echo "<div class='categoria-item'>";
                        echo $cat['nome'];
                        echo "</div>";
                    }
                } else {
                    echo "<p>Nenhuma forma de pagamento cadastrada.</p>";
                }
                $conn->close();
                ?>
            </div>
            
            <a href="dashborad_anfitriao.html" class="voltar">Voltar ao Dashboard</a>
        </div>
    </div>
</body>
</html>