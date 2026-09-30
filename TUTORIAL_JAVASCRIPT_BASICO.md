# Tutorial básico de HTML e JavaScript

Neste tutorial, você aprenderá a criar uma página HTML e usar comandos básicos
de JavaScript. A página terá botões e campos para demonstrar mensagens,
variáveis, cálculos, decisões, repetições, funções, eventos e alterações no HTML.

Não é necessário instalar nenhum programa especial. Você pode escrever os
arquivos em um editor de texto e abrir o arquivo `.html` no navegador.

## 1. Criando a pasta e os arquivos

Crie uma pasta chamada `javascript-basico`. Dentro dela, crie dois arquivos:

```text
javascript-basico/
├── index.html
└── script.js
```

O arquivo `index.html` terá a estrutura e o conteúdo da página. O arquivo
`script.js` terá os comandos JavaScript.

## 2. Criando a página HTML

No arquivo `index.html`, escreva:

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

    <title>JavaScript básico</title>
    <!-- Texto mostrado na aba do navegador -->
</head>

<body>
    <!-- Tudo que aparece na página fica dentro de body -->
    <h1>Comandos básicos de JavaScript</h1>
    <p>Abra o console do navegador para acompanhar alguns exemplos.</p>

    <!-- Conecta o arquivo JavaScript à página -->
    <!-- Ele fica no final para o HTML ser carregado antes dos comandos -->
    <script src="script.js"></script>
</body>
</html>
```

Salve os dois arquivos na mesma pasta e abra `index.html` no navegador. Para
abrir o console, pressione `F12` e escolha a aba **Console**. Em alguns
computadores, pode ser necessário usar `Fn + F12`.

## 3. Exibindo mensagens

No arquivo `script.js`, experimente os comandos:

```javascript
// Mostra uma mensagem no console do navegador
console.log("A página foi carregada!");

// Mostra uma caixa de aviso na página
alert("Bem-vindo à aula de JavaScript!");
```

O texto entre aspas é chamado de *string*. O ponto e vírgula indica o final de
uma instrução. Em muitos casos ele é opcional, mas usá-lo ajuda a manter o
código organizado.

O `console.log()` é muito útil para estudar, testar valores e procurar erros.
O `alert()` interrompe a navegação até a pessoa fechar a mensagem, por isso deve
ser usado com moderação.

## 4. Comentários

Comentários explicam o código e são ignorados pelo navegador.

```javascript
// Este é um comentário de uma linha

/*
Este é um comentário
com mais de uma linha.
*/
```

## 5. Variáveis e constantes

Variáveis guardam dados que serão usados pelo programa.

```javascript
// let cria uma variável cujo valor pode mudar
let nome = "Ana";
let idade = 18;

// O valor da variável idade pode ser alterado
idade = 19;

// const cria uma constante: seu valor não deve ser reatribuído
const nomeDoCurso = "Desenvolvimento Web";

// A vírgula permite mostrar vários valores no console
console.log("Nome:", nome);
console.log("Idade:", idade);
console.log("Curso:", nomeDoCurso);
```

Prefira `const` quando o valor não precisar ser reatribuído e use `let` quando
ele precisar mudar. Evite `var` em códigos novos, pois `let` e `const` possuem
regras de escopo mais claras.

## 6. Tipos básicos de dados

```javascript
// string: texto
const aluno = "Carlos";

// number: número inteiro ou decimal
const nota = 8.5;

// boolean: valor verdadeiro ou falso
const aprovado = true;

// null: ausência de um valor definida de propósito
const telefone = null;

// undefined: variável que ainda não recebeu um valor
let endereco;

// typeof informa o tipo de um valor
console.log(typeof aluno);    // string
console.log(typeof nota);     // number
console.log(typeof aprovado); // boolean
console.log(endereco);        // undefined
```

## 7. Operadores e cálculos

```javascript
const numero1 = 10;
const numero2 = 4;

console.log(numero1 + numero2); // Soma: 14
console.log(numero1 - numero2); // Subtração: 6
console.log(numero1 * numero2); // Multiplicação: 40
console.log(numero1 / numero2); // Divisão: 2.5
console.log(numero1 % numero2); // Resto da divisão: 2
console.log(numero1 ** 2);      // Potência: 100
```

O operador `+` também junta textos, operação chamada concatenação.

```javascript
const nome = "Marina";
const mensagem = "Olá, " + nome + "!";
console.log(mensagem);

// A crase permite inserir valores com ${...} dentro do texto
const idade = 20;
console.log(`${nome} tem ${idade} anos.`);
```

## 8. Comparações e operadores lógicos

Comparações produzem o valor `true` ou `false`.

```javascript
const idade = 18;

console.log(idade > 17);   // true: maior que
console.log(idade < 18);   // false: menor que
console.log(idade >= 18);  // true: maior ou igual
console.log(idade <= 20);  // true: menor ou igual
console.log(idade === 18); // true: valor e tipo são iguais
console.log(idade !== 18); // false: valor ou tipo são diferentes

// && significa E: as duas condições precisam ser verdadeiras
console.log(idade >= 18 && idade <= 60);

// || significa OU: pelo menos uma condição precisa ser verdadeira
console.log(idade < 12 || idade >= 60);

// ! significa NÃO e inverte o valor lógico
console.log(!(idade >= 18));
```

Prefira `===` e `!==`, pois eles comparam tanto o valor quanto o tipo do dado.

## 9. Tomando decisões com if e else

O programa pode executar comandos diferentes de acordo com uma condição.

```javascript
const nota = 7;

// Se a condição for verdadeira, executa o primeiro bloco
if (nota >= 7) {
    console.log("Aluno aprovado!");
} else if (nota >= 5) {
    // Executa se a primeira condição for falsa e esta for verdadeira
    console.log("Aluno em recuperação.");
} else {
    // Executa quando nenhuma condição anterior for verdadeira
    console.log("Aluno reprovado.");
}
```

As chaves `{ }` delimitam o bloco de comandos de cada condição.

## 10. Repetições com for e while

Uma estrutura de repetição executa o mesmo bloco várias vezes.

```javascript
// Começa em 1, continua enquanto i for menor ou igual a 5
// e acrescenta 1 ao valor de i depois de cada repetição
for (let i = 1; i <= 5; i++) {
    console.log(`Repetição número ${i}`);
}
```

O `while` repete enquanto uma condição for verdadeira:

```javascript
let contador = 1;

while (contador <= 3) {
    console.log(`Contador: ${contador}`);

    // Aumenta o contador e evita uma repetição infinita
    contador++;
}
```

## 11. Criando funções

Uma função reúne comandos que podem ser utilizados várias vezes.

```javascript
// nome é um parâmetro recebido pela função
function criarSaudacao(nome) {
    // return devolve o resultado para quem chamou a função
    return `Olá, ${nome}!`;
}

// Chama a função e guarda o resultado
const saudacao = criarSaudacao("Beatriz");
console.log(saudacao);

// Esta função recebe dois números e devolve a soma
function somar(numero1, numero2) {
    return numero1 + numero2;
}

console.log(somar(5, 3)); // Mostra 8
```

## 12. Listas com arrays

Um *array* guarda vários valores em uma única variável. As posições começam em
zero.

```javascript
const tecnologias = ["HTML", "CSS", "JavaScript"];

console.log(tecnologias[0]); // HTML
console.log(tecnologias[2]); // JavaScript

// Adiciona um item no final do array
tecnologias.push("PHP");

// length informa a quantidade de itens
console.log(tecnologias.length); // 4

// Percorre todos os itens do array
for (const tecnologia of tecnologias) {
    console.log(tecnologia);
}
```

## 13. Objetos

Um objeto reúne informações relacionadas usando pares de propriedade e valor.

```javascript
const estudante = {
    nome: "Lucas",
    idade: 21,
    matriculado: true
};

// O ponto permite acessar uma propriedade
console.log(estudante.nome);
console.log(estudante.idade);

// Altera uma propriedade existente
estudante.idade = 22;
```

## 14. Selecionando e alterando elementos da página

JavaScript pode localizar um elemento HTML e alterar seu conteúdo. Acrescente
este código dentro de `<body>`, antes da tag `<script>` do `index.html`:

```html
<h2 id="titulo-mensagem">Mensagem original</h2>
<p id="texto-mensagem">Clique no botão para alterar este texto.</p>
<button id="botao-alterar" type="button">Alterar mensagem</button>
```

Depois, acrescente ao final de `script.js`:

```javascript
// Localiza os elementos pelo valor do atributo id
const titulo = document.querySelector("#titulo-mensagem");
const texto = document.querySelector("#texto-mensagem");
const botaoAlterar = document.querySelector("#botao-alterar");

// Altera imediatamente o texto do título
titulo.textContent = "JavaScript alterou o título!";

// A função será executada quando o botão receber um clique
botaoAlterar.addEventListener("click", function () {
    texto.textContent = "O botão foi clicado!";
    texto.style.color = "blue"; // Altera a cor usando uma propriedade CSS
});
```

O `document.querySelector()` seleciona o primeiro elemento que corresponde ao
seletor informado. O símbolo `#` indica que estamos procurando um `id`.

## 15. Lendo um campo e fazendo um cálculo

Acrescente esta seção ao `index.html`, antes da tag `<script>`:

```html
<section>
    <h2>Calculadora de dobro</h2>

    <!-- O label identifica o campo para o usuário e para tecnologias assistivas -->
    <label for="numero">Digite um número:</label>
    <input type="number" id="numero">

    <!-- type button evita que o botão tente enviar um formulário -->
    <button id="botao-calcular" type="button">Calcular</button>

    <!-- O resultado será inserido neste parágrafo -->
    <p id="resultado"></p>
</section>
```

Acrescente ao final de `script.js`:

```javascript
const campoNumero = document.querySelector("#numero");
const botaoCalcular = document.querySelector("#botao-calcular");
const resultado = document.querySelector("#resultado");

botaoCalcular.addEventListener("click", function () {
    // value lê o conteúdo do campo. Number converte o texto em número
    const numero = Number(campoNumero.value);

    // Um campo vazio precisa ser verificado antes de fazer o cálculo
    if (campoNumero.value === "") {
        resultado.textContent = "Digite um número.";
        return; // Encerra a função neste ponto
    }

    const dobro = numero * 2;
    resultado.textContent = `O dobro de ${numero} é ${dobro}.`;
});
```

O valor de um campo é lido como texto, mesmo quando usamos
`type="number"`. Por isso, usamos `Number()` antes de realizar o cálculo.

## 16. Exemplo completo da página

Depois de praticar cada parte separadamente, substitua o conteúdo dos arquivos
pelos exemplos a seguir para montar uma página completa.

No arquivo `index.html`:

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratório de JavaScript</title>
</head>
<body>
    <header>
        <h1>Laboratório de JavaScript</h1>
        <p>Use os controles para testar os comandos.</p>
    </header>

    <main>
        <section>
            <h2>Saudação</h2>

            <label for="nome">Digite seu nome:</label>
            <input type="text" id="nome">
            <button id="botao-saudacao" type="button">Saudar</button>

            <!-- aria-live faz leitores de tela anunciarem a mudança -->
            <p id="saida-saudacao" aria-live="polite"></p>
        </section>

        <section>
            <h2>Verificar nota</h2>

            <label for="nota">Digite uma nota de 0 a 10:</label>
            <input type="number" id="nota" min="0" max="10" step="0.1">
            <button id="botao-nota" type="button">Verificar</button>

            <p id="saida-nota" aria-live="polite"></p>
        </section>

        <section>
            <h2>Lista de conteúdos</h2>
            <button id="botao-listar" type="button">Mostrar conteúdos</button>
            <ul id="lista-conteudos"></ul>
        </section>
    </main>

    <!-- O JavaScript é carregado depois dos elementos usados pelo programa -->
    <script src="script.js"></script>
</body>
</html>
```

No arquivo `script.js`:

```javascript
// Exemplo 1: ler um texto e mostrar uma saudação
const campoNome = document.querySelector("#nome");
const botaoSaudacao = document.querySelector("#botao-saudacao");
const saidaSaudacao = document.querySelector("#saida-saudacao");

botaoSaudacao.addEventListener("click", function () {
    // trim remove espaços no início e no final do texto
    const nome = campoNome.value.trim();

    if (nome === "") {
        saidaSaudacao.textContent = "Por favor, digite seu nome.";
    } else {
        saidaSaudacao.textContent = `Olá, ${nome}! Bem-vindo à aula.`;
    }
});

// Exemplo 2: ler um número e usar estruturas de decisão
const campoNota = document.querySelector("#nota");
const botaoNota = document.querySelector("#botao-nota");
const saidaNota = document.querySelector("#saida-nota");

botaoNota.addEventListener("click", function () {
    const nota = Number(campoNota.value);

    if (campoNota.value === "" || nota < 0 || nota > 10) {
        saidaNota.textContent = "Digite uma nota válida entre 0 e 10.";
    } else if (nota >= 7) {
        saidaNota.textContent = "Situação: aprovado.";
    } else if (nota >= 5) {
        saidaNota.textContent = "Situação: recuperação.";
    } else {
        saidaNota.textContent = "Situação: reprovado.";
    }
});

// Exemplo 3: percorrer um array e criar elementos HTML
const conteudos = ["Variáveis", "Condições", "Repetições", "Funções", "Eventos"];
const botaoListar = document.querySelector("#botao-listar");
const listaConteudos = document.querySelector("#lista-conteudos");

botaoListar.addEventListener("click", function () {
    // Limpa a lista para não repetir os itens a cada clique
    listaConteudos.innerHTML = "";

    for (const conteudo of conteudos) {
        // Cria um novo elemento li na memória
        const item = document.createElement("li");
        item.textContent = conteudo;

        // Insere o novo li dentro da lista ul
        listaConteudos.appendChild(item);
    }
});
```

Salve os arquivos, atualize a página no navegador e teste cada botão. Mantenha o
console aberto para observar possíveis mensagens de erro.

## 17. Como identificar erros

Quando algo não funcionar:

1. Salve todos os arquivos e atualize a página.
2. Abra o console do navegador com `F12`.
3. Leia a mensagem e observe o nome do arquivo e o número da linha.
4. Confira parênteses, chaves, aspas e pontos e vírgulas.
5. Verifique se os valores de `id` no HTML são iguais aos seletores usados no
   JavaScript.
6. Use `console.log()` para conferir os valores durante a execução.

JavaScript diferencia letras maiúsculas de minúsculas. Portanto, `nome`,
`Nome` e `NOME` são identificadores diferentes.

## 18. Como praticar

Uma boa sequência de prática é:

1. Trocar os textos exibidos pela página.
2. Criar dois campos e mostrar a soma dos valores.
3. Informar uma idade e mostrar se a pessoa é maior de idade.
4. Criar um botão que altere a cor de um título.
5. Adicionar novos conteúdos ao array da lista.
6. Criar uma tabuada usando um laço `for`.
7. Criar um botão para limpar todos os resultados.

### Desafio final

Crie uma calculadora de média com dois campos de nota e um botão. Ao clicar no
botão, a página deve calcular a média e informar se o estudante foi aprovado,
ficou em recuperação ou foi reprovado. Valide campos vazios e notas fora do
intervalo de 0 a 10.
