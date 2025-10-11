<?php
include "conexao.php";
?>

<div id="criar-presente" class="tab-content">
    <h2>Cadastro de Presente</h2>
    <form class="form_cadastro" action="../backend/cadastrar_presente.php" method="POST" enctype="multipart/form-data">

        <label>Evento:</label><br>
        <input type="number" name="evento_id" placeholder="ID do evento" required><br><br>

        <label>Nome do presente:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Preço:</label><br>
        <input type="number" step="0.01" name="preco" required><br><br>

        <label>Limite máximo:</label><br>
        <input type="number" name="limite_maximo" required><br><br>

        <label>Categoria:</label><br>
        <select name="categoria" required>
            <?php
            $sql = "SELECT id, nome FROM categorias ORDER BY nome";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                }
            }
            ?>
        </select><br><br>

        <label>Imagem do presente:</label><br>
        <input type="file" name="imagem" accept="image/*" required><br><br>

        <label>Tipo de pagamento:</label><br>
        <select name="tipo_pagamento" required>
            <?php
            $sql_pag = "SELECT id, nome FROM formas_pagamento ORDER BY nome";
            $result = $conn->query($sql_pag);
            if ($result->num_rows > 0) {
                while ($row = $result_pag->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                }
            }
            ?>
        </select><br><br>

        <button type="submit">Cadastrar Presente</button>
    </form>
</div>
