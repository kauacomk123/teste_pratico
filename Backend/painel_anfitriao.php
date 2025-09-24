<?php
session_start();
if (!isset($_SESSION['id_anfitriao'])) {
    header("Location: ../Frontend/login_anfitriao.html");
    exit();
}

require_once("./conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Comemore+</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f7fb;
        }
        
        .header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        
        .sidebar {
            background: #2d3748;
            color: white;
            padding: 20px 0;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: 0.3s;
        }
        
        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background: #4361ee;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .btn {
            background: #4361ee;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .presente-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        
        .presente-item img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Comemore+ - Dashboard do Anfitrião</h1>
        <div>
            <span>Olá, <?php echo $_SESSION['nome']; ?></span>
            <a href="../index3.html" style="color: white; margin-left: 20px;">Sair</a>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="#" class="active" onclick="openTab('eventos')">📅 Meus Eventos</a></li>
                <li><a href="#" onclick="openTab('criar-evento')">➕ Criar Evento</a></li>
                <li><a href="#" onclick="openTab('presentes')">🎁 Gerenciar Presentes</a></li>
                <li><a href="#" onclick="openTab('convidados')">👥 Convidados</a></li>
                <li><a href="#" onclick="openTab('relatorios')">📊 Relatórios</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <!-- TAB: MEUS EVENTOS -->
            <div id="eventos" class="tab-content active">
                <h2>Meus Eventos</h2>
                <div class="grid" id="lista-eventos">
                    <!-- Eventos serão carregados via JavaScript -->
                </div>
            </div>
            
            <!-- TAB: CRIAR EVENTO -->
            <div id="criar-evento" class="tab-content">
                <h2>Criar Novo Evento</h2>
                <form id="form-evento" class="card">
                    <div class="form-group">
                        <label>Nome do Evento:</label>
                        <input type="text" name="nome_evento" required>
                    </div>
                    <div class="form-group">
                        <label>Data do Evento:</label>
                        <input type="date" name="data_evento" required>
                    </div>
                    <div class="form-group">
                        <label>Localização:</label>
                        <input type="text" name="localizacao" required>
                    </div>
                    <div class="form-group">
                        <label>Anfitrião(ões):</label>
                        <input type="text" name="anfitrioes" required>
                    </div>
                    <div class="form-group">
                        <label>Senha do Evento (para convidados):</label>
                        <input type="password" name="senha_evento" required>
                    </div>
                    <button type="submit" class="btn">Criar Evento</button>
                </form>
            </div>
            
            <!-- TAB: GERENCIAR PRESENTES -->
            <div id="presentes" class="tab-content">
                <h2>Gerenciar Presentes</h2>
                <button class="btn" onclick="openTab('criar-presente')">Adicionar Presente</button>
                <div class="grid" id="lista-presentes">
                    <!-- Presentes serão carregados via JavaScript -->
                </div>
            </div>
            
            <!-- TAB: CRIAR PRESENTE -->
            <div id="criar-presente" class="tab-content">
                <h2>Adicionar Presente</h2>
                <form id="form-presente" class="card" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nome do Presente:</label>
                        <input type="text" name="nome_presente" required>
                    </div>
                    <div class="form-group">
                        <label>Preço:</label>
                        <input type="number" step="0.01" name="preco" required>
                    </div>
                    <div class="form-group">
                        <label>Limite Máximo:</label>
                        <input type="number" name="limite_maximo" required>
                    </div>
                    <div class="form-group">
                        <label>Categoria:</label>
                        <select name="categoria" required>
                            <option value="">Selecione...</option>
                            <option value="Casa">Casa</option>
                            <option value="Eletrodomésticos">Eletrodomésticos</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Viagem">Viagem</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Imagem do Presente:</label>
                        <input type="file" name="imagem" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Tipo de Pagamento:</label>
                        <select name="tipo_pagamento" required>
                            <option value="pix">PIX</option>
                            <option value="link">Link de Pagamento</option>
                            <option value="entrega">Entrega</option>
                            <option value="cartao">Cartão de Crédito</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Adicionar Presente</button>
                </form>
            </div>
            
            <!-- TAB: CONVIDADOS -->
            <div id="convidados" class="tab-content">
                <h2>Convidados e Presentes Escolhidos</h2>
                <div id="lista-convidados">
                    <!-- Lista de convidados será carregada via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para alternar entre abas
        function openTab(tabName) {
            // Esconde todas as abas
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Mostra a aba selecionada
            document.getElementById(tabName).classList.add('active');
            
            // Atualiza menu ativo
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Carrega dados específicos da aba
            if (tabName === 'eventos') carregarEventos();
            if (tabName === 'presentes') carregarPresentes();
            if (tabName === 'convidados') carregarConvidados();
        }
        
        // Carregar eventos do banco
        function carregarEventos() {
            fetch('carregar_eventos.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('lista-eventos').innerHTML = data;
                });
        }
        
        // Carregar presentes do banco
        function carregarPresentes() {
            fetch('carregar_presentes.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('lista-presentes').innerHTML = data;
                });
        }
        
        // Carregar convidados do banco
        function carregarConvidados() {
            fetch('carregar_convidados.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('lista-convidados').innerHTML = data;
                });
        }
        
        // Formulário de evento
        document.getElementById('form-evento').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('criar_evento.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                this.reset();
                carregarEventos();
                openTab('eventos');
            });
        });
        
        // Formulário de presente
        document.getElementById('form-presente').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('criar_presente.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Presente adicionado com sucesso!');
                this.reset();
                carregarPresentes();
                openTab('presentes');
            });
        });
        
        // Carregar dados iniciais
        carregarEventos();
    </script>
</body>
</html>