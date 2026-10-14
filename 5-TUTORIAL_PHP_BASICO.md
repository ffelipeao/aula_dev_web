# Tutorial básico de HTML e PHP

Neste tutorial, você aprenderá a usar os principais comandos de PHP com
exemplos de mensagens, variáveis, cálculos, decisões, repetições, funções,
listas e formulários. Ao final, montará uma página que verifica a nota de um
estudante, sem precisar de banco de dados.

## 1. Como o PHP funciona

PHP é uma linguagem executada no **servidor**. Quando uma pessoa acessa uma
página PHP, o servidor executa os comandos e envia o HTML resultante ao
navegador.

No tutorial de JavaScript, os comandos eram executados pelo navegador. Aqui,
o PHP prepara a resposta antes de ela aparecer na tela.

```text
Navegador solicita a página → Servidor executa o PHP → Navegador recebe HTML
```

Por isso, abrir um arquivo `.php` com dois cliques não executa seus comandos.
Precisamos acessar a página por um servidor que tenha PHP.

## 2. Preparando a pasta e executando o servidor

Use um editor de texto e escolha uma das opções abaixo para executar PHP.

### Opção A: usando o XAMPP já instalado

O XAMPP reúne ferramentas para executar páginas localmente. Para este
tutorial, usaremos o **Apache**, que atende às solicitações do navegador e
executa o PHP disponível no XAMPP.

1. Abra o painel do XAMPP. No Windows, ele se chama **XAMPP Control Panel**.
2. Na linha **Apache**, clique em **Start** e aguarde a indicação de que está
   em execução. No macOS, abra o gerenciador do XAMPP e inicie **Apache Web
   Server** na aba **Manage Servers**.
3. Localize a pasta `htdocs` dentro da instalação do XAMPP. Os caminhos
   habituais são:

   - **Windows:** `C:\xampp\htdocs`
   - **macOS:** `/Applications/XAMPP/xamppfiles/htdocs`
   - **Linux:** `/opt/lampp/htdocs`

4. Dentro de `htdocs`, crie a pasta `php-basico`.
5. No editor, crie o arquivo `index.php` dentro dessa pasta. Confira se ele
   não foi salvo como `index.php.txt`.
6. Escreva o primeiro programa da seção 3 e salve o arquivo.
7. Abra **http://localhost/php-basico/** no navegador.

No Windows, por exemplo, a organização será:

```text
C:\xampp\htdocs\
└── php-basico\
    └── index.php
```

Também é possível acessar **http://localhost/php-basico/index.php**. O nome
`index.php` permite que o Apache encontre a página inicial ao abrir a pasta.
Sempre que alterar o código, salve o arquivo e atualize a página.

Não é necessário iniciar o MySQL para os exemplos deste tutorial, pois não
usaremos banco de dados. Com o Apache do XAMPP em execução, também não é
necessário executar `php -S` no terminal.

Se a página não abrir, confira se o Apache está em execução e se a pasta está
realmente em `htdocs`. Se o Apache estiver configurado para outra porta, como
`8080`, use **http://localhost:8080/php-basico/**. Se ele não iniciar, consulte
a mensagem no painel: a porta usada pode estar ocupada por outro programa.

Ao terminar a atividade, você pode clicar em **Stop** para encerrar o Apache.

### Opção B: usando o servidor do próprio PHP

Se você já tem o comando `php` disponível no terminal, pode usar seu servidor
de desenvolvimento. Nesta opção, a pasta do projeto pode ficar em um local
de sua escolha:

1. Crie uma pasta chamada `php-basico`.
2. Dentro dela, crie um arquivo chamado `index.php`.
3. Abra o terminal nessa pasta.
4. Confira se o PHP está disponível:

```bash
php -v
```

Se o terminal informar que o comando não foi encontrado, será necessário
instalar ou configurar o PHP antes de continuar.

Inicie o servidor:

```bash
php -S localhost:8000
```

Mantenha o terminal aberto e acesse **http://localhost:8000/** no navegador.
Para encerrar o servidor, pressione `Ctrl + C` no terminal. Esse servidor é
destinado ao desenvolvimento local.

```text
php-basico/
└── index.php
```

## 3. Primeiro programa e mensagens com echo

Escreva no arquivo `index.php`:

```php
<?php
// echo envia um texto para a página
echo "Olá, mundo!";
echo "<br>"; // A tag HTML br cria uma quebra de linha no navegador
echo "Bem-vindo à aula de PHP!";
```

Salve e atualize a página. Você verá duas mensagens, uma em cada linha.

O marcador `<?php` inicia o código PHP. O ponto e vírgula `;` encerra uma
instrução. Textos ficam entre aspas e são chamados de *strings*.

Nos próximos exemplos, substitua o conteúdo de `index.php` pelo trecho que
quiser testar. Cada exemplo começa com `<?php`. Se reunir vários trechos no
mesmo bloco PHP, use esse marcador apenas uma vez no início.

Quando o arquivo contém somente PHP, podemos deixar de escrever o fechamento
`?>`. Quando voltamos a escrever HTML, usamos esse fechamento, como veremos
mais adiante.

## 4. Comentários

Comentários explicam o código e não são executados.

```php
<?php
// Comentário de uma linha

/*
Comentário com
mais de uma linha.
*/

echo "Os comentários não aparecem na página.";
```

## 5. Variáveis e constantes

Variáveis guardam valores. Em PHP, seus nomes começam com `$`.

```php
<?php
$nome = "Ana"; // Guarda um texto
$idade = 18;   // Guarda um número

$idade = 19;   // Altera o valor da variável

// Uma constante guarda um valor que não será alterado
const CURSO = "Desenvolvimento Web";

echo "Nome: $nome<br>";
echo "Idade: $idade<br>";
echo "Curso: " . CURSO;
```

Não usamos `let`, `var` ou `const` para declarar variáveis comuns em PHP:
basta escrever `$nome = valor;`. Já a constante acima usa `const` e é acessada
sem `$`.

Os nomes de variáveis diferenciam maiúsculas de minúsculas: `$nome` e `$Nome`
são variáveis diferentes. Prefira nomes claros, como `$notaFinal`.

## 6. Tipos básicos e var_dump

```php
<?php
$aluno = "Carlos";  // string: texto
$idade = 20;        // int: número inteiro
$nota = 8.5;        // float: número decimal, escrito com ponto
$aprovado = true;   // bool: verdadeiro ou falso
$telefone = null;   // null: ausência de valor

// Mostra o tipo e o valor de cada variável
echo "<pre>"; // Preserva a formatação da saída no navegador
var_dump($aluno, $idade, $nota, $aprovado, $telefone);
echo "</pre>";
```

O `var_dump()` ajuda a conferir valores durante o estudo. Por exemplo,
`var_dump($idade)` mostra `int(20)`. Para valores lógicos, ele mostra
`bool(true)` ou `bool(false)`.

## 7. Operadores e cálculos

```php
<?php
$numero1 = 10;
$numero2 = 4;

echo ($numero1 + $numero2) . "<br>"; // Soma: 14
echo ($numero1 - $numero2) . "<br>"; // Subtração: 6
echo ($numero1 * $numero2) . "<br>"; // Multiplicação: 40
echo ($numero1 / $numero2) . "<br>"; // Divisão: 2.5
echo ($numero1 % $numero2) . "<br>"; // Resto da divisão: 2
echo ($numero1 ** 2) . "<br>";      // Potência: 100

$media = (8 + 6) / 2;
echo "Média: $media"; // Média: 7
```

Os parênteses deixam clara a ordem dos cálculos. Não divida um número por zero.

## 8. Juntando textos e inserindo variáveis

Em PHP, o ponto `.` junta textos. Essa operação se chama **concatenação**.

```php
<?php
$nome = "Marina";
$idade = 20;

echo "Olá, " . $nome . "!<br>";

// Aspas duplas permitem inserir variáveis diretamente no texto
echo "$nome tem $idade anos.<br>";

// Aspas simples mantêm o texto literal
echo 'A variável se chama $nome.';
```

O último comando mostra exatamente `A variável se chama $nome.`. Diferente
do JavaScript, o operador usado para juntar textos em PHP é `.`.

## 9. Comparações e operadores lógicos

Comparações produzem `true` ou `false`. Use `var_dump()` para visualizar esses
resultados com clareza.

```php
<?php
$idade = 18;

echo "<pre>";
var_dump($idade > 17);   // true: maior que
var_dump($idade < 18);   // false: menor que
var_dump($idade >= 18);  // true: maior ou igual
var_dump($idade <= 20);  // true: menor ou igual
var_dump($idade === 18); // true: valor e tipo iguais
var_dump($idade !== 18); // false: valor ou tipo diferentes

var_dump(18 === "18"); // false: um inteiro e um texto

// && significa E: as duas condições precisam ser verdadeiras
var_dump($idade >= 18 && $idade <= 60);

// || significa OU: pelo menos uma condição precisa ser verdadeira
var_dump($idade < 12 || $idade >= 60);

// ! significa NÃO: inverte o valor lógico
var_dump(!($idade >= 18));
echo "</pre>";
```

Use `=` para atribuir um valor e `===` para comparar valor e tipo. O operador
`==` também compara, mas pode converter tipos; prefira `===` quando quiser
evitar essa conversão.

## 10. Decisões com if, elseif e else

```php
<?php
$nota = 7;

if ($nota >= 7) {
    // Executado se a primeira condição for verdadeira
    echo "Aluno aprovado!";
} elseif ($nota >= 5) {
    // Executado se a primeira for falsa e esta for verdadeira
    echo "Aluno em recuperação.";
} else {
    // Executado quando as condições anteriores forem falsas
    echo "Aluno reprovado.";
}
```

As chaves `{ }` delimitam cada bloco. Troque a nota por `6` e depois por `4`
para observar os três resultados.

## 11. Repetições com for e while

O `for` é útil quando temos um contador:

```php
<?php
// Começa em 1, continua até 5 e aumenta o contador a cada repetição
for ($i = 1; $i <= 5; $i++) {
    echo "Repetição número $i<br>";
}
```

O `while` repete enquanto uma condição for verdadeira:

```php
<?php
$contador = 1;

while ($contador <= 3) {
    echo "Contador: $contador<br>";
    $contador++; // Soma 1 e permite que a repetição termine
}
```

Sem atualizar o contador, a condição continuaria verdadeira e o programa
ficaria repetindo sem parar.

## 12. Listas com arrays e foreach

Um *array* guarda vários valores. Neste exemplo, as posições começam em zero.

```php
<?php
$tecnologias = ["HTML", "CSS", "JavaScript"];

echo $tecnologias[0] . "<br>"; // HTML
echo $tecnologias[2] . "<br>"; // JavaScript

$tecnologias[] = "PHP"; // Adiciona um item no final
echo "Quantidade: " . count($tecnologias) . "<br>"; // 4

// A cada repetição, tecnologia recebe um item da lista
foreach ($tecnologias as $tecnologia) {
    echo $tecnologia . "<br>";
}
```

O `foreach` facilita percorrer todos os itens sem controlar uma posição
manualmente.

## 13. Arrays associativos

Também podemos usar nomes para identificar os valores. Esses nomes são
chamados de **chaves**.

```php
<?php
$estudante = [
    "nome" => "Lucas",
    "idade" => 21,
    "matriculado" => true
];

echo $estudante["nome"] . "<br>"; // Lucas
echo $estudante["idade"] . "<br>"; // 21

$estudante["idade"] = 22; // Altera o valor associado à chave idade
echo $estudante["idade"]; // 22
```

O símbolo `=>` associa cada chave ao seu valor. Esse recurso ajuda a reunir
informações de um estudante, produto ou outro registro.

## 14. Criando funções

Uma função reúne comandos que podem ser reutilizados.

```php
<?php
// nome é um parâmetro: um valor recebido pela função
function criarSaudacao($nome) {
    return "Olá, $nome!"; // Devolve o resultado para quem chamou
}

function somar($numero1, $numero2) {
    return $numero1 + $numero2;
}

$mensagem = criarSaudacao("Beatriz");
echo $mensagem . "<br>"; // Olá, Beatriz!
echo somar(5, 3);       // 8
```

O `return` devolve um valor e encerra a execução da função naquele ponto.
Ele não mostra o resultado na página sozinho: usamos `echo` para isso.

## 15. Misturando HTML e PHP

Substitua o conteúdo de `index.php` por esta página:

```php
<?php
$curso = "Desenvolvimento Web";
$conteudos = ["Variáveis", "Condições", "Repetições", "Funções"];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP básico</title>
</head>
<body>
    <h1><?php echo $curso; ?></h1>
    <p>Conteúdos da aula:</p>
    <ul>
        <?php
        foreach ($conteudos as $conteudo) {
            echo "<li>$conteudo</li>";
        }
        ?>
    </ul>
</body>
</html>
```

O PHP gera o título e os itens da lista. Ao usar **Exibir código-fonte** no
navegador, você verá o HTML produzido, sem os comandos PHP.

Nos exemplos acima, os textos foram definidos por nós. Quando o texto vier de
um formulário, usaremos `htmlspecialchars()` antes de colocá-lo no HTML.

## 16. Recebendo dados de um formulário

Um formulário envia os valores dos campos ao servidor. Observe os principais
elementos deste exemplo, que será usado na página completa:

```html
<form action="index.php" method="post">
    <label for="nome">Digite seu nome:</label>
    <input type="text" id="nome" name="nome" required>
    <button type="submit">Enviar</button>
</form>
```

- `action="index.php"` indica o arquivo que receberá os dados.
- `method="post"` envia os dados no corpo da requisição.
- `name="nome"` identifica o valor que o PHP receberá.
- `id="nome"` conecta o campo ao `label`.
- `required` pede ao navegador que exija o preenchimento.
- `type="submit"` faz o botão enviar o formulário.

No PHP, podemos ler esse campo assim:

```php
<?php
$nome = $_POST["nome"] ?? "";

// Confere se recebemos um texto antes de utilizá-lo
if (is_string($nome)) {
    $nome = trim($nome); // Remove espaços no início e no final
    echo htmlspecialchars($nome, ENT_QUOTES, "UTF-8");
}
```

`$_POST` é um array disponibilizado pelo PHP com os dados enviados por POST.
O operador `??` fornece um valor padrão quando a chave não existe ou vale
`null`, como no primeiro acesso à página.

O `htmlspecialchars()` faz caracteres como `<` e `>` aparecerem como texto,
evitando que o conteúdo digitado seja interpretado como HTML.

Também existe o método `get`, cujos dados aparecem na URL e são lidos por
`$_GET`. Por exemplo, em `index.php?nome=Ana`, `$_GET["nome"]` contém `Ana`.
POST não criptografa os dados; a proteção do transporte depende de HTTPS.

## 17. Exemplo completo: verificar a nota de um estudante

Agora substitua todo o conteúdo de `index.php` pelo código abaixo. Ele reúne
formulário, função, validação, decisão e lista.

```php
<?php
$mensagem = "Preencha o formulário para verificar sua nota.";
$conteudos = ["Variáveis", "Condições", "Repetições", "Funções", "Formulários"];

function verificarSituacao($nota) {
    if ($nota >= 7) {
        return "aprovado";
    } elseif ($nota >= 5) {
        return "em recuperação";
    } else {
        return "reprovado";
    }
}

// No primeiro acesso, o método é GET. Ao enviar o formulário, é POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"] ?? "";
    $notaInformada = $_POST["nota"] ?? "";

    // Campos comuns chegam como texto. Conferimos isso antes de tratá-los.
    if (!is_string($nome) || !is_string($notaInformada)) {
        $mensagem = "Dados inválidos. Preencha o formulário novamente.";
    } else {
        $nome = trim($nome);
        $notaInformada = trim($notaInformada);

        // Aceita tanto 7,5 quanto 7.5 como número decimal
        $notaInformada = str_replace(",", ".", $notaInformada);

        if ($nome === "") {
            $mensagem = "Por favor, digite seu nome.";
        } elseif ($notaInformada === "" || !is_numeric($notaInformada)) {
            $mensagem = "Digite uma nota numérica entre 0 e 10.";
        } else {
            // Converte o texto em número somente depois da verificação
            $nota = (float) $notaInformada;

            if ($nota < 0 || $nota > 10) {
                $mensagem = "A nota deve estar entre 0 e 10.";
            } else {
                $situacao = verificarSituacao($nota);
                $mensagem = "Olá, $nome! Sua nota é $nota. Situação: $situacao.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratório de PHP</title>
</head>
<body>
    <h1>Laboratório de PHP</h1>

    <h2>Verificar nota</h2>
    <form action="index.php" method="post">
        <p>
            <label for="nome">Nome do estudante:</label>
            <input type="text" id="nome" name="nome" required>
        </p>
        <p>
            <label for="nota">Nota de 0 a 10:</label>
            <input type="number" id="nota" name="nota"
                   min="0" max="10" step="0.1" required>
        </p>
        <button type="submit">Verificar</button>
    </form>

    <!-- Exibe a mensagem como texto, incluindo o nome enviado -->
    <p><?php echo htmlspecialchars($mensagem, ENT_QUOTES, "UTF-8"); ?></p>

    <h2>Conteúdos estudados</h2>
    <ul>
        <?php
        foreach ($conteudos as $conteudo) {
            echo "<li>$conteudo</li>";
        }
        ?>
    </ul>
</body>
</html>
```

Salve, acesse a página pelo servidor e teste com seu nome e diferentes notas:

| Nota | Resultado esperado |
|---|---|
| 8 | Aprovado |
| 7 | Aprovado |
| 6 | Em recuperação |
| 5 | Em recuperação |
| 4 | Reprovado |
| 0 | Reprovado: zero é uma nota válida |

Ao enviar, o navegador faz uma nova requisição e carrega a resposta gerada
pelo PHP. Os campos ficam vazios novamente porque não preenchemos seus
atributos `value` com os dados recebidos. Os dados não são salvos.

O navegador já verifica `required`, `min` e `max`, mas essas verificações
podem ser contornadas. Por isso, o PHP também confere os dados recebidos.
`is_numeric()` verifica se o texto representa um número antes da conversão.

## 18. Como identificar erros

Quando algo não funcionar:

1. Salve o arquivo e atualize a página.
2. Confira se está acessando `http://localhost:8000/` ou o endereço do XAMPP.
3. Leia as mensagens no terminal do servidor ou no registro de erros do PHP.
4. Confira o arquivo e a linha indicados pela mensagem.
5. Verifique `$`, aspas, parênteses, chaves e pontos e vírgulas.
6. Confira se o `name` do campo é igual à chave usada em `$_POST`.
7. Use `var_dump()` temporariamente para observar o valor de uma variável.

Também é possível verificar a sintaxe pelo terminal, dentro da pasta:

```bash
php -l index.php
```

Esse comando procura erros de sintaxe, mas não substitui testar o programa.
O console JavaScript do navegador não mostra os erros internos do PHP.

## 19. Resumo dos principais recursos

| Recurso | Para que serve | Exemplo |
|---|---|---|
| `echo` | Exibir conteúdo | `echo "Olá!";` |
| `$variavel` | Guardar um valor | `$idade = 20;` |
| `const` | Definir uma constante | `const CURSO = "Web";` |
| `.` | Juntar textos | `"Olá, " . $nome` |
| `if / elseif / else` | Tomar decisões | `if ($nota >= 7) { ... }` |
| `for / while` | Repetir comandos | `while ($contador <= 3) { ... }` |
| `foreach` | Percorrer um array | `foreach ($lista as $item) { ... }` |
| `function / return` | Criar uma função e devolver um resultado | `return $a + $b;` |
| `count()` | Contar itens | `count($lista)` |
| `trim()` | Remover espaços das extremidades | `trim($nome)` |
| `is_numeric()` | Verificar se um valor representa um número | `is_numeric($nota)` |
| `$_POST / $_GET` | Ler dados enviados na requisição | `$_POST["nome"] ?? ""` |
| `htmlspecialchars()` | Preparar texto para exibição no HTML | `htmlspecialchars($nome, ENT_QUOTES, "UTF-8")` |
| `var_dump()` | Inspecionar tipo e valor | `var_dump($nota)` |

## 20. Como praticar

1. Troque as mensagens exibidas pelo programa.
2. Crie duas variáveis numéricas e mostre sua soma.
3. Informe uma idade em uma variável e mostre se a pessoa é maior de idade.
4. Gere a tabuada de um número usando `for`.
5. Adicione conteúdos ao array e observe a lista na página.
6. Crie uma função que receba um número e devolva seu dobro.
7. Acrescente um campo de turma ao formulário e mostre a turma no resultado.

### Desafio final

Adapte o formulário para receber **duas notas**. Calcule a média com
`($nota1 + $nota2) / 2` e use a função `verificarSituacao()` para informar o
resultado. Valide o nome, campos vazios, valores não numéricos e notas fora do
intervalo de 0 a 10. Mostre o resultado usando `htmlspecialchars()`.
