<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento do Tanque - NTI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        /* O desenho do Tanque */
        .tanque-wrapper {
            width: 150px;
            height: 400px;
            border: 4px solid #555;
            border-radius: 10px 10px 10px 10px;
            position: relative;
            margin: 20px auto;
            background-color: #333;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        /* A água dentro do tanque */
        .agua {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0%; /* Isso vai ser alterado pelo JS */
            background-color: #00a8ff;
            transition: height 1s ease-in-out;
            box-shadow: inset 0 20px 20px rgba(255,255,255,0.2);
        }
        .info {
            font-size: 24px;
            font-weight: bold;
            margin-top: 15px;
        }
        .status {
            font-size: 14px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Nível do Tanque</h2>
        
        <div class="tanque-wrapper">
            <div class="agua" id="nivel-agua"></div>
        </div>

        <div class="info">
            Nível Atual: <span id="texto-nivel">--</span>%
        </div>
        <div class="status" id="status-att">Aguardando dados...</div>
    </div>

    <script>
        function buscarNivel() {
            fetch('/api/leituras/latest')
                .then(response => response.json())
                .then(data => {
                    if(data && data.nivel !== undefined) {
                        // Atualiza a altura da div de água (assumindo que o ESP manda de 0 a 100)
                        document.getElementById('nivel-agua').style.height = data.nivel + '%';
                        // Atualiza o texto numérico
                        document.getElementById('texto-nivel').innerText = data.nivel;
                        
                        let dataHora = new Date(data.created_at).toLocaleTimeString('pt-BR');
                        document.getElementById('status-att').innerText = 'Última atualização: ' + dataHora;
                    }
                })
                .catch(error => console.error('Erro ao buscar dados:', error));
        }

        // Busca assim que a página carrega
        buscarNivel();
        
        // Fica consultando a API a cada 2 segundos (2000 ms)
        setInterval(buscarNivel, 2000);
    </script>

</body>
</html>