<?php
// painel_anfitriao.php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id_anfitriao'])) {
    header("Location: ../Frontend/login_anfitriao.html");
    exit();
}

// Conexão com o banco de dados
$host = "localhost"; 
$usuario = "root";     
$senha = "Santana27.";           
$banco = "lista_presente";

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Buscar dados do anfitrião (administrador)
$id_anfitriao = $_SESSION['id_anfitriao'];
$sql_anfitriao = "SELECT * FROM administradores WHERE id = ?";
$stmt_anfitriao = $conn->prepare($sql_anfitriao);
$stmt_anfitriao->bind_param("i", $id_anfitriao);
$stmt_anfitriao->execute();
$result_anfitriao = $stmt_anfitriao->get_result();
$anfitriao = $result_anfitriao->fetch_assoc();

// Descobrir automaticamente a coluna que relaciona eventos com administradores
$result_eventos = null;
$colunas_possiveis = ['id_administrador', 'administrador_id', 'id_admin', 'admin_id', 'id_anfitriao', 'anfitriao_id'];

foreach ($colunas_possiveis as $coluna) {
    // Verificar se a coluna existe na tabela eventos
    $check_coluna = $conn->query("SHOW COLUMNS FROM eventos LIKE '$coluna'");
    if ($check_coluna->num_rows > 0) {
        // Coluna encontrada, buscar eventos
        $sql_eventos = "SELECT * FROM eventos WHERE $coluna = ?";
        $stmt_eventos = $conn->prepare($sql_eventos);
        $stmt_eventos->bind_param("i", $id_anfitriao);
        $stmt_eventos->execute();
        $result_eventos = $stmt_eventos->get_result();
        break;
    }
}

// Se não encontrou nenhuma coluna de relacionamento, buscar todos os eventos (para teste)
if (!$result_eventos) {
    $sql_eventos = "SELECT * FROM eventos LIMIT 5";
    $result_eventos = $conn->query($sql_eventos);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Anfitrião - Comemore+</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --warning: #ffb800;
            --danger: #e63946;
            --border-radius: 8px;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .logo h1 {
            font-size: 24px;
            margin-top: 10px;
        }

        .logo img {
            width: 50px;
            height: 50px;
        }

        .menu {
            list-style: none;
            flex-grow: 1;
        }

        .menu li {
            margin-bottom: 5px;
        }

        .menu a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .menu a:hover, .menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--success);
        }

        .menu i {
            margin-right: 10px;
            font-size: 18px;
        }

        .user-info {
            padding: 20px;
            display: flex;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid white;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 30px;
            padding: 8px 15px;
            box-shadow: var(--shadow);
            width: 300px;
        }

        .search-bar input {
            border: none;
            outline: none;
            flex-grow: 1;
            padding: 5px 10px;
            font-size: 14px;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .stat-card {
            display: flex;
            align-items: center;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
        }

        .stat-text h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-text p {
            color: var(--gray);
            font-size: 14px;
        }

        /* Recent Activities */
        .activities {
            margin-top: 20px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
        }

        .activity-content {
            flex-grow: 1;
        }

        .activity-content h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .activity-content p {
            font-size: 14px;
            color: var(--gray);
        }

        .activity-time {
            font-size: 12px;
            color: var(--gray);
        }

        /* Upcoming Events */
        .events-list {
            margin-top: 15px;
        }

        .event-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.3s;
        }

        .event-item:hover {
            background-color: var(--light);
        }

        .event-date {
            text-align: center;
            margin-right: 15px;
            background: var(--light);
            padding: 10px;
            border-radius: var(--border-radius);
            min-width: 60px;
        }

        .event-date .day {
            font-size: 20px;
            font-weight: bold;
            display: block;
        }

        .event-date .month {
            font-size: 14px;
            text-transform: uppercase;
        }

        .event-details {
            flex-grow: 1;
        }

        .event-details h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .event-details p {
            font-size: 14px;
            color: var(--gray);
        }

        .event-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 15px;
        }

        .action-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .action-description {
            color: var(--gray);
            font-size: 14px;
        }

        /* Responsividade */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }
            
            .logo h1, .menu span, .user-info .user-name {
                display: none;
            }
            
            .menu a {
                justify-content: center;
                padding: 15px 0;
            }
            
            .menu i {
                margin-right: 0;
                font-size: 20px;
            }
            
            .user-info {
                justify-content: center;
            }
            
            .user-info img {
                margin-right: 0;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .search-bar {
                width: 200px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTIwIDdINGEyIDIgMCAwIDAtMiAydjEwYTIgMiAwIDAgMCAyIDJoMTZhMiAyIDAgMCAwIDItMlY5YTIgMiAwIDAgMC0yLTJ6Ij48L3BhdGg+PHBvbHlsaW5lIHBvaW50cz0iMTYgNSAyMCA1IDIwIDkgMTYgOSI+PC9wb2x5bGluZT48cGF0aCBkPSJNOSA1YTcgNyAwIDAgMCAwIDE0Ij48L3BhdGg+PHBhdGggZD0iTTkgMTJhMiAyIDAgMCAwIDIgMmgxYTIgMiAwIDAgMCAyLTJjMC0xLjEtMS4zLTIuNC0yLTItLjcgMC0yIDEuOC0yIDJ6Ij48L3BhdGg+PC9zdmc+" alt="Presentes">
            <h1>Comemore+</h1>
        </div>
        
        <ul class="menu">
            <li><a href="#" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="criar_evento.php"><i class="fas fa-calendar-alt"></i> <span>Eventos</span></a></li>
            <li><a href="gerenciar_presentes.php"><i class="fas fa-gift"></i> <span>Presentes</span></a></li>
            <li><a href="convidados.php"><i class="fas fa-users"></i> <span>Convidados</span></a></li>
            <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> <span>Relatórios</span></a></li>
            <li><a href="#"><i class="fas fa-cog"></i> <span>Configurações</span></a></li>
        </ul>
        
        <div class="user-info">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTIwIDIxdi0yYTQgNCAwIDAgMC00LTRINGE0IDQgMCAwIDAtNCA0djIiPjwvcGF0aD48Y2lyY2xlIGN4PSIxMiIgY3k9IjciIHI9IjQiPjwvY2lyY2xlPjwvc3ZnPg==" alt="Usuário">
            <div class="user-name"><?php echo htmlspecialchars($anfitriao['nome']); ?></div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1 class="page-title">Dashboard</h1>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Pesquisar eventos...">
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="card stat-card">
                <div class="stat-icon" style="background-color: rgba(67, 97, 238, 0.1); color: var(--primary);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-text">
                    <h3><?php echo $result_eventos ? $result_eventos->num_rows : '0'; ?></h3>
                    <p>Eventos Criados</p>
                </div>
            </div>

            <div class="card stat-card">
                <div class="stat-icon" style="background-color: rgba(76, 201, 240, 0.1); color: var(--success);">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="stat-text">
                    <h3>0</h3>
                    <p>Presentes Cadastrados</p>
                </div>
            </div>

            <div class="card stat-card">
                <div class="stat-icon" style="background-color: rgba(255, 184, 0, 0.1); color: var(--warning);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-text">
                    <h3>0</h3>
                    <p>Convidados Confirmados</p>
                </div>
            </div>

            <div class="card stat-card">
                <div class="stat-icon" style="background-color: rgba(230, 57, 70, 0.1); color: var(--danger);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-text">
                    <h3>R$ 0,00</h3>
                    <p>Total Arrecadado</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="criar_evento.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="action-title">Criar Evento</div>
                <div class="action-description">Cadastre um novo evento</div>
            </a>
            
            <a href="gerenciar_presentes.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="action-title">Gerenciar Presentes</div>
                <div class="action-description">Adicione e edite presentes</div>
            </a>
            
            <a href="convidados.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="action-title">Convidados</div>
                <div class="action-description">Acompanhe os convidados</div>
            </a>
            
            <a href="relatorios.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="action-title">Relatórios</div>
                <div class="action-description">Visualize estatísticas</div>
            </a>
        </div>

        <div class="dashboard-cards">
            <!-- Meus Eventos -->
            <div class="card" style="grid-column: span 2;">
                <h2><i class="fas fa-calendar"></i> Meus Eventos</h2>
                <div class="events-list">
                    <?php if ($result_eventos && $result_eventos->num_rows > 0): ?>
                        <?php while ($evento = $result_eventos->fetch_assoc()): ?>
                            <div class="event-item">
                                <div class="event-date">
                                    <span class="day"><?php echo date('d', strtotime($evento['data_evento'])); ?></span>
                                    <span class="month"><?php echo date('M', strtotime($evento['data_evento'])); ?></span>
                                </div>
                                <div class="event-details">
                                    <h4><?php echo htmlspecialchars($evento['nome']); ?></h4>
                                    <p><?php echo htmlspecialchars($evento['local']); ?> • Senha: <?php echo htmlspecialchars($evento['senha_evento']); ?></p>
                                </div>
                                <div class="event-actions">
                                    <a href="editar_evento.php?id=<?php echo $evento['id']; ?>" class="btn btn-primary">Editar</a>
                                    <a href="visualizar_evento.php?id=<?php echo $evento['id']; ?>" class="btn btn-outline">Visualizar</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px; color: var(--gray);">
                            <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 15px;"></i>
                            <p>Nenhum evento criado ainda. Comece criando seu primeiro evento!</p>
                            <a href="criar_evento.php" class="btn btn-primary" style="margin-top: 15px;">Criar Primeiro Evento</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="card">
                <h2><i class="fas fa-history"></i> Atividades Recentes</h2>
                <div class="activities">
                    <div class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(67, 97, 238, 0.1); color: var(--primary);">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Login realizado</h4>
                            <p>Bem-vindo ao sistema</p>
                        </div>
                        <div class="activity-time">agora</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(76, 201, 240, 0.1); color: var(--success);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Painel acessado</h4>
                            <p>Painel do anfitrião</p>
                        </div>
                        <div class="activity-time">hoje</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Interatividade básica
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona classe active ao item de menu clicado
            const menuItems = document.querySelectorAll('.menu a');
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Efeito de hover nos botões
            const buttons = document.querySelectorAll('.btn, .action-card');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                });
            });
        });
    </script>
</body>
</html>