<?php
// validar_anfitriao.php
session_start();

// Conexão com o banco de dados
$host = "localhost"; 
$usuario = "root";     // usuário do MySQL
$senha = "Santana27.";           // senha do MySQL
$banco = "lista_presente"; // nome do banco

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Pegando dados do formulário
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];

// Consulta ao banco
$sql = "SELECT * FROM administradores WHERE cpf = ? AND senha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cpf, $senha);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se encontrou usuário
if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Cria sessão do usuário
    $_SESSION['id_anfitriao'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];

    // Redireciona para painel
    header("Location: painel_anfitriao.php");
    exit();
} else {
    // Redireciona de volta para a página HTML com parâmetro de erro
    header("Location: ../Frontend/login_anfitriao.html?error=login_failed");
    exit();
}

$conn->close();
?>