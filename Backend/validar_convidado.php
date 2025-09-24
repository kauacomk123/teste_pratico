<?php
include "conexao.php";
session_start();

$cpf = $_POST['cpf'];
$senha_evento_digitada = $_POST['senha_evento'];

// Busca todos os eventos (agora com nome, data e localização)
$sql = "SELECT id, nome, data_evento, localizacao, senha_evento FROM eventos";
$resultado = $conn->query($sql);

$evento_encontrado = false;
$evento_id = null;
$evento_nome = null;
$evento_data = null;
$evento_localizacao = null;

if ($resultado->num_rows > 0) {
    while ($evento = $resultado->fetch_assoc()) {
        if ($senha_evento_digitada === $evento['senha_evento']) {
            $evento_encontrado = true;
            $evento_id = $evento['id'];
            $evento_nome = $evento['nome'];
            $evento_data = $evento['data_evento'];
            $evento_localizacao = $evento['localizacao'];
            break;
        }
    }
}

if ($evento_encontrado && $evento_id) {
    // Remove formatação do CPF
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);
    
    // VERIFICA se o convidado já existe
    $sql_verifica = "SELECT id, nome FROM convidados WHERE cpf = ? AND evento_id = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("si", $cpf_limpo, $evento_id);
    $stmt_verifica->execute();
    $resultado_convidado = $stmt_verifica->get_result();

    if ($resultado_convidado->num_rows > 0) {
        // CPF JÁ EXISTE - FAZ LOGIN DIRETO PARA O PAINEL
        $convidado = $resultado_convidado->fetch_assoc();
        
        $_SESSION['convidado_id'] = $convidado['id'];
        $_SESSION['evento_id'] = $evento_id;
        $_SESSION['evento_nome'] = $evento_nome;
        $_SESSION['evento_data'] = $evento_data;
        $_SESSION['evento_localizacao'] = $evento_localizacao;
        $_SESSION['cpf'] = $cpf_limpo;
        $_SESSION['nome'] = $convidado['nome'];
        
        header("Location: ../frontend/painel_convidado.php");
        exit();
        
    } else {
        // CPF NÃO EXISTE - MANDA PARA COMPLETAR CADASTRO
        $_SESSION['cpf_temp'] = $cpf;
        $_SESSION['evento_id_temp'] = $evento_id;
        $_SESSION['evento_nome_temp'] = $evento_nome;
        $_SESSION['evento_data_temp'] = $evento_data;
        $_SESSION['evento_localizacao_temp'] = $evento_localizacao;
        
        header("Location: ../frontend/completar_cadastro_convidado.php");
        exit();
    }
} else {
    echo "Senha do evento inválida!";
    echo '<br><a href="../frontend/login_convidado.html">Voltar</a>';
}

$conn->close();
?>