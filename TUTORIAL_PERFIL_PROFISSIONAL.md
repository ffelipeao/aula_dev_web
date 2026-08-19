# Projeto inicial: página de perfil profissional com HTML, CSS e JavaScript

## Objetivo da aula

Neste projeto, você criará uma página de **perfil profissional**, semelhante a um currículo on-line. A atividade utiliza:

- **HTML5** para organizar o conteúdo;
- **CSS3** para definir cores, tamanhos, espaçamentos e tornar a página responsiva;
- **JavaScript** para adicionar uma interação simples.

Ao final, cada aluno terá uma página que poderá personalizar com seu nome, foto, apresentação, formação, experiências, habilidades e formas de contato.

## 1. Crie a estrutura do projeto

Crie uma pasta chamada `perfil-profissional`. Dentro dela, organize os arquivos desta forma:

```text
perfil-profissional/
├── index.html
├── css/
│   └── estilo.css
├── js/
│   └── script.js
└── imagens/
    └── perfil.jpg
```

O arquivo `index.html` será a página principal. Os arquivos de estilo e de programação ficarão separados nas pastas `css` e `js`. A pasta `imagens` guardará a foto do perfil.

> Se não quiser usar uma foto neste momento, mantenha a pasta `imagens` e retire temporariamente a tag `<img>` do HTML.

## 2. Construa a página com HTML5

Abra o arquivo `index.html` e insira o código abaixo:

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Currículo profissional de Ana Silva">
    <title>Ana Silva | Perfil Profissional</title>

    <!-- Conecta o arquivo CSS à página -->
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <header class="cabecalho">
        <img class="foto-perfil" src="imagens/perfil.jpg"
             alt="Foto profissional de Ana Silva">

        <div>
            <h1>Ana Silva</h1>
            <p class="cargo">Estudante de Desenvolvimento Web</p>
            <p>Rio de Janeiro, RJ</p>
        </div>
    </header>

    <nav class="menu" aria-label="Navegação principal">
        <a href="#sobre">Sobre</a>
        <a href="#formacao">Formação</a>
        <a href="#experiencia">Experiência</a>
        <a href="#habilidades">Habilidades</a>
        <a href="#contato">Contato</a>
    </nav>

    <main class="conteudo">
        <section id="sobre" class="cartao">
            <h2>Sobre mim</h2>
            <p>
                Sou estudante de desenvolvimento web, interessado em criar páginas
                acessíveis, organizadas e fáceis de usar. Procuro oportunidades para
                aprender, colaborar em equipe e desenvolver minha carreira na área de tecnologia.
            </p>
        </section>

        <section id="formacao" class="cartao">
            <h2>Formação acadêmica</h2>

            <article>
                <h3>Curso de Desenvolvimento Web</h3>
                <p>Universidade Exemplo — 2026</p>
                <p>Conteúdos: HTML5, CSS3, JavaScript e fundamentos da web.</p>
            </article>
        </section>

        <section id="experiencia" class="cartao">
            <h2>Experiências e projetos</h2>

            <article>
                <h3>Projeto: página de perfil profissional</h3>
                <p><time datetime="2026">2026</time></p>
                <p>
                    Desenvolvimento de uma página responsiva utilizando HTML semântico,
                    folhas de estilo e JavaScript.
                </p>
            </article>
        </section>

        <section id="habilidades" class="cartao">
            <h2>Habilidades</h2>
            <ul class="lista-habilidades">
                <li>HTML5</li>
                <li>CSS3</li>
                <li>JavaScript</li>
                <li>Git e GitHub</li>
                <li>Trabalho em equipe</li>
            </ul>
        </section>

        <section id="contato" class="cartao">
            <h2>Contato</h2>
            <address>
                <p>E-mail: <a href="mailto:ana@email.com">ana@email.com</a></p>
                <p>
                    LinkedIn:
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer">
                        linkedin.com/in/ana-silva
                    </a>
                </p>
                <p>
                    GitHub:
                    <a href="https://github.com/" target="_blank" rel="noopener noreferrer">
                        github.com/ana-silva
                    </a>
                </p>
            </address>

            <button id="botao-contato" type="button">Exibir mensagem</button>
            <p id="mensagem-contato" class="mensagem" aria-live="polite"></p>
        </section>
    </main>

    <footer class="rodape">
        <p>&copy; <span id="ano-atual"></span> Ana Silva. Todos os direitos reservados.</p>
    </footer>

    <!-- Conecta o arquivo JavaScript ao final da página -->
    <script src="js/script.js"></script>
</body>
</html>
```

### O que os principais elementos fazem?

- `<!DOCTYPE html>` informa que o documento utiliza HTML5.
- `<html lang="pt-BR">` indica o idioma da página.
- `<head>` contém configurações e informações que não fazem parte do conteúdo visível.
- `<meta name="viewport">` ajuda a página a se adaptar a celulares e tablets.
- `<header>` representa o cabeçalho com as principais informações do perfil.
- `<nav>` contém os links de navegação.
- `<main>` reúne o conteúdo principal.
- `<section>` divide o currículo em assuntos.
- `<article>` representa um item independente, como um curso ou projeto.
- `<address>` identifica informações de contato.
- `<footer>` representa o rodapé.

Essas tags são chamadas de **elementos semânticos**, pois informam o significado de cada parte do documento. Isso melhora a organização, a acessibilidade e a leitura do código.

## 3. Adicione o estilo em um arquivo CSS separado

Abra `css/estilo.css` e insira:

```css
/* Configurações gerais */
:root {
    --cor-principal: #1d4ed8;
    --cor-secundaria: #eff6ff;
    --cor-texto: #1f2937;
    --cor-fundo: #f3f4f6;
    --cor-branca: #ffffff;
    --sombra: 0 4px 14px rgba(0, 0, 0, 0.08);
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    line-height: 1.6;
    color: var(--cor-texto);
    background-color: var(--cor-fundo);
}

/* Cabeçalho */
.cabecalho {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    padding: 48px 20px;
    color: var(--cor-branca);
    background: linear-gradient(135deg, #1e3a8a, var(--cor-principal));
}

.cabecalho h1 {
    margin: 0;
    font-size: 2.4rem;
}

.cabecalho p {
    margin: 4px 0;
}

.cargo {
    font-size: 1.2rem;
    font-weight: bold;
}

.foto-perfil {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 5px solid var(--cor-branca);
    border-radius: 50%;
}

/* Navegação */
.menu {
    position: sticky;
    top: 0;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px;
    background-color: var(--cor-branca);
    box-shadow: var(--sombra);
}

.menu a {
    padding: 8px 12px;
    color: var(--cor-principal);
    font-weight: bold;
    text-decoration: none;
    border-radius: 6px;
}

.menu a:hover,
.menu a:focus {
    color: var(--cor-branca);
    background-color: var(--cor-principal);
}

/* Conteúdo */
.conteudo {
    width: min(900px, 92%);
    margin: 32px auto;
}

.cartao {
    margin-bottom: 24px;
    padding: 28px;
    background-color: var(--cor-branca);
    border-left: 5px solid var(--cor-principal);
    border-radius: 10px;
    box-shadow: var(--sombra);
}

.cartao h2 {
    margin-top: 0;
    color: var(--cor-principal);
}

.cartao h3 {
    margin-bottom: 4px;
}

.lista-habilidades {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 0;
    list-style: none;
}

.lista-habilidades li {
    padding: 7px 12px;
    color: #1e3a8a;
    background-color: var(--cor-secundaria);
    border-radius: 20px;
}

a {
    color: var(--cor-principal);
}

address {
    font-style: normal;
}

button {
    padding: 10px 18px;
    color: var(--cor-branca);
    font-size: 1rem;
    cursor: pointer;
    background-color: var(--cor-principal);
    border: 0;
    border-radius: 6px;
}

button:hover,
button:focus {
    background-color: #1e3a8a;
}

.mensagem {
    min-height: 24px;
    font-weight: bold;
}

/* Rodapé */
.rodape {
    padding: 20px;
    color: var(--cor-branca);
    text-align: center;
    background-color: #111827;
}

/* Ajustes para telas pequenas */
@media (max-width: 600px) {
    .cabecalho {
        flex-direction: column;
        text-align: center;
    }

    .cabecalho h1 {
        font-size: 2rem;
    }

    .cartao {
        padding: 20px;
    }
}
```

### Conceitos de CSS utilizados

- `:root` armazena cores em variáveis para facilitar futuras alterações.
- `display: flex` organiza elementos em linha ou coluna.
- `margin` controla o espaço externo e `padding` controla o espaço interno.
- `border-radius` arredonda bordas.
- `box-shadow` cria sombras.
- `:hover` aplica um estilo quando o ponteiro passa sobre o elemento.
- `:focus` destaca um elemento selecionado pelo teclado.
- `@media` altera o layout em telas pequenas e torna a página responsiva.

## 4. Adicione uma interação com JavaScript

Abra `js/script.js` e insira:

```javascript
// Seleciona elementos do HTML pelo atributo id
const botaoContato = document.querySelector('#botao-contato');
const mensagemContato = document.querySelector('#mensagem-contato');
const anoAtual = document.querySelector('#ano-atual');

// Insere automaticamente o ano atual no rodapé
anoAtual.textContent = new Date().getFullYear();

// Executa uma função quando o botão é clicado
botaoContato.addEventListener('click', function () {
    mensagemContato.textContent =
        'Obrigado pelo interesse! Entre em contato pelo e-mail informado acima.';
});
```

O método `document.querySelector()` procura um elemento no HTML. O método `addEventListener()` permite executar uma ação quando ocorre um evento — neste caso, um clique. A propriedade `textContent` altera o texto apresentado na página.

## 5. Entenda como os arquivos são conectados

O CSS é carregado dentro do `<head>`:

```html
<link rel="stylesheet" href="css/estilo.css">
```

O JavaScript é carregado no final do `<body>`:

```html
<script src="js/script.js"></script>
```

Os caminhos começam a partir do local do `index.html`. Por isso, `css/estilo.css` significa: entre na pasta `css` e carregue o arquivo `estilo.css`.

## 6. Teste o projeto

1. Salve todos os arquivos.
2. Confirme se os nomes das pastas e dos arquivos estão iguais aos usados no código.
3. Abra `index.html` em um navegador.
4. Clique nos itens do menu e verifique a navegação entre as seções.
5. Clique no botão **Exibir mensagem**.
6. Reduza a largura da janela para observar o comportamento em uma tela menor.
7. Abra as ferramentas de desenvolvedor do navegador (`F12`) e verifique se há erros no console.

Se a imagem, o CSS ou o JavaScript não funcionar, confira primeiro o caminho e a grafia do nome do arquivo. Em alguns sistemas, `Estilo.css` e `estilo.css` são nomes diferentes.

## 7. Personalize seu currículo

Substitua as informações do exemplo pelas suas próprias informações:

- nome e cargo desejado;
- cidade e estado;
- resumo profissional;
- cursos e formação acadêmica;
- experiências, trabalhos voluntários ou projetos escolares;
- conhecimentos técnicos e habilidades pessoais;
- e-mail e links profissionais;
- foto com nome `perfil.jpg` dentro da pasta `imagens`.

Não publique endereço residencial, documentos, senhas, telefone pessoal ou outras informações sensíveis.

## 8. Desafios para praticar

Depois de concluir a versão inicial, tente realizar os desafios:

1. Altere as cores da página por meio das variáveis em `:root`.
2. Acrescente uma seção chamada **Idiomas**.
3. Inclua mais dois projetos no currículo.
4. Crie um botão para alternar entre tema claro e tema escuro.
5. Adicione links para os projetos publicados no GitHub.
6. Valide o HTML no [W3C Markup Validation Service](https://validator.w3.org/).

## Checklist de entrega

- [ ] O projeto possui as pastas `css`, `js` e `imagens`.
- [ ] O HTML utiliza elementos semânticos.
- [ ] O CSS está em um arquivo separado e foi conectado corretamente.
- [ ] O JavaScript está em um arquivo separado e foi conectado corretamente.
- [ ] A página possui informações profissionais personalizadas.
- [ ] Os links e o botão funcionam.
- [ ] A página se adapta a telas menores.
- [ ] Não existem erros no console do navegador.
- [ ] O código está indentado e organizado.

## Resultado esperado

O resultado será um currículo web simples, elegante e responsivo. Mais importante do que copiar o exemplo é compreender a responsabilidade de cada tecnologia: o **HTML estrutura**, o **CSS apresenta** e o **JavaScript adiciona comportamento**.
