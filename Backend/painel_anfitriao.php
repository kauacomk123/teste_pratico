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
    <link rel="stylesheet" href="../Frontend/css/dasgboard.css">
    <title>Dashboard - Comemore+</title>
</head>
<body>
    <div class="header">
        <h1>Comemore+ Dashboard</h1>
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
    <form  class="form_cadastro" action="cadastra_evento.php" method="POST">
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
        
            <label>Eventos:</label><br>
        <select name="evento_id" required>
            <?php
            $sql = "SELECT id, nome FROM eventos ORDER BY nome";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                }
            }
            ?>
        </select><br><br>

        <label>Nome do presente:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Preço:</label><br>
        <input type="number" step="0.01" name="preco" required><br><br>

        <label>Limite máximo:</label><br>
        <input type="number" name="limite_maximo" required><br><br>


         <label>tipo de categoria:</label><br>
        <select name="categoria" required>
            <?php
            $sql = "SELECT id, nome FROM categorias ORDER BY nome";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                }
            }
            ?>
        </select><br><br>

        <label>Imagem do presente:</label><br>
        <input type="file" name="imagem" accept="image/*" required><br><br>


         <label>Tipo de pagamento:</label><br>
        <select name="tipo_pagamento" required>
            <?php
            $sql_pag = "SELECT id, nome FROM formas_pagamento ORDER BY nome";
            $result = $conn->query($sql_pag);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                }
            }
            ?>
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
//funçao para editar o evento
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