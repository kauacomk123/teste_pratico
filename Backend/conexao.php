<?php
// Configurações do banco de dados
$host = "localhost";
$user = "root"; // usuário padrão do XAMPP
$password = "Santana27."; // sua senha
$database = "lista_presente"; // nome do seu banco

// Criar conexão
$conn = new mysqli($host, $user, $password, $database);

// Verificar se conectou
if ($conn->connect_error) {
    die("❌ Falha na conexão: " . $conn->connect_error);
}
?>
