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
