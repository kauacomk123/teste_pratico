<?php
session_start();

// Verifica se o convidado está logado
if (!isset($_SESSION['convidado_id'])) {
    header("Location: login_convidado.html");
    exit();
}

// Formata a data do evento
$data_formatada = date('d/m/Y', strtotime($_SESSION['evento_data']));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Convidado</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { background: #f0f8ff; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
        .evento-info { margin: 10px 0; font-size: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
        
        <div class="evento-info">
            <strong>Evento:</strong> <?php echo $_SESSION['evento_nome']; ?>
        </div>
        <div class="evento-info">
            <strong>Data:</strong> <?php echo $data_formatada; ?>
        </div>
         <div class="evento-info">
            <strong>Local:</strong> <?php echo $_SESSION['evento_localizacao']; ?> <!-- CORRIGIDO -->
        </div>
        <div class="evento-info">
            <strong>Seu CPF:</strong> <?php echo $_SESSION['cpf']; ?>
        </div>
    </div>
    
    <h2>Escolha seu presente:</h2>
    <!-- Aqui você vai listar os presentes disponíveis -->
    
    <a href="../index3.html">Sair</a>
</body>
</html>