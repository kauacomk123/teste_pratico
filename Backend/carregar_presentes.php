<?php
include "conexao.php";

// A consulta SQL foi modificada para usar INNER JOIN:
$sql = "
   SELECT 
    -- 1. SELECIONANDO COLUNAS DA TABELA PRINCIPAL (PRESENTES)
    p.id,                     -- ID do presente
    p.evento_id,              -- ID do evento ao qual o presente pertence
    p.nome AS nome_presente,  -- Nome do presente (renomeado para evitar conflito)
    p.preco,                  -- Preço do presente
    p.limite_maximo,          -- Limite de vezes que pode ser comprado
    p.imagem,                 -- Nome do arquivo da imagem

    -- 2. SELECIONANDO NOMES DAS TABELAS RELACIONADAS (CHAVES ESTRANGEIRAS)
    c.nome AS nome_categoria, -- Nome completo da categoria (puxado da tabela 'categorias')
    fp.nome AS nome_pagamento -- Nome completo da forma de pagamento (puxado da tabela 'formas_pagamento')

FROM 
    presentes AS p  -- 3. TABELA PRINCIPAL: Começamos pela tabela 'presentes', dando o apelido 'p'

-- 4. JUNÇÕES (JOINS) - Conectando 'presentes' a outras tabelas por meio de IDs
INNER JOIN 
    categorias AS c          -- Conectando com a tabela 'categorias', apelido 'c'
    ON p.categoria_id = c.id -- Onde o ID da categoria do presente (p.categoria_id) é igual ao ID da categoria (c.id)

INNER JOIN 
    formas_pagamento AS fp   -- Conectando com a tabela 'formas_pagamento', apelido 'fp'
    ON p.tipo_pagamento_id = fp.id -- Onde o ID do pagamento do presente (p.tipo_pagamento_id) é igual ao ID do pagamento (fp.id)

-- 5. ORDENAÇÃO (OPCIONAL)
ORDER BY 
    p.nome                   -- Ordena a lista final pelo nome do presente
";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
         $caminho_imagem = "../uploads/" . $row['imagem']; 

        echo "
        <div class='card'>
            <h3>" . htmlspecialchars($row['nome_presente']) . "</h3>
            <p><strong>Preço:</strong> R$ " . number_format($row['preco'], 2, ',', '.') . "</p>
            <p><strong>Categoria:</strong> " . htmlspecialchars($row['nome_categoria']) . "</p>
            <p><strong>Tipo de Pagamento:</strong> " . htmlspecialchars($row['nome_pagamento']) . "</p>
            <p><strong>Limite Máximo:</strong> " . htmlspecialchars($row['limite_maximo']) . "</p>
           
            <div class='imagem-presente'>
                <img src='" . htmlspecialchars($caminho_imagem) . "' alt='Imagem do Presente " . htmlspecialchars($row['nome_presente']) . "' style='width: 150px; height: auto; border: 1px solid #ccc;'>
            </div>
            
            </div>
        ";
    }
} else {
    // Adicionado tratamento de erro para a query, caso o JOIN falhe
    if (!$result) {
        echo "<p>Erro na consulta SQL: " . $conn->error . "</p>";
    } else {
        echo "<p>Nenhum presente cadastrado ainda.</p>";
    }
}

$conn->close();
?>