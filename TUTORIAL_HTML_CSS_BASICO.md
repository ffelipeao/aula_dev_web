# Tutorial básico de HTML e CSS

Neste tutorial, você aprenderá a criar uma página com títulos, textos, listas,
links, imagens, formulários e tabelas. No final, verá as três maneiras mais
comuns de adicionar CSS a uma página.

Não é necessário instalar nenhum programa especial. Você pode escrever o código
em um editor de texto e abrir o arquivo `.html` no navegador.

## 1. Estrutura básica de uma página HTML

Crie um arquivo chamado `index.html` e escreva:

```html
<!DOCTYPE html>
<!-- Informa ao navegador que usamos HTML5 -->

<html lang="pt-BR">
<!-- Início da página. lang indica que o conteúdo está em português -->

<head>
    <!-- Configurações da página que não aparecem no conteúdo -->
    <meta charset="UTF-8">
    <!-- Permite usar acentos e caracteres como ç -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Faz a página se adaptar à tela do celular -->

    <title>Minha primeira página</title>
    <!-- Texto mostrado na aba do navegador -->
</head>

<body>
    <!-- Tudo que aparece na página fica dentro de body -->
    <h1>Minha primeira página</h1>
    <p>Estou aprendendo HTML!</p>
</body>
</html>
```

As tags normalmente possuem abertura e fechamento. Por exemplo, `<p>` inicia
um parágrafo e `</p>` termina o parágrafo.

## 2. Títulos, parágrafos e links

```html
<!-- h1 é o título principal. Use apenas um título principal por página -->
<h1>Curso de Desenvolvimento Web</h1>

<!-- h2 é um subtítulo -->
<h2>Sobre o curso</h2>

<!-- p cria um parágrafo -->
<p>Neste curso aprenderemos HTML, CSS e JavaScript.</p>

<!-- strong dá importância ao texto e geralmente o deixa em negrito -->
<p>Esta parte é <strong>muito importante</strong>.</p>

<!-- a cria um link. href informa o endereço de destino -->
<a href="https://developer.mozilla.org/pt-BR/">Aprenda mais sobre desenvolvimento web</a>
```

## 3. Listas

Use `<ul>` quando a ordem não for importante e `<ol>` quando existir uma
sequência. Cada item é criado com `<li>`.

```html
<h2>Conteúdos do curso</h2>

<!-- ul cria uma lista com marcadores -->
<ul>
    <li>HTML</li>
    <li>CSS</li>
    <li>JavaScript</li>
</ul>

<h2>Passos para estudar</h2>

<!-- ol cria uma lista numerada -->
<ol>
    <li>Ler o conteúdo</li>
    <li>Digitar o exemplo</li>
    <li>Praticar</li>
</ol>
```

## 4. Imagem

```html
<!-- src informa onde está a imagem e alt descreve a imagem -->
<img src="imagens/foto.jpg" alt="Aluno estudando desenvolvimento web" width="300">
```

O atributo `alt` é importante para acessibilidade e também aparece quando a
imagem não pode ser carregada. O atributo `width` define a largura em pixels.

## 5. Formulário simples

Formulários recebem dados digitados pelo usuário. Este primeiro exemplo apenas
mostra os campos; para enviar ou guardar os dados, será necessário estudar uma
linguagem como PHP ou JavaScript depois.

```html
<h2>Cadastro</h2>

<!-- form agrupa os campos do formulário -->
<form>
    <!-- label explica o que deve ser digitado no campo -->
    <label for="nome">Nome:</label>

    <!-- input cria o campo. required torna o preenchimento obrigatório -->
    <input type="text" id="nome" name="nome" required>

    <br><br>
    <!-- br pula uma linha. Aqui foi usado apenas para manter o exemplo simples -->

    <label for="email">E-mail:</label>
    <!-- type email ajuda o navegador a verificar o endereço -->
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="curso">Curso:</label>
    <!-- select cria uma lista de opções -->
    <select id="curso" name="curso">
        <option value="html">HTML</option>
        <option value="css">CSS</option>
        <option value="javascript">JavaScript</option>
    </select>

    <br><br>

    <label for="mensagem">Mensagem:</label><br>
    <!-- textarea cria uma área para textos maiores -->
    <textarea id="mensagem" name="mensagem" rows="4" cols="30"></textarea>

    <br><br>

    <!-- button cria o botão de envio -->
    <button type="submit">Enviar</button>
</form>
```

O `for` de cada `<label>` deve ter o mesmo valor do `id` do campo correspondente.
Assim, o navegador entende qual texto pertence a qual campo.

## 6. Tabela simples

Use tabelas para dados organizados em linhas e colunas, e não para montar o
visual da página.

```html
<h2>Horários das aulas</h2>

<!-- table inicia a tabela -->
<table border="1">
    <!-- thead reúne os títulos das colunas -->
    <thead>
        <tr>
            <!-- th cria uma célula de título -->
            <th>Dia</th>
            <th>Conteúdo</th>
            <th>Horário</th>
        </tr>
    </thead>

    <!-- tbody reúne os dados da tabela -->
    <tbody>
        <!-- tr cria uma linha -->
        <tr>
            <!-- td cria uma célula comum -->
            <td>Segunda-feira</td>
            <td>HTML</td>
            <td>19h</td>
        </tr>
        <tr>
            <td>Quarta-feira</td>
            <td>CSS</td>
            <td>19h</td>
        </tr>
    </tbody>
</table>
```

O atributo `border="1"` deixa a borda visível rapidamente. Mais adiante, é
melhor controlar a aparência da tabela usando CSS.

## 7. Outros elementos úteis

```html
<!-- header representa o cabeçalho da página -->
<header>
    <h1>Meu site</h1>
</header>

<!-- nav agrupa links de navegação -->
<nav>
    <a href="#inicio">Início</a>
    <a href="#contato">Contato</a>
</nav>

<!-- main guarda o conteúdo principal -->
<main>
    <!-- section separa uma parte do conteúdo -->
    <section id="inicio">
        <h2>Bem-vindo</h2>
        <p>Este é o conteúdo principal.</p>
    </section>
</main>

<!-- footer representa o rodapé -->
<footer>
    <p>Desenvolvido durante a aula de HTML.</p>
</footer>
```

## 8. Três maneiras de adicionar CSS

CSS é a linguagem usada para mudar cores, tamanhos, espaçamentos e a aparência
dos elementos HTML.

### CSS inline

O CSS é escrito diretamente na tag usando o atributo `style`.

```html
<!-- color muda a cor; font-size muda o tamanho da letra -->
<h1 style="color: blue; font-size: 32px;">Título azul</h1>

<!-- background-color muda a cor de fundo -->
<p style="background-color: lightyellow;">Parágrafo com fundo amarelo.</p>
```

É rápido para testar, mas não é indicado para páginas grandes porque mistura o
conteúdo com a aparência e repete muito código.

### CSS interno, no mesmo arquivo

O CSS fica dentro da tag `<style>`, normalmente no `<head>` do HTML.

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CSS interno</title>

    <style>
        /* body representa toda a parte visível da página */
        body {
            font-family: Arial, sans-serif; /* Muda o tipo da letra */
            background-color: #f2f2f2;    /* Muda a cor do fundo */
        }

        /* Esta regra será aplicada a todos os títulos h1 */
        h1 {
            color: darkblue; /* Muda a cor do texto */
        }
    </style>
</head>
<body>
    <h1>Exemplo com CSS interno</h1>
</body>
</html>
```

Essa opção funciona bem em uma página pequena, mas o estilo não é compartilhado
automaticamente com outras páginas.

### CSS externo, em outro arquivo

Esta é a forma mais organizada e mais usada. Crie dois arquivos na mesma pasta:

```text
meu-projeto/
├── index.html
└── estilo.css
```

No arquivo `index.html`:

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CSS externo</title>

    <!-- link conecta o arquivo CSS ao HTML -->
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- class permite aplicar um estilo específico ao elemento -->
    <h1 class="titulo">Exemplo com CSS externo</h1>
    <p>O estilo está em outro arquivo.</p>
</body>
</html>
```

No arquivo `estilo.css`:

```css
/* Aplica estas configurações ao corpo da página */
body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
}

/* O ponto indica que titulo é o nome de uma classe */
.titulo {
    color: darkgreen;
    text-align: center; /* Centraliza o texto */
}
```

## 9. Como praticar

Na pasta `html-css-basico` deste projeto há exemplos prontos das três formas de
usar CSS. Abra cada arquivo `.html` no navegador e altere cores e textos para
observar o resultado.

Uma boa sequência de prática é:

1. Trocar os textos e as opções do formulário.
2. Adicionar uma nova linha à tabela.
3. Alterar as cores no CSS.
4. Criar um novo campo no formulário.
5. Montar uma página simples sobre um assunto de que você gosta.
