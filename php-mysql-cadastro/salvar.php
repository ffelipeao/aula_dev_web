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
