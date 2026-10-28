# Tutorial 9 — PHP e MySQL com o template gratuito SB Admin 2

Neste tutorial, vamos adaptar o sistema do [tutorial 8](8-TUTORIAL_PHP_MYSQL_ESTOQUE_CLASSES_LOGIN.md) para usar o [SB Admin 2 gratuito](https://startbootstrap.com/theme/sb-admin-2). A versão básica permanece na pasta `php-mysql-estoque`, usando Bootstrap 5. A versão com template está em [php-mysql-estoque-sb-admin-2](php-mysql-estoque-sb-admin-2).

## 1. Preparar a versão com template

1. Conclua o tutorial 8 para conhecer conexão, login e classe de produtos.
2. Copie a pasta pronta `php-mysql-estoque-sb-admin-2` para o `htdocs` do XAMPP. Para migrar manualmente, duplique `php-mysql-estoque` com esse novo nome e siga as etapas abaixo.
3. Mantenha o MySQL iniciado. Se ainda não criou o banco, importe `banco.sql` pelo phpMyAdmin.
4. Pelo Apache, abra **http://localhost/php-mysql-estoque-sb-admin-2/**.
5. Como alternativa, entre na pasta da nova versão e execute `php -S localhost:8000`. Nesse caso, abra **http://localhost:8000/**. Consulte os caminhos do executável PHP no tutorial 8.

**Administrador inicial:** e-mail **admin@estoque.local**, senha **Aula@123**.

As duas versões usam o banco `aula_estoque`. Produtos cadastrados, alterados ou excluídos em uma aparecem também na outra. Para trabalhar com bancos independentes, altere o nome no SQL antes da importação e na variável `$banco` da respectiva `Conexao.php`. Reimportar o SQL não redefine a senha de um administrador existente.

### Opção rápida: clonar somente a pasta deste projeto

Com o **Git instalado**, abra o terminal na pasta em que deseja guardar o projeto e execute:

```sh
git clone --depth 1 --filter=blob:none --no-checkout https://github.com/ffelipeao/aula_dev_web.git aula-estoque-9
cd aula-estoque-9
git sparse-checkout set --no-cone "/php-mysql-estoque-sb-admin-2/"
git checkout
cd php-mysql-estoque-sb-admin-2
```

O clone parcial evita baixar de início o conteúdo dos arquivos; o `sparse-checkout` seleciona somente a pasta **`php-mysql-estoque-sb-admin-2`**, incluindo suas subpastas. `--depth 1` limita o histórico ao estado mais recente. A pasta `aula-estoque-9` guarda os metadados do Git e a pasta do projeto selecionado. Escolha uma pasta de destino que ainda não exista. Os comandos usam HTTPS e não exigem configurar uma chave SSH para um repositório público.

Depois, mantenha o **MySQL iniciado**, importe o `banco.sql` desta pasta no phpMyAdmin e ajuste as variáveis de `classes/Conexao.php` se necessário. Ainda dentro da pasta `php-mysql-estoque-sb-admin-2`, execute:

```sh
php -S localhost:8000
```

Abra **http://localhost:8000/** e entre com **admin@estoque.local**, senha **Aula@123**. Se `php` não estiver no PATH, use o caminho do executável do XAMPP indicado no tutorial 8. Encerre o servidor com **Ctrl+C**.

Para usar o Apache, copie a pasta interna `php-mysql-estoque-sb-admin-2` para `htdocs` e abra **http://localhost/php-mysql-estoque-sb-admin-2/**. Não é necessário copiar a pasta externa `aula-estoque-9`. Se o Git não reconhecer `sparse-checkout`, atualize-o antes de executar estes comandos.

## 2. O que muda

A mudança está na apresentação do `index.php` e nos arquivos da pasta `assets`. Os arquivos `banco.sql`, `login.php`, `operacoes.php` e as três classes permanecem iguais aos do tutorial 8. O formulário continua enviando o login para `login.php` e a manutenção para `operacoes.php`.

```text
php-mysql-estoque-sb-admin-2/
├── banco.sql
├── index.php
├── login.php
├── operacoes.php
├── classes/
│   ├── Conexao.php
│   ├── Login.php
│   └── Cadastro.php
└── assets/
    └── sb-admin-2/
```

## 3. Instalar e entender o template

Usaremos o [SB Admin 2 gratuito da Start Bootstrap](https://startbootstrap.com/theme/sb-admin-2), versão **4.1.4**, distribuído sob licença MIT. A interface foi adaptada com menu lateral, barra superior, cartões, tela de login e tabela de produtos. A pesquisa continua sendo feita pelo PHP/MySQL.

O template usa **Bootstrap 4 e jQuery**. O arquivo `sb-admin-2.min.css` já inclui os estilos do Bootstrap; não acrescente Bootstrap 5 à página. O modal usa `data-toggle`, `data-target` e `data-dismiss`, além de evento `show.bs.modal` registrado com jQuery.

Os arquivos necessários já estão em `php-mysql-estoque-sb-admin-2/assets/sb-admin-2`. Copie essa pasta junto do projeto para o XAMPP ou para a pasta em que executará `php -S localhost:8000`. Não é preciso instalar npm nem compilar o tema, e os estilos, ícones e scripts funcionam sem internet.

```text
assets/sb-admin-2/
├── LICENSE
├── ORIGEM.md
├── css/sb-admin-2.min.css
├── js/sb-admin-2.min.js
└── vendor/
    ├── bootstrap/js/
    ├── fontawesome-free/  (CSS e fontes dos ícones)
    ├── jquery/
    └── jquery-easing/
```

Para obter os arquivos novamente, baixe a versão 4.1.4 no [repositório oficial](https://github.com/StartBootstrap/startbootstrap-sb-admin-2/releases/tag/v4.1.4), extraia o ZIP e copie as pastas indicadas acima. Preserve o arquivo `LICENSE`. Não é necessário copiar as páginas HTML demonstrativas, gráficos ou DataTables para este exercício.

Os scripts são carregados nesta ordem: **jQuery → Bootstrap bundle → jQuery Easing → SB Admin 2 → evento do modal**. O menu lateral pode ser recolhido, inclusive pelo botão no topo em telas pequenas. A tela de login usa o fundo em degradê e o cartão central do tema.

## 4. Substituir o index.php

Na cópia destinada ao template, substitua o `index.php` pelo código abaixo. Os comentários PHP foram mantidos.

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
    <!-- O CSS do template já inclui os estilos do Bootstrap 4. -->
    <link href="assets/sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top" class="<?= $tela === 'login' ? 'bg-gradient-primary' : '' ?>">
<?php if ($tela !== 'login') { ?>
<div id="wrapper">
    <!-- Menu lateral do SB Admin 2. -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <li>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <span class="sidebar-brand-icon"><i class="fas fa-boxes" aria-hidden="true"></i></span>
                <span class="sidebar-brand-text mx-3">Estoque</span>
            </a>
        </li>
        <li><hr class="sidebar-divider my-0"></li>
        <li class="nav-item <?= $tela === 'lista' ? 'active' : '' ?>">
            <a class="nav-link" href="index.php"><i class="fas fa-list" aria-hidden="true"></i> <span>Produtos</span></a>
        </li>
        <?php if ($logado) { ?>
        <li class="nav-item <?= $tela === 'cadastro' ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?tela=cadastro"><i class="fas fa-plus" aria-hidden="true"></i> <span>Novo produto</span></a>
        </li>
        <?php } ?>
        <li class="text-center d-none d-md-block">
            <button type="button" class="rounded-circle border-0" id="sidebarToggle" aria-label="Recolher ou expandir menu"></button>
        </li>
    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button type="button" id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" aria-label="Abrir ou fechar menu">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
                <span class="font-weight-bold text-primary">Controle de estoque</span>
                <div class="ml-auto d-flex align-items-center">
                    <?php if ($logado) { ?>
                        <span class="mr-3 small text-gray-600"><?= /* Exibe o nome do usuário. */ escapar($_SESSION['usuario']['nome']) ?></span>
                        <form action="operacoes.php" method="post">
                            <input type="hidden" name="acao" value="sair">
                            <input type="hidden" name="token" value="<?= /* Protege o valor exibido no HTML. */ escapar($_SESSION['token']) ?>">
                            <button class="btn btn-outline-primary btn-sm">Sair</button>
                        </form>
                    <?php } else { ?>
                        <a class="btn btn-primary btn-sm" href="index.php?tela=login">Entrar</a>
                    <?php } ?>
                </div>
            </nav>
            <main class="container-fluid pb-4">
<?php } else { ?>
<main class="container py-5">
<?php } ?>
<?php if ($tela === 'login') { ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <div class="card border-0 shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4"><i class="fas fa-boxes fa-3x text-primary mb-3" aria-hidden="true"></i><h1 class="h4 text-gray-900">Entrar</h1></div>
                <p class="text-muted">Entre para gerenciar os produtos.</p>
                <?php if ($erro !== '') { ?>
                    <div class="alert alert-danger" role="alert"><?= /* Exibe texto seguro no HTML. */ escapar($erro) ?></div>
                <?php } ?>
                <form class="user" method="post" action="login.php">
                    <input type="hidden" name="token" value="<?= /* Exibe texto seguro no HTML. */ escapar($_SESSION['token']) ?>">
                    <div class="mb-3">
                        <label for="email" class="font-weight-bold">E-mail</label>
                        <input type="email" class="form-control form-control-user" id="email" name="email" required
                               autocomplete="username" value="<?= /* Exibe texto seguro no HTML. */ escapar($email) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="font-weight-bold">Senha</label>
                        <input type="password" class="form-control form-control-user" id="senha" name="senha" required
                               autocomplete="current-password">
                    </div>
                    <button class="btn btn-primary btn-user btn-block">Entrar</button>
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
            <label for="nome" class="font-weight-bold">Nome</label>
            <input class="form-control" id="nome" name="nome" maxlength="100" required
                   value="<?= /* Exibe texto seguro no HTML. */ escapar($produto['nome']) ?>">
        </div>
        <div class="mb-3">
            <label for="preco" class="font-weight-bold">Preço (R$)</label>
            <input type="number" class="form-control" id="preco" name="preco" min="0"
                   max="99999999.99" step="0.01" required value="<?= /* Exibe texto seguro no HTML. */ escapar($produto['preco']) ?>">
        </div>
        <div class="mb-4">
            <label for="quantidade" class="font-weight-bold">Quantidade em estoque</label>
            <input type="number" class="form-control" id="quantidade" name="quantidade"
                   min="0" max="4294967295" step="1" required value="<?= /* Exibe texto seguro no HTML. */ escapar($produto['quantidade']) ?>">
        </div>
        <button class="btn btn-primary">Salvar</button>
        <a class="btn btn-outline-secondary" href="index.php">Voltar</a>
    </form>
</div>
</div>
<?php } else { ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h1 class="h2 mb-0">Produtos em estoque</h1>
    <?php if ($logado) { ?>
        <a class="btn btn-primary" href="index.php?tela=cadastro">Cadastrar produto</a>
    <?php } ?>
</div>
<form action="index.php" method="get" class="d-flex mb-4" role="search">
    <label for="busca" class="sr-only">Buscar produto por nome</label>
    <input class="form-control mr-2" type="search" id="busca" name="busca"
           placeholder="Buscar produto por nome" value="<?= /* Exibe texto seguro no HTML. */ escapar($busca) ?>">
    <button class="btn btn-primary mr-2">Buscar</button>
    <a class="btn btn-outline-secondary" href="index.php">Limpar</a>
</form>
<div class="card shadow-sm">
<div class="table-responsive">
<table class="table table-striped  mb-0">
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
                            data-toggle="modal" data-target="#modalExcluir"
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
                <h2 class="modal-title h5" id="tituloExcluir">Confirmar exclusão</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Excluir o produto <strong id="nomeExcluir"></strong>?
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" id="idExcluir">
                <input type="hidden" name="token" value="<?= /* Exibe texto seguro no HTML. */ escapar($_SESSION['token']) ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger">Confirmar exclusão</button>
            </div>
        </form>
    </div>
</div>

<?php } ?>
<?php } ?>
</main>
<?php if ($tela !== 'login') { ?>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto text-center small">Controle de estoque · Aula de PHP</div>
        </footer>
    </div>
</div>
<?php } ?>
<!-- Carrega as dependências locais na ordem exigida pelo template. -->
<script src="assets/sb-admin-2/vendor/jquery/jquery.min.js"></script>
<script src="assets/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="assets/sb-admin-2/js/sb-admin-2.min.js"></script>
<script>
// O Bootstrap 4 dispara os eventos do modal pelo jQuery.
$('#modalExcluir').on('show.bs.modal', function (evento) {
    const botao = evento.relatedTarget;
    document.getElementById('idExcluir').value = botao.dataset.id;
    document.getElementById('nomeExcluir').textContent = botao.dataset.nome;
});
</script>
</body>
</html>
```

## 5. Entender a adaptação

- **Menu lateral:** usa `sidebar`, `bg-gradient-primary` e os botões do próprio SB Admin 2 para recolher ou expandir. O item Novo produto aparece apenas com login.
- **Barra superior:** mostra a identificação do sistema, o usuário autenticado e o formulário de saída.
- **Login:** mantém os campos PHP e o envio para `login.php`, usando o cartão, o fundo e a classe `user` do tema.
- **Lista e formulário:** mantêm a pesquisa no servidor, o formulário compartilhado para cadastro/edição e os mesmos nomes de campos.
- **Modal:** usa os atributos do Bootstrap 4: `data-toggle`, `data-target` e `data-dismiss`. O evento é registrado com `$('#modalExcluir').on('show.bs.modal', ...)`, após carregar jQuery e Bootstrap.

Na versão básica do tutorial 8, o modal usa Bootstrap 5 e eventos nativos. Nesta versão, substituímos esses atributos e o registro do evento para acompanhar o Bootstrap 4 do template. Não misture os arquivos das duas versões na mesma página.

## 6. Testar a migração

| Teste | Resultado esperado |
|---|---|
| Abrir sem login | Menu lateral, tabela e busca pública; sem ações de manutenção |
| Pesquisar parte do nome | Lista filtrada pelo PHP/MySQL |
| Abrir Entrar | Cartão central sobre fundo azul |
| Entrar com o administrador | Nome e ações de manutenção aparecem |
| Cadastrar e alterar | Mesmo formulário e registros preservados |
| Abrir modal e cancelar | Produto permanece |
| Confirmar exclusão | Remove somente o produto exibido no modal |
| Alternar entre botões de exclusão | Modal atualiza nome e ID |
| Recolher o menu em tela larga e estreita | Conteúdo permanece acessível |
| Sair e abrir uma URL de edição | Solicita login |
| Abrir com a internet desligada | Estilos, ícones e scripts locais continuam disponíveis |

Repita também o roteiro funcional do tutorial 8. No navegador, confira se os arquivos da pasta `assets` retornam sem erro. Se o modal não abrir, confira a ordem dos scripts e os atributos do Bootstrap 4.

## 7. Exercícios

1. Personalize o nome e o ícone da barra lateral.
2. Destaque produtos com quantidade zero usando as classes do template.
3. Acrescente mensagens de sucesso dentro de um `alert` depois das operações.
4. Compare o código do modal com a versão Bootstrap 5 do tutorial 8.

A licença do template e os dados de origem estão em `assets/sb-admin-2/LICENSE` e `assets/sb-admin-2/ORIGEM.md`. Preserve os avisos de licença ao redistribuir o projeto.

Referências: [SB Admin 2](https://startbootstrap.com/theme/sb-admin-2), [repositório oficial](https://github.com/StartBootstrap/startbootstrap-sb-admin-2) e [modal do Bootstrap 4](https://getbootstrap.com/docs/4.6/components/modal/).
