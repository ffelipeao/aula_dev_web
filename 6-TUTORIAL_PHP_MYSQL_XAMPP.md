# Tutorial de PHP e MySQL com XAMPP: cadastro de produtos

Neste tutorial, você construirá um sistema de cadastro de produtos com telas
para **listar, cadastrar, editar e excluir**. Os dados serão gravados no banco
e continuarão disponíveis depois de fechar o navegador.

A proposta é continuar o [tutorial básico de PHP](5-TUTORIAL_PHP_BASICO.md) e
transformar a ideia do cadastro feito com HTML e JavaScript em um sistema com
armazenamento no servidor. Usaremos PHP e SQL diretamente, sem framework.

Cada arquivo tem seu código completo abaixo: crie o arquivo, copie o bloco
inteiro, salve e siga os testes. Os mesmos arquivos estão disponíveis na pasta
[php-mysql-cadastro](php-mysql-cadastro), caso prefira copiá-la para o XAMPP.

## 1. O que vamos construir

O sistema trabalha com produtos que possuem ID, nome, preço e quantidade.
As quatro operações principais são chamadas de **CRUD**:

| Operação | Significado | Comando SQL | Tela ou arquivo |
|---|---|---|---|
| Create | Cadastrar | `INSERT` | `cadastro.php` → `salvar.php` |
| Read | Consultar/listar | `SELECT` | `index.php` |
| Update | Editar | `UPDATE` | `cadastro.php?id=1` → `salvar.php` |
| Delete | Excluir | `DELETE` | `excluir.php?id=1` → confirmação por POST |

```text
Navegador envia o formulário
          ↓
Apache executa o PHP
          ↓
PHP consulta ou altera o banco de dados
          ↓
PHP gera a resposta e o navegador mostra a página
```

O **PHP** executa as regras. O **banco de dados** guarda os registros.
O **SQL** é a linguagem dos comandos enviados ao banco. O **phpMyAdmin** é
uma ferramenta para administrar o banco pelo navegador.

O XAMPP fornece MariaDB no lugar do MySQL, embora o botão do painel possa se
chamar **MySQL**. Os comandos e o driver `mysql` usados neste tutorial funcionam
com ambos. Veja a [documentação do XAMPP](https://www.apachefriends.org/faq_windows.html).

## 2. Iniciando o XAMPP

Este guia considera o XAMPP já instalado, com PHP 8 ou superior.

1. Abra o **XAMPP Control Panel**.
2. Clique em **Start** na linha **Apache**.
3. Clique em **Start** na linha **MySQL**.
4. Aguarde os dois serviços indicarem que estão em execução.
5. Abra **http://localhost/** para conferir o Apache.
6. Abra **http://localhost/phpmyadmin/** para administrar o banco.

No macOS, use a aba **Manage Servers** do gerenciador do XAMPP e inicie
**Apache Web Server** e o serviço do banco. No Linux, inicie os serviços pelo
gerenciador da instalação.

Neste projeto, precisamos dos dois serviços: Apache para as páginas PHP e
MySQL/MariaDB para os registros. Não é necessário executar `php -S`.

Se seu Apache usa a porta `8080`, os endereços começam com
`http://localhost:8080/`. A porta do banco é uma configuração separada, que
normalmente é `3306` e será informada em `conexao.php`.

## 3. Criando a pasta e os arquivos

Crie a pasta `php-mysql-cadastro` dentro de `htdocs` do XAMPP:

| Sistema | Local habitual da pasta do projeto |
|---|---|
| Windows | `C:\xampp\htdocs\php-mysql-cadastro` |
| macOS | `/Applications/XAMPP/xamppfiles/htdocs/php-mysql-cadastro` |
| Linux | `/opt/lampp/htdocs/php-mysql-cadastro` |

Se o XAMPP foi instalado em outro local, use a pasta `htdocs` dessa instalação.
Crie estes arquivos no editor. Salve os arquivos como UTF-8 e confira suas
extensões: `index.php` não pode virar `index.php.txt`.

```text
php-mysql-cadastro/
├── banco.sql
├── conexao.php
├── funcoes.php
├── index.php
├── cadastro.php
├── salvar.php
├── excluir.php
└── style.css
```

| Arquivo | Responsabilidade |
|---|---|
| `banco.sql` | Criar o banco e a tabela |
| `conexao.php` | Abrir a conexão do PHP com o banco |
| `funcoes.php` | Reunir funções usadas em várias páginas |
| `index.php` | Listar os produtos e oferecer as ações |
| `cadastro.php` | Exibir o formulário novo ou preenchido para edição |
| `salvar.php` | Validar os dados e cadastrar ou atualizar |
| `excluir.php` | Pedir confirmação e excluir |
| `style.css` | Definir a aparência das telas |

## 4. Criando o banco e a tabela — banco.sql

Copie o código abaixo para `banco.sql`. Depois:

1. Abra **http://localhost/phpmyadmin/**.
2. Na página inicial, clique na aba **SQL**.
3. Cole o conteúdo de `banco.sql` e clique em **Executar**.
4. Na lateral, selecione o banco **aula_php** e a tabela **produtos**.
5. Na aba **Estrutura**, confira os quatro campos.

Também é possível usar a aba **Importar** e selecionar o arquivo `banco.sql`.
Esse arquivo é executado no phpMyAdmin; não precisa ser aberto como página.

```sql
CREATE DATABASE IF NOT EXISTS aula_php
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE aula_php;

CREATE TABLE IF NOT EXISTS produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    quantidade INT UNSIGNED NOT NULL DEFAULT 0
);
```

### Entendendo a tabela

- `CREATE DATABASE`: cria o banco que reúne as tabelas do projeto.
- `USE`: seleciona o banco em que os comandos seguintes serão executados.
- `CREATE TABLE`: cria a tabela de produtos.
- `IF NOT EXISTS`: evita recriar um banco ou uma tabela que já existe.
- `id`: número que identifica cada produto.
- `AUTO_INCREMENT`: gera automaticamente o próximo ID.
- `PRIMARY KEY`: identifica cada linha sem repetição.
- `VARCHAR(100)`: texto com até 100 caracteres.
- `DECIMAL(10, 2)`: número com até 10 dígitos, sendo dois de centavos.
- `INT UNSIGNED`: número inteiro sem valores negativos.
- `NOT NULL`: exige um valor no campo; não impede texto vazio sozinho.
- `DEFAULT 0`: usa zero quando a quantidade não é informada no INSERT.
- `utf8mb4`: permite armazenar acentos e outros caracteres Unicode.

Não informaremos o ID no cadastro: o banco o gera. O preço será armazenado
como decimal, por exemplo `12.50`, e exibido na lista como `R$ 12,50`.

**Teste desta etapa:** a tabela deve existir e estar vazia. Ainda não há
produtos porque não executamos nenhum cadastro.

## 5. Conectando o PHP ao banco — conexao.php

Copie para `conexao.php`:

```php
<?php
// Ajuste estes valores se seu XAMPP usar outra configuração.
$host = '127.0.0.1';
$porta = '3306';
$banco = 'aula_php';
$usuario = 'root';
$senha = ''; // Configuração local comum do XAMPP: root sem senha.

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar. Confira o MySQL, o banco e conexao.php.');
}
```

### Como a conexão funciona

`new PDO(...)` abre a conexão. O texto que começa com `mysql:` informa o
driver, endereço, porta, nome do banco e codificação. O objeto `$pdo` será usado
para enviar comandos SQL.

Em instalações locais comuns do XAMPP, o usuário é `root` e a senha está vazia.
Se você definiu uma senha, preencha `$senha`. Se sua instalação usa outro
usuário ou porta, ajuste os valores correspondentes.

`try` tenta executar a conexão; `catch` trata uma falha. O detalhe do erro vai
para o registro do servidor por `error_log()`, e a página recebe uma mensagem
simples. `ERRMODE_EXCEPTION` permite tratar erros do banco com `catch`.

**Teste desta etapa:** acesse
**http://localhost/php-mysql-cadastro/conexao.php**.
Uma página vazia é o resultado esperado, pois esse arquivo não gera HTML
quando a conexão funciona. Em caso de erro, confira o serviço MySQL, as
credenciais e o nome `aula_php`.

## 6. Funções compartilhadas — funcoes.php

Copie para `funcoes.php`:

```php
<?php
// A sessão mantém o token e as mensagens entre as páginas.
session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Use ao exibir textos no HTML, inclusive em atributos value.
function escapar($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function redirecionar($pagina) {
    header('Location: ' . $pagina);
    exit;
}

function validarToken() {
    $token = $_POST['token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['token'], $token)) {
        http_response_code(403);
        exit('Formulário inválido. Volte à lista e abra o formulário novamente.');
    }
}

function validarId($valor) {
    return filter_var($valor, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 4294967295]
    ]);
}

function buscarProduto($pdo, $id) {
    $consulta = $pdo->prepare('SELECT * FROM produtos WHERE id = :id');
    $consulta->execute(['id' => $id]);
    $produto = $consulta->fetch();

    if (!$produto) {
        http_response_code(404);
        exit('Produto não encontrado. Volte à lista.');
    }

    return $produto;
}
```

### Entendendo as funções

`session_start()` inicia uma sessão: dados guardados em `$_SESSION` podem ser
recuperados em outra página da mesma sessão do navegador. Usaremos isso para
mensagens de sucesso, erros e valores do formulário.

`escapar()` prepara um texto para aparecer no HTML. Se alguém cadastrar
`<b>Caderno</b>`, o nome aparecerá literalmente, sem virar uma tag HTML.

`redirecionar()` manda o navegador abrir outra página. O `exit` encerra o
arquivo logo depois. Por isso, chamadas de `header()` e `session_start()`
ficam antes de qualquer HTML ou texto enviado à página.

`validarId()` aceita somente um inteiro positivo dentro do limite da coluna.
`buscarProduto()` consulta um único registro e informa quando ele não existe.

O **token** é um valor aleatório guardado na sessão e enviado em um campo
oculto do formulário. `validarToken()` compara esses valores antes de gravar
ou excluir, ajudando a impedir envios feitos por uma página externa. Ele não
é um login; o exemplo continua sendo um exercício local sem autenticação.

O comando `SELECT ... WHERE id = :id` usa um **parâmetro**. Em vez de juntar o
valor recebido ao texto SQL, enviamos esse valor separadamente com
`execute()`. A mesma técnica será usada para salvar e excluir.
O manual do PHP explica o uso de
[prepare() e execute()](https://www.php.net/manual/pt_BR/pdo.prepare.php).

## 7. Aparência das telas — style.css

Copie para `style.css`:

```css
* { box-sizing: border-box; }
body {
    margin: 0;
    padding: 24px;
    font-family: Arial, sans-serif;
    line-height: 1.5;
    background: #f2f4f7;
    color: #202938;
}
main {
    max-width: 900px;
    margin: auto;
    padding: 24px;
    background: white;
    border-radius: 8px;
}
a { color: #174e9b; margin-right: 12px; }
label { display: block; margin-top: 16px; font-weight: bold; }
input:not([type="hidden"]) {
    display: block;
    width: 100%;
    max-width: 420px;
    padding: 10px;
    font: inherit;
    border: 1px solid #687587;
    border-radius: 4px;
}
small { display: block; }
button {
    margin: 20px 12px 0 0;
    padding: 10px 16px;
    border: 0;
    border-radius: 4px;
    background: #174e9b;
    color: white;
    font: inherit;
    cursor: pointer;
}
.perigo { background: #a51e2c; }
.mensagem { padding: 12px; background: #e0f2e7; }
.erros { padding: 16px 32px; background: #ffe8e8; }
.tabela { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
caption { text-align: left; font-weight: bold; margin-bottom: 8px; }
th, td { padding: 10px; border: 1px solid #ccd3dc; text-align: left; }
th { background: #edf1f6; }
```

O mesmo CSS será carregado por todas as telas. `max-width` limita a largura
do conteúdo, enquanto `overflow-x: auto` permite rolar a tabela em telas
pequenas. As classes `mensagem`, `erros` e `perigo` destacam os resultados.

## 8. Tela de consulta — index.php

Copie para `index.php`:

```php
<?php
require_once __DIR__ . '/funcoes.php';
require_once __DIR__ . '/conexao.php';

// SELECT consulta os registros; ORDER BY organiza a lista.
$consulta = $pdo->query('SELECT * FROM produtos ORDER BY id DESC');
$produtos = $consulta->fetchAll();

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']); // Mostra a mensagem apenas uma vez.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Cadastro de produtos</h1>
    <p><a href="cadastro.php">Cadastrar produto</a></p>

    <?php if ($mensagem !== '') { ?>
        <p class="mensagem" role="status"><?= escapar($mensagem) ?></p>
    <?php } ?>

    <?php if (count($produtos) === 0) { ?>
        <p>Nenhum produto cadastrado.</p>
    <?php } else { ?>
        <div class="tabela">
        <table>
            <caption>Produtos cadastrados</caption>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto) { ?>
                    <tr>
                        <td><?= escapar($produto['id']) ?></td>
                        <td><?= escapar($produto['nome']) ?></td>
                        <td>R$ <?= number_format((float) $produto['preco'], 2, ',', '.') ?></td>
                        <td><?= escapar($produto['quantidade']) ?></td>
                        <td>
                            <a href="cadastro.php?id=<?= escapar($produto['id']) ?>">Editar</a>
                            <a href="excluir.php?id=<?= escapar($produto['id']) ?>">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    <?php } ?>
</main>
</body>
</html>
```

### Como a listagem funciona

`require_once` carrega outro arquivo uma única vez. `__DIR__` indica a pasta
do arquivo atual, permitindo localizar `funcoes.php` e `conexao.php`.

O `SELECT * FROM produtos` lê os campos da tabela. `ORDER BY id DESC` coloca
os registros de maior ID primeiro. `query()` executa essa consulta fixa, que
não recebe dados do usuário. `fetchAll()` devolve os registros em um array,
e `foreach` cria uma linha HTML para cada produto.

`<?= escapar($produto['nome']) ?>` é uma forma curta de escrever
`<?php echo escapar($produto['nome']); ?>`.

`number_format()` prepara o preço para exibição em reais. O valor armazenado
no banco continua decimal. `unset()` apaga a mensagem da sessão depois de
recuperá-la, para que ela apareça uma única vez.

**Teste desta etapa:** acesse
**http://localhost/php-mysql-cadastro/**.
Você deve ver o título, o link de cadastro e **Nenhum produto cadastrado**.
Os links das próximas telas funcionarão depois que você criar os arquivos
correspondentes.

## 9. Tela de cadastro e edição — cadastro.php

Copie para `cadastro.php`:

```php
<?php
require_once __DIR__ . '/funcoes.php';
require_once __DIR__ . '/conexao.php';

// Sem ID: formulário novo. Com ID: formulário de edição.
$produto = ['id' => '', 'nome' => '', 'preco' => '', 'quantidade' => '0'];
$editando = isset($_GET['id']);

if ($editando) {
    $id = validarId($_GET['id']);
    if ($id === false) {
        http_response_code(400);
        exit('ID inválido. Volte à lista.');
    }
    $produto = buscarProduto($pdo, $id);
}

$erros = $_SESSION['erros'] ?? [];
$dados = $_SESSION['dados'] ?? null;
unset($_SESSION['erros'], $_SESSION['dados']);

// Após um erro, recupera os valores que a pessoa digitou.
if ($dados !== null && $dados['id'] === (string) $produto['id']) {
    $produto = $dados;
}

$titulo = $editando ? 'Editar produto' : 'Cadastrar produto';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar($titulo) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1><?= escapar($titulo) ?></h1>
    <p><a href="index.php">Voltar à lista</a></p>

    <?php if (count($erros) > 0) { ?>
        <ul class="erros" role="alert">
            <?php foreach ($erros as $erro) { ?>
                <li><?= escapar($erro) ?></li>
            <?php } ?>
        </ul>
    <?php } ?>

    <form action="salvar.php" method="post">
        <input type="hidden" name="token" value="<?= escapar($_SESSION['token']) ?>">
        <input type="hidden" name="id" value="<?= escapar($produto['id']) ?>">

        <label for="nome">Nome do produto:</label>
        <input type="text" id="nome" name="nome" maxlength="100" required
               value="<?= escapar($produto['nome']) ?>">

        <label for="preco">Preço (R$):</label>
        <input type="text" id="preco" name="preco" inputmode="decimal"
               placeholder="Ex.: 12,50" required value="<?= escapar($produto['preco']) ?>">
        <small>Use ponto ou vírgula para os centavos, sem separador de milhar.</small>

        <label for="quantidade">Quantidade em estoque:</label>
        <input type="number" id="quantidade" name="quantidade" min="0"
               max="1000000" step="1" required value="<?= escapar($produto['quantidade']) ?>">

        <button type="submit">Salvar produto</button>
    </form>
</main>
</body>
</html>
```

### Um formulário para duas operações

Sem `id` na URL, o formulário começa vazio para cadastrar um produto. Com
`cadastro.php?id=1`, o PHP lê `$_GET['id']`, consulta o produto e preenche os
campos. O número `1` é apenas um exemplo: use um ID que exista na sua tabela.

O campo oculto `id` fica vazio no cadastro e recebe o ID na edição.
`salvar.php` usará essa informação para escolher entre INSERT e UPDATE.
Campos ocultos também podem ser alterados por quem envia a requisição;
por isso, o PHP valida o ID recebido.

`action="salvar.php"` define o destino e `method="post"` define a forma de
envio. O atributo `name` de cada campo é a chave usada em `$_POST`.
Os atributos `required`, `min` e `max` ajudam no navegador; o servidor também
precisa validar os valores.

Se houver erros, os dados da sessão preenchem novamente o formulário para que
você possa corrigi-los. O preço usa campo de texto com `inputmode="decimal"`
para aceitar `12,50` e `12.50`. Não use separadores de milhar: digite `1250,00`,
e não `1.250,00`.

**Teste desta etapa:** clique em **Cadastrar produto**. Confira os três campos
e o link para voltar. Crie `salvar.php` antes de tentar enviar o formulário.

## 10. Gravando e atualizando — salvar.php

Copie para `salvar.php`:

```php
<?php
require_once __DIR__ . '/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Use o formulário para salvar um produto.');
}

validarToken();
require_once __DIR__ . '/conexao.php';

// Aceita somente textos nos campos esperados.
$dados = [];
foreach (['id', 'nome', 'preco', 'quantidade'] as $campo) {
    $valor = $_POST[$campo] ?? '';
    if (!is_string($valor)) {
        http_response_code(400);
        exit('Dados inválidos. Volte à lista.');
    }
    $dados[$campo] = trim($valor);
}

$id = $dados['id'] === '' ? null : validarId($dados['id']);
if ($id === false) {
    http_response_code(400);
    exit('ID inválido. Volte à lista.');
}

if ($id !== null) {
    buscarProduto($pdo, $id); // Confere se o registro ainda existe.
}

$erros = [];
// Conta caracteres UTF-8, incluindo letras acentuadas.
$tamanhoNome = preg_match_all('/./us', $dados['nome']);
if ($tamanhoNome === false || $tamanhoNome < 1 || $tamanhoNome > 100) {
    $erros[] = 'Informe um nome com 1 a 100 caracteres.';
}

// Mantém o preço como texto decimal para gravar em DECIMAL sem arredondar.
$preco = str_replace(',', '.', $dados['preco']);
if (!preg_match('/^[0-9]{1,8}(\.[0-9]{1,2})?$/D', $preco)) {
    $erros[] = 'Informe um preço de 0 a 99999999,99, com até duas casas decimais.';
}

$quantidade = filter_var($dados['quantidade'], FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 0, 'max_range' => 1000000]
]);
if ($quantidade === false) {
    $erros[] = 'Informe uma quantidade inteira de 0 a 1000000.';
}

if (count($erros) > 0) {
    $_SESSION['erros'] = $erros;
    $_SESSION['dados'] = $dados;
    $destino = $id === null ? 'cadastro.php' : 'cadastro.php?id=' . $id;
    redirecionar($destino);
}

try {
    $parametros = [
        'nome' => $dados['nome'],
        'preco' => $preco,
        'quantidade' => $quantidade
    ];

    if ($id === null) {
        // INSERT cria um registro. O banco gera o ID automaticamente.
        $sql = 'INSERT INTO produtos (nome, preco, quantidade)
                VALUES (:nome, :preco, :quantidade)';
        $mensagem = 'Produto cadastrado com sucesso!';
    } else {
        // WHERE limita a alteração ao produto escolhido.
        $sql = 'UPDATE produtos SET nome = :nome, preco = :preco,
                quantidade = :quantidade WHERE id = :id';
        $parametros['id'] = $id;
        $mensagem = 'Produto atualizado com sucesso!';
    }

    $comando = $pdo->prepare($sql);
    $comando->execute($parametros);
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    http_response_code(500);
    exit('Não foi possível salvar. Consulte o registro de erros do Apache.');
}

unset($_SESSION['erros'], $_SESSION['dados']);
$_SESSION['mensagem'] = $mensagem;
// Volta à lista: atualizar a página não repete o INSERT ou o UPDATE.
redirecionar('index.php');
```

### Passo a passo da gravação

1. Confere se a requisição usa POST e valida o token do formulário.
2. Lê os campos e remove espaços nas extremidades com `trim()`.
3. Valida o ID quando é uma edição e verifica se o produto existe.
4. Confere o nome, o preço e a quantidade.
5. Se houver erros, guarda os valores na sessão e volta ao formulário.
6. Sem ID, executa INSERT. Com ID, executa UPDATE com `WHERE id = :id`.
7. Guarda a mensagem de sucesso e redireciona à lista.

O preço aceita até oito dígitos antes do separador e duas casas decimais,
respeitando a coluna `DECIMAL(10, 2)`. A expressão usada em `preg_match()`
confere esse formato. O valor vai ao banco como texto decimal normalizado
com ponto, evitando fazer cálculos com números de ponto flutuante para gravar
o preço. `preg_match_all()` conta os caracteres do nome, incluindo acentos.

`FILTER_VALIDATE_INT` verifica a quantidade inteira dentro do intervalo
escolhido para o exercício. A comparação é `$quantidade === false`, porque
zero é uma quantidade válida e não deve ser confundida com falha.

Os marcadores `:nome`, `:preco`, `:quantidade` e `:id` recebem os valores
por `execute()`. Não substitua isso por concatenação de dados no SQL.

Após salvar, o redirecionamento faz a lista ser aberta por GET. Assim,
atualizar a lista não envia novamente o cadastro anterior.

### Teste: cadastrar e consultar no banco

1. Abra o formulário pela lista.
2. Cadastre **Caderno**, preço **12,50**, quantidade **10**.
3. Clique em **Salvar produto**.
4. Confira a mensagem de sucesso e o produto na lista.
5. Atualize a lista: deve continuar existindo apenas um Caderno.
6. No phpMyAdmin, selecione `aula_php` → `produtos` → **Visualizar**.
7. Confira o registro e seu ID gerado automaticamente.

Feche a aba e abra o sistema novamente. O produto continuará lá porque foi
gravado no banco. Nomes iguais são permitidos neste exemplo; o identificador
único é o ID.

### Teste: editar

1. Na linha do Caderno, clique em **Editar**.
2. Confira se os campos já mostram os valores cadastrados.
3. Altere o preço para **15,90** e a quantidade para **8**.
4. Clique em **Salvar produto**.
5. Confira os novos valores na lista e no phpMyAdmin.

O ID deve continuar o mesmo: UPDATE altera o registro existente.
**Nunca retire o WHERE do UPDATE deste exemplo**, pois isso alteraria todas
as linhas da tabela.

## 11. Tela de confirmação e exclusão — excluir.php

Copie para `excluir.php`:

```php
<?php
require_once __DIR__ . '/funcoes.php';

$metodo = $_SERVER['REQUEST_METHOD'];
if ($metodo !== 'GET' && $metodo !== 'POST') {
    http_response_code(405);
    header('Allow: GET, POST');
    exit('Método não permitido.');
}

if ($metodo === 'POST') {
    validarToken();
}

require_once __DIR__ . '/conexao.php';
$valorId = $metodo === 'POST' ? ($_POST['id'] ?? '') : ($_GET['id'] ?? '');
$id = validarId($valorId);

if ($id === false) {
    http_response_code(400);
    exit('ID inválido. Volte à lista.');
}

$produto = buscarProduto($pdo, $id);

if ($metodo === 'POST') {
    try {
        $comando = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
        $comando->execute(['id' => $id]);
    } catch (PDOException $erro) {
        error_log($erro->getMessage());
        http_response_code(500);
        exit('Não foi possível excluir. Consulte o registro de erros do Apache.');
    }

    $_SESSION['mensagem'] = 'Produto excluído com sucesso!';
    redirecionar('index.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Excluir produto</h1>
    <p>Deseja excluir <strong><?= escapar($produto['nome']) ?></strong>?</p>
    <p>Esta operação remove o registro do banco de dados.</p>

    <form action="excluir.php" method="post">
        <input type="hidden" name="token" value="<?= escapar($_SESSION['token']) ?>">
        <input type="hidden" name="id" value="<?= escapar($produto['id']) ?>">
        <button type="submit" class="perigo">Confirmar exclusão</button>
        <a href="index.php">Cancelar e voltar</a>
    </form>
</main>
</body>
</html>
```

### Por que confirmar antes de excluir

O link da lista abre uma página por GET e apenas mostra o produto escolhido.
O registro só é removido quando você envia o formulário por POST clicando
em **Confirmar exclusão**. O token também é verificado nessa etapa.

`DELETE FROM produtos WHERE id = :id` remove somente o registro identificado.
**Nunca retire o WHERE**, pois isso excluiria todas as linhas da tabela.
O link **Cancelar e voltar** retorna à lista sem alterar o banco.

### Teste: cancelar e excluir

1. Cadastre outro produto chamado **Borracha**, preço **2,00**, quantidade **5**.
2. Na linha da Borracha, clique em **Excluir**.
3. Confira se o nome mostrado é o correto.
4. Clique em **Cancelar e voltar** e confirme que ela continua na lista.
5. Abra **Excluir** novamente e clique em **Confirmar exclusão**.
6. Confira a mensagem e a ausência da Borracha na lista e no phpMyAdmin.
7. Confirme que o Caderno continua cadastrado.

A exclusão não possui opção de desfazer neste exemplo. Um novo cadastro pode
receber um ID diferente; o banco não precisa reutilizar o número excluído.

## 12. Entendendo os principais comandos SQL

Os exemplos abaixo servem para observar a linguagem SQL. Se quiser executá-los,
use a aba **SQL** dentro do banco `aula_php` no phpMyAdmin. INSERT adiciona
outro produto, e UPDATE/DELETE modificam dados: use registros de teste.

### INSERT: criar um registro

```sql
INSERT INTO produtos (nome, preco, quantidade)
VALUES ('Lápis', 3.50, 20);
```

Textos usam aspas simples no SQL e números decimais usam ponto.

### SELECT: consultar todos os produtos

```sql
SELECT id, nome, preco, quantidade
FROM produtos
ORDER BY nome;
```

### SELECT com WHERE: consultar um produto

```sql
SELECT * FROM produtos WHERE id = 1;
```

Troque `1` por um ID existente. O símbolo `*` significa todos os campos.

### UPDATE: alterar um registro

```sql
UPDATE produtos
SET preco = 4.00, quantidade = 15
WHERE id = 1;
```

### DELETE: remover um registro

```sql
DELETE FROM produtos WHERE id = 1;
```

Nos arquivos PHP, os valores variáveis entram por parâmetros, como `:id`.
Nos exemplos executados diretamente no phpMyAdmin, usamos valores literais.

## 13. Roteiro para testar o sistema completo

Use produtos de teste e siga esta sequência:

| Ação | Resultado esperado |
|---|---|
| Abrir a lista com a tabela vazia | Mensagem de que não há produtos |
| Cadastrar Caderno, 12,50, quantidade 10 | Uma linha nova e mensagem de sucesso |
| Cadastrar Caneta, 3.75, quantidade 0 | Aceita ponto no preço e zero no estoque |
| Atualizar a lista após cadastrar | Não duplica o último cadastro |
| Editar o Caderno | Mantém o ID e atualiza os valores |
| Salvar a edição sem mudar valores | Continua funcionando, sem criar outra linha |
| Informar preço `abc`, `-2` ou `1,234` | Volta ao formulário com erro e preserva os campos |
| Informar preço `0` | Aceita: produto gratuito é permitido neste exercício |
| Informar nome contendo apenas espaços | Mostra erro e não grava |
| Cadastrar nome `<b>Teste</b>` | Mostra o texto literal na lista, sem negrito |
| Cancelar uma exclusão | Mantém o produto |
| Confirmar uma exclusão | Remove apenas o produto escolhido |
| Abrir `cadastro.php?id=abc` | Informa ID inválido |
| Abrir edição com um ID positivo inexistente | Informa produto não encontrado |
| Abrir `salvar.php` diretamente no navegador | Informa que é preciso usar o formulário |
| Fechar e reabrir o navegador | Registros continuam no banco |

O navegador normalmente bloqueia quantidade negativa, fracionada ou campo
obrigatório vazio. Para observar a validação do PHP, abra as ferramentas do
navegador com F12, use a aba **Elementos/Inspector** e remova temporariamente
`min`, `step` ou `required` do campo. Envie quantidade `-1`, `1.5` ou um nome
vazio e confira o erro. Ao recarregar a página, os atributos voltam.

Para conferir o token, altere temporariamente o `value` do campo oculto
`token` pelo Inspector e envie o formulário. A resposta deve indicar formulário
inválido, sem gravar nem excluir. Volte à lista e reabra o formulário para
continuar.

## 14. Problemas comuns

| Problema | O que conferir |
|---|---|
| `localhost` não abre | Apache iniciado e porta correta na URL |
| phpMyAdmin não conecta | Serviço MySQL/MariaDB iniciado e configuração da instalação |
| Página não encontrada | Pasta dentro de `htdocs`, nome da pasta e extensão `.php` |
| Erro de conexão do sistema | Host, porta, usuário e senha em `conexao.php` |
| `Unknown database` no registro de erros | Execute `banco.sql` e confira `aula_php` |
| Tabela `produtos` não existe | Execute a criação da tabela no banco correto |
| `could not find driver` no registro de erros | Use o PHP do XAMPP e confira a extensão `pdo_mysql` |
| `headers already sent` | Remova espaços ou HTML antes de `<?php`; salve sem BOM |
| CSS não aparece | Confira `style.css` na mesma pasta e atualize a página |
| Formulário inválido | Volte à lista, reabra o formulário e confira se cookies estão permitidos |
| ID inválido ou produto não encontrado | Abra a edição/exclusão pelos links da lista |
| Página vazia ou erro 500 | Consulte o registro de erros do Apache e a sintaxe do arquivo |

No Windows, o registro costuma ficar em `C:\xampp\apache\logs\error.log`.
Também é possível abri-lo pelo botão **Logs** do Apache no painel. Em outras
instalações, procure o arquivo de erros na pasta de logs do XAMPP.

Para conferir a sintaxe no terminal do Windows, por exemplo:

```bat
C:\xampp\php\php.exe -l C:\xampp\htdocs\php-mysql-cadastro\salvar.php
```

Esse comando verifica a sintaxe; as operações do banco precisam ser testadas
com o Apache e o MySQL/MariaDB em execução.

## 15. Exercícios para continuar

1. Cadastre três produtos e confira os registros pelo phpMyAdmin.
2. Altere a ordenação da lista para nome em ordem alfabética.
3. Mostre a quantidade total de produtos cadastrados usando `count($produtos)`.
4. Mostre uma mensagem quando a quantidade em estoque for zero.
5. Acrescente um campo de descrição: altere a tabela, formulário, validação,
   INSERT, UPDATE e exibição.
6. Crie uma pesquisa por nome usando `SELECT ... WHERE nome LIKE :busca` e
   parâmetros de `prepare()`/`execute()`.

### Desafio final

Adapte o sistema para cadastrar livros, com título, autor, preço e quantidade.
Mantenha as quatro operações, as mensagens, a validação no PHP e a confirmação
de exclusão. Teste cada operação e confira os resultados no phpMyAdmin.

Este exemplo é para estudo local. O uso de `root` sem senha acompanha uma
configuração comum do XAMPP; um sistema publicado precisa de credenciais
próprias e controle de acesso.
