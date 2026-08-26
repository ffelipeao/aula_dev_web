// Procura elementos pelo id e guarda cada resultado em uma constante
const botaoContato = document.querySelector('#botao-contato');
const mensagemContato = document.querySelector('#mensagem-contato');
const anoAtual = document.querySelector('#ano-atual');

// Obtém o ano atual e insere o valor no rodapé
anoAtual.textContent = new Date().getFullYear();

// Executa a função quando a pessoa clica no botão
botaoContato.addEventListener('click', function () {
    // Insere a mensagem no parágrafo reservado para ela
    mensagemContato.textContent =
        'Obrigado pelo interesse! Entre em contato pelo e-mail informado acima.';
});
