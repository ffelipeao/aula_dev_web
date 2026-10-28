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
$login->exigirLogin();

// Confere se o token recebido pertence à sessão atual.
$token = $_POST['token'] ?? '';
if (!isset($_SESSION['token']) || !is_string($token)
    || !hash_equals($_SESSION['token'], $token)) {
    http_response_code(403);
    exit('Formulário inválido. Volte e atualize a página.');
}

if ($acao === 'sair') {
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
