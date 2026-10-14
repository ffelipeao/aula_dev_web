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
<!-- Informa ao navegador que este documento usa o padrão HTML5 -->
<!DOCTYPE html>
<!-- Inicia o documento e define o português do Brasil como idioma principal -->
<html lang="pt-BR">
<!-- Reúne configurações e informações sobre a página -->
<head>
    <!-- Permite exibir corretamente acentos e caracteres especiais -->
    <meta charset="UTF-8">
    <!-- Faz a largura da página acompanhar a tela de celulares e tablets -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fornece aos buscadores uma descrição resumida da página -->
    <meta name="description" content="Currículo profissional de Ana Silva">
    <!-- Define o texto exibido na aba do navegador -->
    <title>Ana Silva | Perfil Profissional</title>

    <!-- Conecta o arquivo CSS à página -->
    <link rel="stylesheet" href="css/estilo.css">
</head>
<!-- Inicia todo o conteúdo visível da página -->
<body>
    <!-- Cria o cabeçalho; a classe permite estilizar esta área no CSS -->
    <header class="cabecalho">
        <!-- Exibe a foto e fornece uma descrição alternativa para acessibilidade -->
        <img class="foto-perfil" src="imagens/perfil.jpg"
             alt="Foto profissional de Ana Silva">

        <!-- Agrupa o nome, o cargo e a localização -->
        <div>
            <!-- Define o título principal da página -->
            <h1>Ana Silva</h1>
            <!-- Exibe o cargo; a classe permite aplicar um estilo específico -->
            <p class="cargo">Estudante de Desenvolvimento Web</p>
            <!-- Exibe a localização em um parágrafo -->
            <p>Rio de Janeiro, RJ</p>
        </div>
    </header>

    <!-- Cria o menu e dá a ele um nome compreensível para leitores de tela -->
    <nav class="menu" aria-label="Navegação principal">
        <!-- Cada link leva à seção que possui o id indicado depois de # -->
        <a href="#sobre">Sobre</a>
        <a href="#formacao">Formação</a>
        <a href="#experiencia">Experiência</a>
        <a href="#habilidades">Habilidades</a>
        <a href="#contato">Contato</a>
    </nav>

    <!-- Delimita o conteúdo principal do documento -->
    <main class="conteudo">
        <!-- Cria a seção Sobre; o id é o destino do primeiro link do menu -->
        <section id="sobre" class="cartao">
            <!-- Define o título desta seção -->
            <h2>Sobre mim</h2>
            <!-- Apresenta o resumo profissional em um parágrafo -->
            <p>
                Sou estudante de desenvolvimento web, interessado em criar páginas
                acessíveis, organizadas e fáceis de usar. Procuro oportunidades para
                aprender, colaborar em equipe e desenvolver minha carreira na área de tecnologia.
            </p>
        </section>

        <section id="formacao" class="cartao">
            <h2>Formação acadêmica</h2>

            <!-- Representa um curso que pode ser entendido de forma independente -->
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
                <!-- Marca 2026 como uma informação de tempo compreensível por máquinas -->
                <p><time datetime="2026">2026</time></p>
                <p>
                    Desenvolvimento de uma página responsiva utilizando HTML semântico,
                    folhas de estilo e JavaScript.
                </p>
            </article>
        </section>

        <section id="habilidades" class="cartao">
            <h2>Habilidades</h2>
            <!-- Cria uma lista não ordenada; cada li representa uma habilidade -->
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
            <!-- Identifica semanticamente o bloco de informações de contato -->
            <address>
                <!-- mailto: solicita a abertura do aplicativo de e-mail -->
                <p>E-mail: <a href="mailto:ana@email.com">ana@email.com</a></p>
                <p>
                    LinkedIn:
                    <!-- Abre o LinkedIn em outra aba sem dar acesso à página original -->
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

            <!-- Cria o botão que será identificado e controlado pelo JavaScript -->
            <button id="botao-contato" type="button">Exibir mensagem</button>
            <!-- Reserva espaço para a mensagem; aria-live anuncia mudanças ao leitor de tela -->
            <p id="mensagem-contato" class="mensagem" aria-live="polite"></p>
        </section>
    </main>

    <footer class="rodape">
        <!-- O JavaScript preencherá o span com o ano atual -->
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
    --cor-principal: #1d4ed8; /* Guarda o azul principal em uma variável reutilizável */
    --cor-secundaria: #eff6ff; /* Guarda o azul-claro usado nos destaques */
    --cor-texto: #1f2937; /* Define a cor-padrão dos textos */
    --cor-fundo: #f3f4f6; /* Define a cor do fundo da página */
    --cor-branca: #ffffff; /* Guarda o branco usado em diferentes elementos */
    --sombra: 0 4px 14px rgba(0, 0, 0, 0.08); /* Cria uma sombra suave reutilizável */
}

/* Seleciona todos os elementos da página */
* {
    box-sizing: border-box; /* Inclui bordas e preenchimentos no tamanho total do elemento */
}

/* Seleciona o elemento raiz do documento */
html {
    scroll-behavior: smooth; /* Suaviza a rolagem ao clicar nos links do menu */
}

/* Aplica configurações ao corpo inteiro da página */
body {
    margin: 0; /* Remove a margem que o navegador adiciona automaticamente */
    font-family: Arial, Helvetica, sans-serif; /* Define a família de fontes e alternativas */
    line-height: 1.6; /* Aumenta o espaço entre as linhas para facilitar a leitura */
    color: var(--cor-texto); /* Usa a variável como cor do texto */
    background-color: var(--cor-fundo); /* Aplica a cor de fundo armazenada na variável */
}

/* Cabeçalho */
.cabecalho {
    display: flex; /* Coloca a foto e os textos lado a lado */
    align-items: center; /* Alinha os itens verticalmente ao centro */
    justify-content: center; /* Centraliza o conjunto horizontalmente */
    gap: 24px; /* Cria espaço entre a foto e os textos */
    padding: 48px 20px; /* Adiciona 48 px na vertical e 20 px na horizontal */
    color: var(--cor-branca); /* Deixa o texto branco */
    background: linear-gradient(135deg, #1e3a8a, var(--cor-principal)); /* Cria um fundo em degradê */
}

.cabecalho h1 {
    margin: 0; /* Remove a margem-padrão do título */
    font-size: 2.4rem; /* Define o título com 2,4 vezes o tamanho-base da fonte */
}

.cabecalho p {
    margin: 4px 0; /* Adiciona 4 px acima e abaixo e remove as margens laterais */
}

.cargo {
    font-size: 1.2rem; /* Deixa o cargo um pouco maior que o texto normal */
    font-weight: bold; /* Exibe o cargo em negrito */
}

.foto-perfil {
    width: 150px; /* Define a largura da foto */
    height: 150px; /* Define a altura da foto */
    object-fit: cover; /* Preenche a área sem distorcer a imagem */
    border: 5px solid var(--cor-branca); /* Adiciona uma borda branca contínua */
    border-radius: 50%; /* Transforma a imagem quadrada em um círculo */
}

/* Navegação */
.menu {
    position: sticky; /* Mantém o menu visível durante a rolagem */
    top: 0; /* Fixa o menu junto ao topo quando ele passa por essa posição */
    display: flex; /* Organiza os links com Flexbox */
    justify-content: center; /* Centraliza os links horizontalmente */
    flex-wrap: wrap; /* Permite que os links passem para outra linha */
    gap: 8px; /* Cria espaço entre os links */
    padding: 12px; /* Adiciona espaço interno ao menu */
    background-color: var(--cor-branca); /* Define o fundo branco */
    box-shadow: var(--sombra); /* Aplica a sombra guardada na variável */
}

.menu a {
    padding: 8px 12px; /* Aumenta a área interna clicável dos links */
    color: var(--cor-principal); /* Aplica o azul principal ao texto */
    font-weight: bold; /* Deixa os links em negrito */
    text-decoration: none; /* Remove o sublinhado-padrão */
    border-radius: 6px; /* Arredonda os cantos do link */
}

.menu a:hover,
.menu a:focus {
    color: var(--cor-branca); /* Deixa o texto branco ao passar o mouse ou usar o teclado */
    background-color: var(--cor-principal); /* Colore o fundo do link em interação */
}

/* Conteúdo */
.conteudo {
    width: min(900px, 92%); /* Usa no máximo 900 px ou 92% da tela, o que for menor */
    margin: 32px auto; /* Cria espaço vertical e centraliza o conteúdo */
}

.cartao {
    margin-bottom: 24px; /* Separa um cartão do seguinte */
    padding: 28px; /* Cria espaço entre o conteúdo e as bordas */
    background-color: var(--cor-branca); /* Deixa o fundo do cartão branco */
    border-left: 5px solid var(--cor-principal); /* Cria a faixa azul à esquerda */
    border-radius: 10px; /* Arredonda os cantos do cartão */
    box-shadow: var(--sombra); /* Aplica uma sombra suave */
}

.cartao h2 {
    margin-top: 0; /* Remove o espaço acima do título do cartão */
    color: var(--cor-principal); /* Deixa o título azul */
}

.cartao h3 {
    margin-bottom: 4px; /* Reduz o espaço abaixo do subtítulo */
}

.lista-habilidades {
    display: flex; /* Organiza as habilidades lado a lado */
    flex-wrap: wrap; /* Leva os itens excedentes para a próxima linha */
    gap: 10px; /* Define a distância entre os itens */
    padding: 0; /* Remove o recuo-padrão da lista */
    list-style: none; /* Remove os marcadores da lista */
}

.lista-habilidades li {
    padding: 7px 12px; /* Cria espaço interno em cada habilidade */
    color: #1e3a8a; /* Define um azul-escuro para o texto */
    background-color: var(--cor-secundaria); /* Aplica um fundo azul-claro */
    border-radius: 20px; /* Deixa cada item com formato de cápsula */
}

a {
    color: var(--cor-principal); /* Define a cor de todos os links da página */
}

address {
    font-style: normal; /* Remove o itálico aplicado por padrão ao endereço */
}

button {
    padding: 10px 18px; /* Aumenta a área interna do botão */
    color: var(--cor-branca); /* Deixa o texto do botão branco */
    font-size: 1rem; /* Mantém a fonte no tamanho-base da página */
    cursor: pointer; /* Mostra o cursor de clique sobre o botão */
    background-color: var(--cor-principal); /* Deixa o fundo do botão azul */
    border: 0; /* Remove a borda-padrão do navegador */
    border-radius: 6px; /* Arredonda os cantos do botão */
}

button:hover,
button:focus {
    background-color: #1e3a8a; /* Escurece o botão durante a interação */
}

.mensagem {
    min-height: 24px; /* Reserva espaço mesmo antes de existir uma mensagem */
    font-weight: bold; /* Destaca a mensagem em negrito */
}

/* Rodapé */
.rodape {
    padding: 20px; /* Cria espaço interno no rodapé */
    color: var(--cor-branca); /* Deixa o texto branco */
    text-align: center; /* Centraliza o texto */
    background-color: #111827; /* Aplica um fundo azul quase preto */
}

/* Ajustes para telas pequenas */
@media (max-width: 600px) {
    /* Estas regras só são aplicadas em telas com até 600 px de largura */
    .cabecalho {
        flex-direction: column; /* Coloca a foto acima dos textos */
        text-align: center; /* Centraliza os textos do cabeçalho */
    }

    .cabecalho h1 {
        font-size: 2rem; /* Reduz o título para caber melhor na tela */
    }

    .cartao {
        padding: 20px; /* Reduz o espaço interno dos cartões */
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
// Procura no HTML o elemento que possui o id "botao-contato" e o guarda em uma constante
const botaoContato = document.querySelector('#botao-contato');

// Procura o parágrafo no qual a mensagem será exibida e o guarda em uma constante
const mensagemContato = document.querySelector('#mensagem-contato');

// Procura o span do rodapé que receberá o ano e o guarda em uma constante
const anoAtual = document.querySelector('#ano-atual');

// new Date() obtém a data atual, getFullYear() extrai o ano e textContent o insere no span
anoAtual.textContent = new Date().getFullYear();

// Registra uma função que será executada sempre que o botão receber um clique
botaoContato.addEventListener('click', function () {
    // Substitui o conteúdo do parágrafo pelo texto da mensagem
    mensagemContato.textContent =
        // A string entre aspas é o texto que aparecerá para a pessoa usuária
        'Obrigado pelo interesse! Entre em contato pelo e-mail informado acima.';
// Encerra a função e a chamada do método addEventListener
});
```

O método `document.querySelector()` procura um elemento no HTML. O método `addEventListener()` permite executar uma ação quando ocorre um evento — neste caso, um clique. A propriedade `textContent` altera o texto apresentado na página.

## 5. Entenda como os arquivos são conectados

O CSS é carregado dentro do `<head>`:

```html
<!-- rel informa que o arquivo é uma folha de estilos; href indica seu caminho -->
<link rel="stylesheet" href="css/estilo.css">
```

O JavaScript é carregado no final do `<body>`:

```html
<!-- src indica o caminho do arquivo JavaScript que será executado -->
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

## 7. Salve o portfólio em um repositório do GitHub

Um repositório guarda os arquivos e o histórico de alterações do projeto. Ao manter o portfólio em um repositório público, o aluno pode compartilhar seu código e apresentar sua evolução profissional.

Antes de começar, [crie uma conta no GitHub](https://github.com/signup) ou entre em uma conta existente. Não envie senhas, documentos pessoais, chaves de acesso ou outras informações sigilosas para o repositório.

### Crie o repositório

1. No GitHub, clique no botão **+**, no canto superior direito, e escolha **New repository**.
2. No campo **Repository name**, informe um nome como `meu-portfolio`.
3. Em **Description**, escreva uma descrição curta, por exemplo: `Portfólio profissional desenvolvido com HTML, CSS e JavaScript`.
4. Marque a opção **Public** para que outras pessoas e o HTMLPreview possam acessar o projeto.
5. Clique em **Create repository**.

Consulte também a documentação oficial sobre [como criar um repositório](https://docs.github.com/pt/repositories/creating-and-managing-repositories/creating-a-new-repository).

### Opção A: envie os arquivos pelo site do GitHub

Esta é a opção mais simples para quem ainda não utiliza comandos do Git:

1. Abra o repositório recém-criado.
2. Clique em **Add file** e depois em **Upload files**.
3. Arraste a pasta `perfil-profissional` para a área de envio. Confirme que `index.html` e as pastas `css`, `js` e `imagens` aparecem na lista.
4. No campo da mensagem, escreva algo como `Adiciona projeto de perfil profissional`.
5. Confirme o envio clicando em **Commit changes**.

O GitHub chama cada registro de alteração de **commit**. Uma mensagem clara ajuda a entender o que foi modificado. A documentação oficial apresenta mais detalhes sobre [como adicionar arquivos a um repositório](https://docs.github.com/pt/repositories/working-with-files/managing-files/adding-a-file-to-a-repository).

### Opção B: envie o projeto usando o Git no terminal

Se o Git estiver instalado, abra o terminal dentro da pasta que contém `perfil-profissional`. Ao criar o repositório no site, deixe-o vazio, sem adicionar `README`, `.gitignore` ou licença, para evitar conflitos no primeiro envio.

Substitua `SEU-USUARIO` pelo seu nome de usuário do GitHub e execute um comando de cada vez:

```bash
# Entra na pasta que será transformada em repositório
cd caminho/para/meu-portfolio

# Inicia o controle de versão Git nesta pasta
git init

# Prepara todos os arquivos para o primeiro registro
git add .

# Cria o primeiro commit com uma mensagem descritiva
git commit -m "Adiciona projeto de perfil profissional"

# Define main como o nome da branch principal
git branch -M main

# Conecta o projeto local ao repositório criado no GitHub
git remote add origin https://github.com/SEU-USUARIO/meu-portfolio.git

# Envia os arquivos e associa a branch local à branch remota
git push -u origin main
```

O GitHub poderá solicitar autenticação durante o primeiro envio. Siga as instruções apresentadas na tela; a senha comum da conta não é aceita como senha do Git em operações autenticadas.

### Atualize o portfólio depois de fazer alterações

Sempre que modificar o HTML, o CSS, o JavaScript ou as imagens, salve os arquivos e execute:

```bash
git add .
git commit -m "Atualiza informações do portfólio"
git push
```

Use uma mensagem de commit que descreva a mudança realizada. Por exemplo: `Adiciona seção de projetos` ou `Atualiza dados de contato`.

Ao final, abra o repositório no navegador e confirme se esta estrutura foi preservada:

```text
meu-portfolio/
└── perfil-profissional/
    ├── index.html
    ├── css/
    ├── js/
    └── imagens/
```

Você também pode criar um arquivo `README.md` na página inicial do repositório para apresentar o projeto, listar as tecnologias utilizadas e incluir o link de visualização do portfólio.

### Destaque o portfólio no seu perfil

Depois de concluir o envio, você pode fixar o repositório para que ele apareça entre os seus principais trabalhos:

1. Clique na sua foto no canto superior direito e escolha **Your profile**.
2. Na seção **Popular repositories** ou **Pinned**, clique em **Customize your pins**.
3. Selecione o repositório `meu-portfolio`.
4. Clique em **Save pins**.

Assim, quem visitar seu perfil encontrará o projeto com mais facilidade. Consulte a documentação oficial sobre [como fixar repositórios no perfil](https://docs.github.com/en/account-and-profile/how-tos/profile-customization/pinning-items-to-your-profile).

## 8. Visualize a página publicada com o HTMLPreview

Depois de enviar o projeto para um repositório **público** no GitHub, você pode usar o [HTMLPreview](https://htmlpreview.github.io/) para visualizar o arquivo HTML como uma página web formatada. Isso permite conferir o resultado sem baixar o projeto.

Primeiro, abra o arquivo `index.html` no GitHub. O endereço terá uma estrutura semelhante a esta:

```text
https://github.com/USUARIO/REPOSITORIO/blob/main/perfil-profissional/index.html
```

Em seguida, acrescente `https://htmlpreview.github.io/?` antes do endereço completo do arquivo. O link final seguirá este formato:

```text
https://htmlpreview.github.io/?https://github.com/USUARIO/REPOSITORIO/blob/main/perfil-profissional/index.html
```

Neste projeto, use o exemplo abaixo:

[Visualizar a página de perfil profissional com o HTMLPreview](https://htmlpreview.github.io/?https://github.com/ffelipeao/aula_dev_web/blob/main/perfil-profissional/index.html)

Para visualizar seu próprio trabalho:

1. Confirme que a pasta `perfil-profissional` e todos os seus arquivos foram enviados ao GitHub.
2. Abra `perfil-profissional/index.html` dentro do repositório.
3. Copie o endereço exibido na barra do navegador.
4. Cole esse endereço depois de `https://htmlpreview.github.io/?`.
5. Abra o link completo em uma nova aba.

Sempre envie as alterações mais recentes ao GitHub antes de abrir o HTMLPreview. Se a versão antiga continuar aparecendo, aguarde alguns instantes e atualize a página. O repositório precisa ser público para que o serviço consiga acessar os arquivos.

> O HTMLPreview é adequado para visualizar este projeto diretamente a partir do GitHub. Ele não substitui um serviço completo de hospedagem e publicação de sites.

## 9. Personalize seu currículo

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

## 10. Desafios para praticar

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
- [ ] O projeto foi salvo em um repositório público no GitHub.
- [ ] A página pode ser visualizada pelo link do HTMLPreview.
- [ ] Não existem erros no console do navegador.
- [ ] O código está indentado e organizado.

## Resultado esperado

O resultado será um currículo web simples, elegante e responsivo. Mais importante do que copiar o exemplo é compreender a responsabilidade de cada tecnologia: o **HTML estrutura**, o **CSS apresenta** e o **JavaScript adiciona comportamento**.
