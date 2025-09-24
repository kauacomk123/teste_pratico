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
        
        h2{
            text-align: center;
            margin-bottom: 20px;
            color: #333;
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

        .form_cadastro{
              padding: 20px 40px;
    border-radius: 12px;
    background: white;
    max-width: 600px;
    margin: 0 auto; /* centraliza apenas o form */
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: block; /* não precisa de flex para o form */
    text-align: left; /* melhor para formulários */
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
                <li><a href="#" class="active" onclick="openTab('eventos')">Meus Eventos</a></li>
                <li><a href="#" onclick="openTab('criar-evento')"> Criar Evento</a></li>
                <li><a href="#" onclick="openTab('presentes')"> Gerenciar Presentes</a></li>
                <li><a href="#" onclick="openTab('convidados')"> Convidados</a></li>
                <li><a href="#" onclick="openTab('relatorios')">categorias e pagamento</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <!-- TAB: MEUS EVENTOS -->
            <div id="eventos" class="tab-content active">
                <h2>Meus Eventos</h2>
                <div class="grid" id="lista-eventos">
                    
                </div>
            </div>
            
            <!-- TAB: CRIAR EVENTO -->
    
        <div id="criar-evento" class="tab-content">
            <h2>Criar Novo Evento</h2>
    <form  class="form_cadastro" action="/cadastrar_evento.php" method="POST">
        <label>Nome do evento:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Data do evento:</label><br>
        <input type="date" name="data_evento" required><br><br>

        <label>Localização:</label><br>
        <input type="text" name="localizacao" required><br><br>

        <label>Anfitrião(ões):</label><br>
        <input type="text" name="anfitriao" required><br><br>

        <label>Senha do evento:</label><br>
        <input type="password" name="senha_evento" required><br><br>

        <button type="submit">Cadastrar</button>
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
                  <h1>Cadastro de Presente</h1>
        <form action="../backend/cadastrar_presente.php" method="POST" enctype="multipart/form-data">
        <label>Evento:</label><br>
        <input type="number" name="evento_id" placeholder="ID do evento" required><br><br>

        <label>Nome do presente:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Preço:</label><br>
        <input type="number" step="0.01" name="preco" required><br><br>

        <label>Limite máximo:</label><br>
        <input type="number" name="limite_maximo" required><br><br>

        <label>Categoria:</label><br>
        <input type="text" name="categoria" required><br><br>

        <label>Imagem do presente:</label><br>
        <input type="file" name="imagem" accept="image/*" required><br><br>

        <label>Tipo de pagamento:</label><br>
        <select name="tipo_pagamento" required>
            <option value="pix">Pix</option>
            <option value="link">Link</option>
            <option value="entrega">Entrega</option>
            <option value="cartao">Cartão de Crédito</option>
        </select><br><br>

        <button type="submit">Cadastrar Presente</button>
    </form>
            </div>
           <!-- TAB: CONVIDADOS -->
           <!-- <div id="convidados" class="tab-content">
                <h2>Convidados e Presentes Escolhidos</h2>
                <div id="lista-convidados">
                    Lista de convidados será carregada via JavaScript -->
           <!--     </div>
            </div>
        </div>
    </div>       -->
      

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
function excluirEvento(id) {
    if (confirm("Tem certeza que deseja excluir este evento?")) {
        fetch("../backend/excluir_evento.php?id=" + id)
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    alert("Evento excluído com sucesso!");
                    carregarEventos();
                } else {
                    alert("Erro: " + data);
                }
            });
    }
}

function editarEvento(id) {
    let nome = prompt("Novo nome do evento:");
    let data_evento = prompt("Nova data do evento (YYYY-MM-DD):");
    let localizacao = prompt("Nova localização:");
    let anfitriao = prompt("Novo anfitrião:");

    if (nome && data_evento && localizacao && anfitriao) {
        let formData = new FormData();
        formData.append("id", id);
        formData.append("nome", nome);
        formData.append("data_evento", data_evento);
        formData.append("localizacao", localizacao);
        formData.append("anfitriao", anfitriao);

        fetch("../backend/editar_evento.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                alert("Evento atualizado com sucesso!");
                carregarEventos();
            } else {
                alert("Erro: " + data);
            }
        });
    }
}

// Carregar dados iniciais
carregarEventos();
        
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