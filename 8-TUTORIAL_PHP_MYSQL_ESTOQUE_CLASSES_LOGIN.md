# PHP com classes e MySQL no XAMPP: controle de estoque com login

Neste tutorial, vamos criar um controle de estoque com **PHP 8 ou superior, MySQL/MariaDB e Bootstrap**. Começaremos pelo login e depois reuniremos todas as operações de produtos na classe `Cadastro`.

O [projeto pronto está nesta pasta](php-mysql-estoque). Os códigos completos aparecem abaixo, com comentários curtos antes dos métodos e das chamadas PHP para ajudar a acompanhar a aula.

## 1. O que vamos construir

| Recurso | Visitante | Usuário logado |
|---|---|---|
| Listar e buscar produtos pelo nome | Sim | Sim |
| Cadastrar produtos | Não | Sim |
| Alterar produtos no mesmo formulário do cadastro | Não | Sim |
| Excluir com confirmação em modal | Não | Sim |

O estoque será a quantidade atual de cada produto, informada no formulário. Não teremos histórico de entradas e saídas nesta primeira versão. Todos os usuários autenticados terão as mesmas permissões.

### Organização simplificada

```text
php-mysql-estoque/
├── banco.sql
├── index.php
├── operacoes.php
└── classes/
    ├── Conexao.php
    ├── Login.php
    └── Cadastro.php
```

| Arquivo | Responsabilidade |
|---|---|
| `banco.sql` | Criar banco, tabelas, administrador e produtos de exemplo |
| `classes/Conexao.php` | Abrir a conexão PDO |
| `classes/Login.php` | Entrar, consultar a sessão, proteger acesso e sair |
| `classes/Cadastro.php` | Reunir todas as operações SQL de produtos |
| `index.php` | Consultar os dados e mostrar login, lista, cadastro/edição e modal |
| `operacoes.php` | Receber os formulários POST de login, saída, gravação e exclusão |

As telas, o cabeçalho e o rodapé ficam no `index.php`. O arquivo intermediário `operacoes.php` recebe os formulários e executa as operações. As três classes ficam separadas para destacar suas responsabilidades. Não usaremos framework ou Composer.

## 2. Preparar o XAMPP

1. Inicie **Apache** e **MySQL** no painel do XAMPP.
2. Copie a pasta do projeto para `htdocs`.
3. Abra `http://localhost/phpmyadmin/`.
4. Na aba **Importar**, selecione `banco.sql` e execute. Também é possível colar o conteúdo na aba **SQL**.
5. Confira o banco `aula_estoque`, com as tabelas `usuarios` e `produtos`.
6. Ao terminar os arquivos, abra `http://localhost/php-mysql-estoque/`.

| Sistema | Local habitual da pasta do projeto |
|---|---|
| Windows | `C:\xampp\htdocs\php-mysql-estoque` |
| macOS | `/Applications/XAMPP/xamppfiles/htdocs/php-mysql-estoque` |
| Linux | `/opt/lampp/htdocs/php-mysql-estoque` |

Use a pasta da sua instalação. Se o Apache usar a porta 8080, acrescente `:8080` depois de `localhost`. O XAMPP pode fornecer MariaDB com o serviço chamado MySQL; os comandos deste exercício atendem ambos.

Salve os arquivos como UTF-8 sem BOM, sem espaços antes de `<?php`. Confira se o editor não acrescentou `.txt` ao nome.

## 3. Banco com dados iniciais

**Administrador padrão da aula:**

- Nome: **Administrador**
- E-mail para login: **admin@estoque.local**
- Senha: **Aula@123**

O SQL já cria esse usuário com a senha armazenada como **hash**. Não é necessário executar um script PHP para cadastrar o administrador. A senha informada no formulário será comparada com esse hash por `password_verify()`.

O banco também começa com Caderno, Caneta azul, Lápis e Borracha. A Borracha tem quantidade zero para demonstrar um produto sem estoque.

Se importar novamente, o script preserva o usuário com esse e-mail e não redefine sua senha. Também não acrescenta outro exemplo quando já existe um produto com o mesmo nome. Ele não apaga tabelas ou registros existentes. As credenciais acima são para este exercício local.


### `banco.sql`

```sql
CREATE DATABASE IF NOT EXISTS aula_estoque
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aula_estoque;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) UNSIGNED NOT NULL,
    quantidade INT UNSIGNED NOT NULL DEFAULT 0
);

-- Dados para a aula. A senha do admin é Aula@123 (armazenada como hash).
-- Se o e-mail já existir, preserva os dados e a senha desse usuário.
INSERT INTO usuarios (nome, email, senha)
SELECT 'Administrador', 'admin@estoque.local',
       '$2y$12$I3i98R/vZKN4a2by2azrJOmouSo.LRJO79pydEtSlbiKaYMmZmBpi'
WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE email = 'admin@estoque.local');

-- Insere cada exemplo somente se ainda não houver um produto com esse nome.
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Caderno', 12.50, 20
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Caderno');
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Caneta azul', 2.50, 50
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Caneta azul');
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Lápis', 1.50, 30
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Lápis');
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Borracha', 2.00, 0
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Borracha');
```

`AUTO_INCREMENT` gera o ID; `PRIMARY KEY` identifica a linha. `UNIQUE` impede e-mails repetidos. `DECIMAL(10,2)` guarda preços com duas casas decimais, e `UNSIGNED` impede números negativos nessas colunas.

`INSERT ... SELECT ... WHERE NOT EXISTS` insere os dados de exemplo quando ainda não estão presentes. O hash já está pronto no SQL; para criar outras senhas pelo PHP, use `password_hash($senha, PASSWORD_DEFAULT)`.

**Teste:** no phpMyAdmin, abra a tabela `usuarios` e confira o administrador. Na coluna `senha`, deve aparecer o hash, não `Aula@123`. Em um banco novo, `produtos` deve conter quatro registros.

## 4. Aula 1 — classe de conexão

Uma classe agrupa comportamentos. `new Conexao()` cria um objeto; `->conectar()` chama um método desse objeto. O retorno será uma conexão PDO, compartilhada com as outras classes.


### `classes/Conexao.php`

```php
<?php
class Conexao
{
    // Abre e devolve a conexão PDO com o MySQL do XAMPP.
    public function conectar(): PDO
    {
        // Cria a conexão com endereço, banco, usuário, senha e opções.
        return new PDO(
            'mysql:host=127.0.0.1;port=3306;dbname=aula_estoque;charset=utf8mb4',
            'root',
            '', // Ajuste a senha de acordo com seu XAMPP.
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }
}
```

Ajuste host, porta, usuário e senha conforme seu XAMPP. `root` com senha vazia é uma configuração local comum. `charset=utf8mb4` permite acentos; as opções ativam exceções, retornam linhas como arrays e usam parâmetros preparados pelo banco.

Para testar a conexão antes de avançar, crie temporariamente este `index.php`:

```php
<?php
// Carrega a classe que abre o banco.
require_once __DIR__ . '/classes/Conexao.php';

// Abre a conexão; se falhar, confira as configurações e o serviço MySQL.
$pdo = (new Conexao())->conectar();
echo 'Conexão realizada!';
```

Abra o projeto pelo localhost. Depois, substitua esse teste pela página definitiva apresentada na etapa 7.

## 5. Aula 1 — classe de login

`__construct()` é executado ao criar o objeto. Ele recebe a conexão pronta e a guarda em `$this->pdo`. `private` indica uso interno; `public` permite chamar o método pela página. Os tipos `PDO`, `string`, `bool` e `void` mostram o que os métodos recebem ou retornam.


### `classes/Login.php`

```php
<?php
class Login
{
    private PDO $pdo;

    // Recebe a conexão pronta e a guarda para os outros métodos.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Confere e-mail e senha; retorna true quando o login funciona.
    public function entrar(string $email, string $senha): bool
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$email]);
        // Lê a linha do usuário ou recebe false quando não há resultado.
        $usuario = $sql->fetch();

        // Compara a senha digitada com o hash armazenado.
        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return false;
        }

        // Renova o identificador da sessão após autenticar.
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome']
        ];
        return true;
    }

    // Informa se existe um usuário identificado na sessão.
    public function estaLogado(): bool
    {
        // Verifica se o login gravou os dados do usuário na sessão.
        return isset($_SESSION['usuario']);
    }

    // Envia visitantes para o login e interrompe o acesso protegido.
    public function exigirLogin(): void
    {
        // Bloqueia a continuação quando não há usuário autenticado.
        if (!$this->estaLogado()) {
            // Define a próxima página que o navegador deve abrir.
            header('Location: index.php?tela=login');
            exit;
        }
    }

    // Apaga os dados da sessão e encerra o login.
    public function sair(): void
    {
        $_SESSION = [];
        // Remove a sessão mantida pelo servidor.
        session_destroy();
    }
}
```

`entrar()` procura o e-mail e confere a senha. O `?` é um parâmetro: o valor é enviado separadamente pelo `execute()`, em vez de ser concatenado ao SQL. `fetch()` retorna uma linha ou `false`.

Após autenticar, `session_regenerate_id(true)` renova o identificador da sessão. Guardamos em `$_SESSION['usuario']` somente o ID e o nome. `estaLogado()` consulta essa informação, e `exigirLogin()` bloqueia visitantes. O `exit` depois de `header()` impede que o restante da página continue executando.

Esconder botões não protege operações. A página também chamará `exigirLogin()` antes de salvar ou excluir.

**Ponto de parada para testar o login:** depois de copiar o `index.php` da etapa 7, abra `index.php?tela=login`. Tente uma senha errada, entre com **admin@estoque.local / Aula@123**, atualize a página e clique em **Sair**. O nome deve permanecer após atualizar e desaparecer depois de sair. Faça esses testes antes de explorar as operações de produtos.

## 6. Aula 2 — uma classe com todas as operações de produtos

A classe `Cadastro` recebe o mesmo PDO do login. Ela concentra listagem, busca, inclusão, alteração e exclusão. As páginas cuidarão dos formulários e da sessão.


### `classes/Cadastro.php`

```php
<?php
// Todas as operações de produtos ficam nesta classe.
class Cadastro
{
    private PDO $pdo;

    // Recebe a conexão pronta e a guarda para os outros métodos.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lista os produtos e permite filtrar parte do nome.
    public function listar(string $busca = ''): array
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('SELECT * FROM produtos WHERE nome LIKE ? ORDER BY nome');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute(['%' . $busca . '%']);
        // Devolve todas as linhas encontradas em um array.
        return $sql->fetchAll();
    }

    // Busca um produto pelo ID; retorna false se não existir.
    public function buscar(int $id): array|false
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('SELECT * FROM produtos WHERE id = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$id]);
        // Devolve uma linha ou false quando o ID não existe.
        return $sql->fetch();
    }

    // Insere um produto com nome, preço e quantidade.
    public function cadastrar(string $nome, string $preco, int $quantidade): void
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('INSERT INTO produtos (nome, preco, quantidade) VALUES (?, ?, ?)');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$nome, $preco, $quantidade]);
    }

    // Atualiza os campos de um produto identificado pelo ID.
    public function alterar(int $id, string $nome, string $preco, int $quantidade): void
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('UPDATE produtos SET nome = ?, preco = ?, quantidade = ? WHERE id = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$nome, $preco, $quantidade, $id]);
    }

    // Remove somente o produto identificado pelo ID.
    public function excluir(int $id): void
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('DELETE FROM produtos WHERE id = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$id]);
    }
}
```

| Método | Comando | Resultado |
|---|---|---|
| `listar($busca)` | SELECT com LIKE | Lista completa ou filtrada pelo nome |
| `buscar($id)` | SELECT com WHERE | Um produto para preencher a edição |
| `cadastrar(...)` | INSERT | Novo produto |
| `alterar(...)` | UPDATE com WHERE | Atualização pelo ID |
| `excluir($id)` | DELETE com WHERE | Remoção pelo ID |

`fetchAll()` devolve um array de linhas; `fetch()` devolve uma linha ou `false`. O retorno `array|false` documenta essas duas possibilidades.

Em `LIKE`, `%` representa qualquer sequência de caracteres. A busca vazia gera `%%`, listando tudo. Os caracteres `%` e `_` digitados na pesquisa também funcionam como curingas.

Os valores de `execute()` seguem a ordem dos `?`. No UPDATE, o ID é o último. Observe o `WHERE id = ?`, que restringe qual produto será alterado ou removido. O preço é recebido como texto decimal para gravar no banco sem cálculos com ponto flutuante.

## 7. Telas e arquivo intermediário de operações

Crie os dois arquivos abaixo. O `index.php` substitui o teste temporário de conexão e exibe as telas. O `operacoes.php` recebe os dados enviados pelos formulários. Para explicar em sala, comece pelo formulário de login e acompanhe seu envio até o arquivo intermediário.

### Como a página escolhe o que fazer

O parâmetro GET `tela` escolhe o conteúdo exibido:

| Endereço | Tela |
|---|---|
| `index.php` | Lista pública com pesquisa |
| `index.php?tela=login` | Login |
| `index.php?tela=cadastro` | Novo produto, com login obrigatório |
| `index.php?tela=cadastro&id=1` | Mesmo formulário preenchido para edição |

O ID `1` é um exemplo: use um ID existente. Os formulários enviam os dados para `operacoes.php`, com um campo oculto `acao` por POST, com um destes valores: `entrar`, `sair`, `salvar` ou `excluir`.

O `index.php` inicia a sessão, consulta os dados necessários e monta o HTML. O `operacoes.php` inicia a sessão, confere o login e o token, executa a operação solicitada e redireciona para `index.php`. Ele não monta telas. Sua função `redirecionar()` reúne `header()` e `exit`. Cada arquivo carrega as classes e abre sua conexão, pois são executados em requisições diferentes.

### Bootstrap

Usaremos um layout gratuito montado com componentes oficiais do [Bootstrap 5](https://getbootstrap.com/docs/5.3/getting-started/introduction/): barra superior, cartão de login, tabela, formulários e [modal](https://getbootstrap.com/docs/5.3/components/modal/). Não há template adicional para instalar.

O CSS e o JavaScript vêm de CDN. A aparência e o modal precisam de internet. Para uma aula sem internet, baixe os dois arquivos previamente e troque os endereços por caminhos locais. `container` centraliza o conteúdo, `card` monta os painéis, `btn` estiliza os botões e `table-responsive` permite rolar a tabela em telas menores.


### `index.php`

```php
<?php
// Inicia a sessão antes de qualquer saída HTML.
session_start();

// Carrega as três classes usadas nesta página.
require_once __DIR__ . '/classes/Conexao.php';
require_once __DIR__ . '/classes/Login.php';
require_once __DIR__ . '/classes/Cadastro.php';

// Abre uma conexão e a compartilha com os dois objetos.
$pdo = (new Conexao())->conectar();
$login = new Login($pdo);
$cadastro = new Cadastro($pdo);

// Converte caracteres especiais para exibir dados como texto no HTML.
function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Gera uma identificação para proteger os formulários desta sessão.
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$tela = $_GET['tela'] ?? 'lista';
// Recupera o resultado do login e o remove após esta exibição.
$erro = $_SESSION['erro_login'] ?? '';
$email = $_SESSION['email_login'] ?? '';
unset($_SESSION['erro_login'], $_SESSION['email_login']);

// Consulta a sessão uma vez para decidir quais botões exibir.
$logado = $login->estaLogado();
if ($tela === 'login' && $logado) {
    // Envia quem já entrou para a listagem.
    header('Location: index.php');
    exit;
}

$produto = ['id' => '', 'nome' => '', 'preco' => '', 'quantidade' => 0];
if ($tela === 'cadastro') {
    // Protege também o acesso ao formulário por URL.
    $login->exigirLogin();
    if (isset($_GET['id'])) {
        // Recupera os valores que preencherão o formulário de edição.
        $produto = $cadastro->buscar((int) $_GET['id']);
        if (!$produto) {
            http_response_code(404);
            exit('Produto não encontrado.');
        }
    }
}

// Lê a pesquisa e consulta os produtos somente na tela de listagem.
$busca = trim($_GET['busca'] ?? '');
$produtos = $tela === 'lista' ? $cadastro->listar($busca) : [];
$titulo = match ($tela) {
    'login' => 'Entrar no estoque',
    'cadastro' => $produto['id'] ? 'Alterar produto' : 'Cadastrar produto',
    default => 'Produtos em estoque'
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= /* Exibe texto seguro no HTML. */ escapar($titulo ?? 'Controle de estoque') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
<nav class="navbar bg-dark" data-bs-theme="dark">
    <div class="container gap-3">
        <a class="navbar-brand" href="index.php">Controle de estoque</a>
        <?php if ($logado) { ?>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white"><?= /* Exibe texto seguro no HTML. */ escapar($_SESSION['usuario']['nome']) ?></span>
                <form action="operacoes.php" method="post">
                    <input type="hidden" name="acao" value="sair">
                    <input type="hidden" name="token" value="<?= /* Exibe texto seguro no HTML. */ escapar($_SESSION['token']) ?>">
                    <button class="btn btn-outline-light btn-sm">Sair</button>
                </form>
            </div>
        <?php } else { ?>
            <a class="btn btn-outline-light btn-sm" href="index.php?tela=login">Entrar</a>
        <?php } ?>
    </div>
</nav>
<main class="container py-4">
<?php if ($tela === 'login') { ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Entrar</h1>
                <p class="text-body-secondary">Entre para gerenciar os produtos.</p>
                <?php if ($erro !== '') { ?>
                    <div class="alert alert-danger" role="alert"><?= /* Exibe texto seguro no HTML. */ escapar($erro) ?></div>
                <?php } ?>
                <form method="post" action="operacoes.php">
                    <input type="hidden" name="acao" value="entrar">
                    <input type="hidden" name="token" value="<?= /* Exibe texto seguro no HTML. */ escapar($_SESSION['token']) ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required
                               autocomplete="username" value="<?= /* Exibe texto seguro no HTML. */ escapar($email) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required
                               autocomplete="current-password">
                    </div>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
                <a class="d-block mt-3" href="index.php">Consultar sem entrar</a>
            </div>
        </div>
    </div>
</div>
<?php } elseif ($tela === 'cadastro') { ?>

<div class="card shadow-sm mx-auto" style="max-width: 640px">
<div class="card-body p-4">
    <h1 class="h3 mb-4"><?= /* Exibe texto seguro no HTML. */ escapar($titulo) ?></h1>
    <form action="operacoes.php" method="post">
        <input type="hidden" name="acao" value="salvar">
        <input type="hidden" name="id" value="<?= /* Exibe texto seguro no HTML. */ escapar($produto['id']) ?>">
        <input type="hidden" name="token" value="<?= /* Exibe texto seguro no HTML. */ escapar($_SESSION['token']) ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input class="form-control" id="nome" name="nome" maxlength="100" required
                   value="<?= /* Exibe texto seguro no HTML. */ escapar($produto['nome']) ?>">
        </div>
        <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="number" class="form-control" id="preco" name="preco" min="0"
                   max="99999999.99" step="0.01" required value="<?= /* Exibe texto seguro no HTML. */ escapar($produto['preco']) ?>">
        </div>
        <div class="mb-4">
            <label for="quantidade" class="form-label">Quantidade em estoque</label>
            <input type="number" class="form-control" id="quantidade" name="quantidade"
                   min="0" max="4294967295" step="1" required value="<?= /* Exibe texto seguro no HTML. */ escapar($produto['quantidade']) ?>">
        </div>
        <button class="btn btn-primary">Salvar</button>
        <a class="btn btn-outline-secondary" href="index.php">Voltar</a>
    </form>
</div>
</div>
<?php } else { ?>

<div class="d-flex justify-content-between align-items-center mb-4 gap-3">
    <h1 class="h2 mb-0">Produtos em estoque</h1>
    <?php if ($logado) { ?>
        <a class="btn btn-primary" href="index.php?tela=cadastro">Cadastrar produto</a>
    <?php } ?>
</div>
<form action="index.php" method="get" class="d-flex gap-2 mb-4" role="search">
    <label for="busca" class="visually-hidden">Buscar produto por nome</label>
    <input class="form-control" type="search" id="busca" name="busca"
           placeholder="Buscar produto por nome" value="<?= /* Exibe texto seguro no HTML. */ escapar($busca) ?>">
    <button class="btn btn-primary">Buscar</button>
    <a class="btn btn-outline-secondary" href="index.php">Limpar</a>
</form>
<div class="card shadow-sm">
<div class="table-responsive">
<table class="table table-striped align-middle mb-0">
    <caption class="px-3"><?= /* Conta os resultados da busca. */ count($produtos) ?> produto(s) encontrado(s)</caption>
    <thead><tr>
        <th scope="col">ID</th><th scope="col">Nome</th>
        <th scope="col">Preço</th><th scope="col">Quantidade</th>
        <?php if ($logado) { ?><th scope="col">Ações</th><?php } ?>
    </tr></thead>
    <tbody>
    <?php foreach ($produtos as $produto) { ?>
        <tr>
            <td><?= /* Exibe texto seguro no HTML. */ escapar($produto['id']) ?></td>
            <td><?= /* Exibe texto seguro no HTML. */ escapar($produto['nome']) ?></td>
            <td class="text-nowrap">R$ <?= /* Formata o preço em reais. */ number_format((float) $produto['preco'], 2, ',', '.') ?></td>
            <td><?= /* Exibe texto seguro no HTML. */ escapar($produto['quantidade']) ?></td>
            <?php if ($logado) { ?>
                <td class="text-nowrap">
                    <a class="btn btn-outline-primary btn-sm"
                       href="index.php?tela=cadastro&id=<?= /* Exibe texto seguro no HTML. */ escapar($produto['id']) ?>">Alterar</a>
                    <button type="button" class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalExcluir"
                            data-id="<?= /* Exibe texto seguro no HTML. */ escapar($produto['id']) ?>"
                            data-nome="<?= /* Exibe texto seguro no HTML. */ escapar($produto['nome']) ?>">Excluir</button>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
    <?php if (!$produtos) { ?>
        <tr><td colspan="<?= $logado ? 5 : 4 ?>" class="text-center py-4">
            Nenhum produto encontrado.
        </td></tr>
    <?php } ?>
    </tbody>
</table>
</div>
</div>
<?php if ($logado) { ?>
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="tituloExcluir" aria-hidden="true">
    <div class="modal-dialog">
        <form action="operacoes.php" method="post" class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="tituloExcluir">Confirmar exclusão</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Excluir o produto <strong id="nomeExcluir"></strong>?
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" id="idExcluir">
                <input type="hidden" name="token" value="<?= /* Exibe texto seguro no HTML. */ escapar($_SESSION['token']) ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger">Confirmar exclusão</button>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('modalExcluir').addEventListener('show.bs.modal', function (evento) {
    const botao = evento.relatedTarget;
    document.getElementById('idExcluir').value = botao.dataset.id;
    document.getElementById('nomeExcluir').textContent = botao.dataset.nome;
});
</script>
<?php } ?>
<?php } ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### `operacoes.php`

Todos os formulários de manutenção e autenticação têm `action="operacoes.php"`. O formulário de busca continua enviando GET para `index.php`, pois só consulta dados.

```php
<?php
// Inicia a sessão antes de qualquer saída HTML.
session_start();

// Carrega as três classes usadas nesta página.
require_once __DIR__ . '/classes/Conexao.php';
require_once __DIR__ . '/classes/Login.php';
require_once __DIR__ . '/classes/Cadastro.php';

// Abre uma conexão e a compartilha com os dois objetos.
$pdo = (new Conexao())->conectar();
$login = new Login($pdo);
$cadastro = new Cadastro($pdo);

// Aceita operações somente quando enviadas pelos formulários POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Envie a operação pelo formulário.');
}

// Abre a página indicada e encerra o processamento atual.
function redirecionar(string $pagina = 'index.php'): void
{
    header('Location: ' . $pagina);
    exit;
}

$acao = $_POST['acao'] ?? '';

// Impede manutenção por visitantes, mesmo que enviem um POST manualmente.
if ($acao !== 'entrar') {
    $login->exigirLogin();
}

// Confere se o token recebido pertence à sessão atual.
$token = $_POST['token'] ?? '';
if (!isset($_SESSION['token']) || !is_string($token)
    || !hash_equals($_SESSION['token'], $token)) {
    http_response_code(403);
    exit('Formulário inválido. Volte e atualize a página.');
}

if ($acao === 'entrar') {
    // Retira espaços do e-mail e confere as credenciais no banco.
    $email = trim($_POST['email'] ?? '');
    if ($login->entrar($email, $_POST['senha'] ?? '')) {
        // Após entrar, volta para a listagem com as ações liberadas.
        redirecionar();
    }
    // Guarda o erro e o e-mail para exibir uma única vez na tela de login.
    $_SESSION['erro_login'] = 'E-mail ou senha incorretos.';
    $_SESSION['email_login'] = $email;
    redirecionar('index.php?tela=login');
} elseif ($acao === 'sair') {
    // Encerra a sessão e retorna à consulta pública.
    $login->sair();
    redirecionar();
} elseif ($acao === 'salvar') {
    $id = (int) ($_POST['id'] ?? 0);
    // Retira espaços e recebe o preço como texto decimal.
    $nome = trim($_POST['nome'] ?? '');
    $preco = $_POST['preco'] ?? '';
    // Aceita apenas quantidade inteira dentro dos limites da coluna.
    $quantidade = filter_var($_POST['quantidade'] ?? '', FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0, 'max_range' => 4294967295]]);

    // Confere somente as regras básicas de nome, preço e quantidade.
    if ($nome === '' || mb_strlen($nome) > 100
        || !preg_match('/^\d{1,8}(\.\d{1,2})?$/D', $preco)
        || $quantidade === false) {
        http_response_code(400);
        exit('Informe nome (até 100 caracteres), preço válido e quantidade inteira não negativa. Volte ao formulário.');
    }

    if ($id !== 0) {
        // Confere se o produto ainda existe antes de atualizar.
        if (!$cadastro->buscar($id)) {
            http_response_code(404);
            exit('Produto não encontrado.');
        }
        // Atualiza o registro identificado pelo ID.
        $cadastro->alterar($id, $nome, $preco, $quantidade);
    } else {
        // Insere um produto; o banco gera seu ID.
        $cadastro->cadastrar($nome, $preco, $quantidade);
    }
    // Evita repetir o envio ao atualizar o navegador.
    redirecionar();
} elseif ($acao === 'excluir') {
    // Exclui somente o ID enviado pela confirmação do modal.
    $cadastro->excluir((int) ($_POST['id'] ?? 0));
    redirecionar();
} else {
    http_response_code(400);
    exit('Ação desconhecida.');
}
```

O campo `acao` seleciona o método a chamar. Login pode ser enviado por um visitante; as demais ações exigem usuário autenticado. O acesso direto a `operacoes.php` por GET retorna 405 e não executa nenhuma operação.

Quando o login falha, o arquivo guarda a mensagem e o e-mail em `$_SESSION` e redireciona para a tela de login. O `index.php` recupera esses dados e os remove da sessão depois de recebê-los. A senha nunca é guardada nesse retorno.

### Login e sessão

`session_start()` recupera a sessão entre requisições. Ao receber o POST em `operacoes.php`, `entrar()` valida as credenciais; se funcionar, a página redireciona para a lista. A mensagem de erro não revela se o e-mail existe. O formulário mantém o e-mail e não preenche novamente a senha.

`$logado` recebe o resultado de `estaLogado()` para decidir quais botões mostrar. O nome do administrador aparece na barra superior. Sair envia POST, apaga a sessão e retorna à consulta pública.

### Lista e pesquisa

A pesquisa usa GET, permitindo compartilhar o endereço com o termo buscado. `listar()` consulta os dados e `foreach` monta as linhas. `escapar()` transforma caracteres especiais antes de colocar texto no HTML. `number_format()` apresenta o preço em reais, sem alterar o valor armazenado.

### Cadastro e edição no mesmo formulário

Sem ID, o formulário começa vazio, com quantidade zero. Com ID, `buscar()` preenche os mesmos campos. O campo oculto `id` informa qual operação realizar: zero ou vazio cria um produto; outro ID atualiza o registro existente.

`required`, `min`, `max` e `step` ajudam no navegador. O PHP também confere apenas as regras básicas: nome obrigatório até 100 caracteres, preço não negativo com até duas casas e quantidade inteira não negativa dentro do limite da coluna.

`filter_var()` retorna `false` quando a quantidade é inválida. A comparação `=== false` permite quantidade zero. O campo numérico de preço pode apresentar vírgula conforme o idioma do navegador, mas envia o valor com ponto ao PHP. Após salvar, o redirecionamento evita reenviar o formulário ao atualizar a página. Em erro de campo, use **Voltar** no navegador para corrigir.

### Exclusão com modal

Há um único modal para toda a tabela. Cada botão carrega o ID e o nome em `data-id` e `data-nome`. O evento `show.bs.modal` identifica o botão clicado e preenche a confirmação. `textContent` exibe o nome como texto.

**Cancelar** apenas fecha o modal. **Confirmar exclusão** envia POST com `acao=excluir`. O `operacoes.php` exige login antes de chamar `excluir()`. Abrir o modal ou acessar um endereço por GET não apaga produtos.

### Token dos formulários

Mantivemos uma proteção pequena: cada sessão possui um token aleatório, enviado nos campos ocultos. Antes de processar POST, o PHP confere esse token para impedir envios feitos por outra página em nome do usuário. O token não substitui a verificação de login. Os comentários de `escapar()` nos blocos HTML indicam que cada valor passa pela conversão antes de ser exibido.

## 8. Caminho dos dados

```text
Conexao::conectar() → PDO compartilhado por Login e Cadastro

POST operacoes.php acao=entrar  → Login::entrar() → sessão → listagem
GET busca=Cad    → Cadastro::listar() → tabela filtrada
GET tela=cadastro&id=1 → Cadastro::buscar() → formulário preenchido
POST operacoes.php acao=salvar → exige login → cadastrar() ou alterar() → listagem
POST operacoes.php acao=excluir → exige login → excluir() → listagem
POST operacoes.php acao=sair   → Login::sair() → consulta pública
```

O `index.php` escolhe a tela e consulta os dados para exibição. Os formulários enviam suas operações para `operacoes.php`, que chama as classes e redireciona. As classes concentram as responsabilidades de banco e autenticação.

## 9. Roteiro de teste no XAMPP

| Teste | Resultado esperado |
|---|---|
| Importar o SQL em um banco novo | Um administrador e quatro produtos |
| Importar novamente | Sem repetir o admin ou os exemplos com os mesmos nomes |
| Abrir a página sem login | Lista e pesquisa; sem ações de manutenção |
| Buscar `Cad` | Mostra Caderno |
| Buscar um nome inexistente | Nenhum produto encontrado |
| Limpar a busca | Mostra todos os produtos |
| Abrir `index.php?tela=cadastro` sem login | Redireciona para o login |
| Entrar com senha incorreta | Mensagem de credenciais incorretas |
| Entrar com `admin@estoque.local` e `Aula@123` | Nome Administrador e ações aparecem |
| Atualizar a página após entrar | Continua autenticado |
| Cadastrar Régua, preço 4.50 e quantidade 10 | Novo registro com ID próprio |
| Cadastrar produto com preço e quantidade zero | Aceita os dois zeros |
| Alterar a Régua para quantidade 8 | Mesmo ID e quantidade atualizada |
| Salvar sem modificar os campos | Não duplica o registro |
| Atualizar a lista depois de salvar | Não repete o envio |
| Abrir modal e cancelar | Mantém o produto |
| Abrir o modal de outro produto | Mostra o nome e ID da nova seleção |
| Confirmar exclusão | Remove somente o escolhido |
| Cadastrar nome `<b>Teste</b>` | Mostra texto literal, sem negrito |
| Sair e reabrir uma URL de edição | Solicita login |
| Acessar `index.php?acao=excluir&id=1` por GET | Não exclui nada |
| Abrir `operacoes.php` diretamente por GET | Resposta 405, sem alterar dados |
| Enviar manutenção por POST sem login | Redireciona para o login e não altera dados |
| Conferir `usuarios.senha` no phpMyAdmin | Contém hash, não a senha original |

Para observar a regra no servidor, remova temporariamente `min` pelo inspetor e envie quantidade negativa: o PHP deve responder 400. Alterar o token oculto deve produzir 403 sem gravar. Recarregue a página para continuar.

## 10. Problemas comuns

| Problema | Conferir |
|---|---|
| Página não encontrada | Pasta dentro de `htdocs`, extensão `.php` e porta do Apache |
| Falha de conexão | MySQL iniciado, banco importado e credenciais de `Conexao.php` |
| `could not find driver` | PHP do XAMPP e extensão `pdo_mysql` |
| `headers already sent` | Espaços, BOM ou HTML antes do início do PHP |
| Login não funciona | Importação do SQL e credenciais `admin@estoque.local / Aula@123` |
| Admin existente não aceita a senha padrão | Reimportar não redefine a senha de um usuário que já existia |
| Formulário inválido | Atualize a página e permita cookies da sessão |
| Modal não abre | Internet e carregamento do JavaScript Bootstrap |
| Tela sem estilos | Internet e endereço do CSS Bootstrap |
| Erro de sintaxe nos tipos ou no match | Use PHP 8 ou superior |

No Windows, o registro do Apache costuma estar em `C:\xampp\apache\logs\error.log`. Também pode ser aberto pelo botão **Logs** do painel.

## 11. Exercícios de continuação

1. Explique por que esconder um botão não impede um POST manual.
2. Mostre **Sem estoque** ao lado da Borracha e dos demais produtos com quantidade zero.
3. Acrescente descrição ao banco, formulário, classe e listagem.
4. Mostre mensagens de sucesso depois de salvar ou excluir.
5. Crie uma tela autenticada para adicionar usuários com `password_hash()`.
6. Evolua para um histórico de entradas e saídas, estudando transações para atualizar o estoque e registrar a movimentação juntos.

## Referências

- [PHP: classes e objetos](https://www.php.net/manual/pt_BR/language.oop5.basic.php)
- [PHP: PDO e parâmetros](https://www.php.net/manual/pt_BR/pdo.prepare.php)
- [PHP: password_hash](https://www.php.net/manual/pt_BR/function.password-hash.php) e [password_verify](https://www.php.net/manual/pt_BR/function.password-verify.php)
- [PHP: sessões](https://www.php.net/manual/pt_BR/book.session.php)
- [Bootstrap: introdução](https://getbootstrap.com/docs/5.3/getting-started/introduction/) e [modal](https://getbootstrap.com/docs/5.3/components/modal/)

Este projeto é um exercício local no XAMPP, com usuário e senha demonstrativos.
