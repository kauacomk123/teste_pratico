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
            <h2>Cadastro de categorias</h2>
            
            <!-- Mostrar mensagem se existir -->
            <?php if (isset($_GET['mensagem'])): ?>
                <div class="<?php echo $_GET['sucesso'] ? 'sucesso' : 'erro'; ?>">
                    <?php echo $_GET['mensagem']; ?>
                </div>
            <?php endif; ?>
            
            <form action="../backend/cadastrar_categoria.php" method="POST">
                <label>Nome da categoria</label>
                <input type="text" name="nome" required>
                <button type="submit">Cadastrar</button>
            </form>
            
            <!-- Lista de categorias cadastradas -->
            <div class="categoria-lista">
                <h3>Categorias Cadastradas:</h3>
                <?php
                include "../backend/conexao.php";
                $categorias = $conn->query("SELECT id, nome FROM categorias ORDER BY id DESC");
                
                if ($categorias->num_rows > 0) {
                    while($cat = $categorias->fetch_assoc()) {
                        echo "<div class='categoria-item'>";
                        echo $cat['nome'];
                        echo "</div>";
                    }
                } else {
                    echo "<p>Nenhuma categoria cadastrada.</p>";
                }
                $conn->close();
                ?>
            </div>
            
            <a href="dashborad_anfitriao.html" class="voltar">Voltar ao Dashboard</a>
        </div>
    </div>
</body>
</html>