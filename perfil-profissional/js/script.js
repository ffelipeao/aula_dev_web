const botaoContato = document.querySelector('#botao-contato');
const mensagemContato = document.querySelector('#mensagem-contato');
const anoAtual = document.querySelector('#ano-atual');

anoAtual.textContent = new Date().getFullYear();

botaoContato.addEventListener('click', function () {
    mensagemContato.textContent =
        'Obrigado pelo interesse! Entre em contato pelo e-mail informado acima.';
});
