<?php
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta CEP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap">
    <link rel="stylesheet" href="front.css">
</head>

<body>
    <div class="container">
        <h1>Consulta CEP</h1>
        <form id="cepForm">
            <input type="text" id="cep" name="cep" placeholder="Insira seu CEP">
        </form>
        <div class="btn-container">
            <button class="btn" onclick="consultarCEPs()">
                <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
                </svg>
                <span>Consultar</span>
            </button>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h2 id="modalTitle">Resultado da Consulta</h2>
            <div id="result" class="result"></div>
            <div id="error" class="error-message"></div>
        </div>
    </div>

    <script>
        async function consultarCEPs() {
            const form = document.getElementById('cepForm');
            const formData = new FormData(form);
            const cep = formData.get('cep').trim();

            if (!cep) {
                document.getElementById('error').innerText = 'Por favor, insira um CEP para a busca.';
                document.getElementById('result').innerHTML = '';
                abrirModal();
                return;
            }

            const url = `http://localhost:8000/search/local/${cep}`;

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Erro na resposta da API');
                }
                const data = await response.json();
                mostrarResultados(data);
            } catch (error) {
                console.error('Erro ao consultar CEP:', error);
                document.getElementById('result').innerHTML = 'Erro ao consultar CEP.';
                document.getElementById('error').innerText = '';
                abrirModal();
            }
        }

        function mostrarResultados(data) {
            const resultDiv = document.getElementById('result');
            const errorDiv = document.getElementById('error');
            resultDiv.innerHTML = '';
            errorDiv.innerText = '';

            if (data.length === 0) {
                resultDiv.innerText = 'Nenhum dado encontrado para o CEP informado.';
            } else {
                data.forEach(cep => {
                    const cepInfo = `
                        <div>
                            <h3>CEP: ${cep.cep}</h3>
                            <p><strong>Logradouro:</strong> ${cep.logradouro}</p>
                            <p><strong>Complemento:</strong> ${cep.complemento}</p>
                            <p><strong>Bairro:</strong> ${cep.bairro}</p>
                            <p><strong>Localidade:</strong> ${cep.localidade}</p>
                            <p><strong>UF:</strong> ${cep.uf}</p>
                            <p><strong>IBGE:</strong> ${cep.ibge}</p>
                            <p><strong>GIA:</strong> ${cep.gia}</p>
                            <p><strong>DDD:</strong> ${cep.ddd}</p>
                            <p><strong>SIAFI:</strong> ${cep.siafi}</p>
                        </div>
                    `;
                    resultDiv.innerHTML += cepInfo;
                });
            }
            abrirModal();
        }

        function abrirModal() {
            document.getElementById('myModal').style.display = 'flex';
        }

        function fecharModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('myModal')) {
                fecharModal();
            }
        }
    </script>
</body>

</html>
