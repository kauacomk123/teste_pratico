<?php
session_start();
include "../Backend/conexao.php";

// Verifica se veio do fluxo correto
if (!isset($_SESSION['cpf_temp']) || !isset($_SESSION['evento_id_temp'])) {
    header("Location: login_convidado.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Remove formatação do CPF
    $cpf_limpo = preg_replace('/[^0-9]/', '', $_SESSION['cpf_temp']);
    
    $nome = $_POST["nome"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $evento_id = $_SESSION['evento_id_temp'];
    $evento_nome = $_SESSION['evento_nome_temp'];
    $evento_data = $_SESSION['evento_data_temp'];
    $evento_local = $_SESSION['evento_localizacao_temp'];
    
    // Busca a senha do evento
    $sql_senha = "SELECT senha_evento, localizacao FROM eventos WHERE id = ?";
    $stmt_senha = $conn->prepare($sql_senha);
    $stmt_senha->bind_param("i", $evento_id);
    $stmt_senha->execute();
    $resultado_senha = $stmt_senha->get_result();
    $evento = $resultado_senha->fetch_assoc();
    $senha_evento = $evento['senha_evento'];

    // CADASTRA NOVO CONVIDADO
    $sql = "INSERT INTO convidados (cpf, nome, telefone, email, evento_id, senha_evento) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $cpf_limpo, $nome, $telefone, $email, $evento_id, $senha_evento);

    if ($stmt->execute()) {
        $id_convidado = $stmt->insert_id;
        
        $_SESSION['convidado_id'] = $id_convidado;
        $_SESSION['evento_id'] = $evento_id;
        $_SESSION['evento_nome'] = $evento_nome;
        $_SESSION['evento_data'] = $evento_data;
        $_SESSION['evento_localizacao'] = $evento_localizacao;
        $_SESSION['cpf'] = $cpf_limpo;
        $_SESSION['nome'] = $nome;
        
        unset($_SESSION['cpf_temp']);
        unset($_SESSION['evento_id_temp']);
        unset($_SESSION['evento_nome_temp']);
        unset($_SESSION['evento_data_temp']);
        unset($_SESSION['evento_localizacao_temp']);
        
        header("Location: painel_convidado.php");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Frontend/css/stylelogin.css">
    <title>Completar Cadastro</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        form { max-width: 400px; margin: 0 auto; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        .info { background: #f0f8ff; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .evento-info { font-size: 16px; margin: 5px 0; }
    </style>
</head>
<body>
     <div class="container">
    <div id="login-form">
    <h2>Completar Cadastro</h2>
    
    <div class="info">
        <p><strong>CPF:</strong> <?php echo $_SESSION['cpf_temp']; ?></p>
        <div class="evento-info">
            <strong>Evento:</strong> <?php echo $_SESSION['evento_nome_temp']; ?>
        </div>
        <div class="evento-info">
            <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($_SESSION['evento_data_temp'])); ?>
        </div>
        <div class="evento-info">
            <strong>Local:</strong> <?php echo $_SESSION['evento_localizacao_temp']; ?>
        </div>
    </div>
    
    <form method="post">
        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="tel" name="telefone" placeholder="Telefone" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <button type="submit">Completar Cadastro</button>
    </form>
    </div>
    </div>
</body>
</html>