<?php
include "conexao.php";

// Pegando dados do formulário
$evento        = $_POST['evento_id'];
$nome              = $_POST['nome'];
$preco             = $_POST['preco'];
$limite_maximo     = $_POST['limite_maximo'];
$categoria_id      = $_POST['categoria']; // Agora é categoria_id
$tipo_pagamento_id = $_POST['tipo_pagamento']; // <--- ESTA LINHA PEGA O DADO

// Upload da imagem
$diretorio = "../uploads/";
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0777, true);
}

$nome_arquivo = time() . "_" . basename($_FILES["imagem"]["name"]);
$caminho = $diretorio . $nome_arquivo;

if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
    // Inserindo no banco com os nomes corretos das colunas
    $sql = "INSERT INTO presentes (evento_id, nome, preco, limite_maximo, categoria_id, imagem, tipo_pagamento_id) 
            VALUES ('$evento', '$nome', '$preco', '$limite_maximo', '$categoria_id', '$nome_arquivo', '$tipo_pagamento_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Presente cadastrado com sucesso!";
        echo "<br><a href='painel_anfitriao.php'>Voltar</a>";
    } else {
        echo "Erro ao cadastrar presente: " . $conn->error;
    }
} else {
    echo "Erro no upload da imagem.";
}

$conn->close();
?>