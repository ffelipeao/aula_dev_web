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

if (!is_string($nome) || !is_string($email) || !is_string($curso)) {
    http_response_code(400);
    exit('Dados inválidos. Volte ao formulário.');
}

$nome = trim($nome);
$email = trim($email);
$curso = trim($curso);

// A validação do PHP também é necessária: o HTML pode ser contornado.
$tamanhoNome = preg_match_all('/./us', $nome);
$tamanhoCurso = preg_match_all('/./us', $curso);
if ($tamanhoNome === false || $tamanhoNome < 1 || $tamanhoNome > 100
    || $tamanhoCurso === false || $tamanhoCurso < 1 || $tamanhoCurso > 100
    || strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Confira os campos: nome e curso são obrigatórios (até 100 caracteres) e o e-mail deve ser válido (até 150).');
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
