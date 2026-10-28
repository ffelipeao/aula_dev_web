<?php
// Recupera a sessão para conferir o formulário e registrar o usuário.
session_start();

// Aceita somente o envio do formulário de login.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Envie os dados pelo formulário de login.');
}

// Confere se o formulário pertence à sessão atual.
$token = $_POST['token'] ?? '';
if (!isset($_SESSION['token']) || !is_string($token)
    || !hash_equals($_SESSION['token'], $token)) {
    http_response_code(403);
    exit('Formulário inválido. Volte e atualize a página.');
}

// Carrega apenas as classes necessárias à autenticação.
require_once __DIR__ . '/classes/Conexao.php';
require_once __DIR__ . '/classes/Login.php';

// Abre a conexão e a entrega ao objeto responsável pelo login.
$pdo = (new Conexao())->conectar();
$login = new Login($pdo);

// Retira os espaços das extremidades do e-mail informado.
$email = trim($_POST['email'] ?? '');

// Confere as credenciais e grava a identidade na sessão quando corretas.
if ($login->entrar($email, $_POST['senha'] ?? '')) {
    // Após autenticar, abre a listagem com as ações de manutenção.
    header('Location: index.php');
    exit;
}

// Guarda somente o erro e o e-mail para o retorno; não guarda a senha.
$_SESSION['erro_login'] = 'E-mail ou senha incorretos.';
$_SESSION['email_login'] = $email;

// Retorna ao formulário para permitir uma nova tentativa.
header('Location: index.php?tela=login');
exit;
