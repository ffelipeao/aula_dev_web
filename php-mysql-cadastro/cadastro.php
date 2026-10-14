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
