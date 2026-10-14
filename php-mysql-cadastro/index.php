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
