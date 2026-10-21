<?php
header('Content-Type: text/html; charset=utf-8');

// Este arquivo recebe os dados enviados pelo formulário.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Acesse index.html e envie o formulário para cadastrar um aluno.');
}

// name="nome" no HTML corresponde a $_POST['nome'] no PHP.
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$curso = $_POST['curso'] ?? '';

// Verificação simples dos campos obrigatórios.
if (trim($nome) === '' || trim($email) === '' || trim($curso) === '') {
    exit('Preencha todos os campos. Volte ao formulário.');
}

require __DIR__ . '/conexao.php';

try {
    // Os ? são espaços reservados para os dados, separados do comando SQL.
    $sql = 'INSERT INTO alunos (nome, email, curso) VALUES (?, ?, ?)';
    $comando = $conexao->prepare($sql);
    $comando->bind_param('sss', $nome, $email, $curso);
    $comando->execute();
    $comando->close();
    $conexao->close();
} catch (mysqli_sql_exception $erro) {
    error_log($erro->getMessage());
    http_response_code(500);
    exit('Não foi possível salvar o aluno. Confira a tabela alunos e o log de erros do Apache.');
}

// O redirecionamento evita repetir o INSERT ao atualizar a página de sucesso.
header('Location: sucesso.html', true, 303);
exit;
