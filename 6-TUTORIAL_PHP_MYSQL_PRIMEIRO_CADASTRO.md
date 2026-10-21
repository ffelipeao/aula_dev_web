# Primeira demonstração: cadastro de alunos com PHP e MySQL no XAMPP

## Objetivo da aula

Construir um formulário HTML que envia nome, e-mail e curso para `salvar.php` pelo método POST. O PHP recebe os dados e executa um INSERT na tabela `alunos`. Depois, vamos criar uma tela PHP para listar os alunos cadastrados em uma tabela HTML. O phpMyAdmin tamb?m poder? ser usado para conferir os registros.

Pré-requisitos: XAMPP instalado, editor de texto e navegador. Não é necessário instalar bibliotecas nem usar terminal.

Fluxo da aplicação:

```text
index.html → POST → salvar.php → conexao.php → banco escola / tabela alunos
                       ↓ depois de gravar
                  sucesso.html ? listar.php ? SELECT na tabela alunos
```

O HTML apresenta o formulário, o Apache executa o PHP e o MySQL armazena os dados. O phpMyAdmin é uma interface para administrar o banco. O XAMPP inclui MariaDB, compatível com o SQL deste exercício, embora o painel use o nome MySQL. Veja a [documentação oficial do XAMPP](https://www.apachefriends.org/faq_windows).

## 1. Iniciar o XAMPP e preparar a pasta

1. Abra o painel do XAMPP.
2. Inicie **Apache** e **MySQL** usando **Start**. No macOS, use o gerenciador de servidores do XAMPP.
3. Crie a pasta `php-mysql-alunos` dentro da pasta `htdocs` do XAMPP.

Caminho comum no Windows:

```text
C:\xampp\htdocs\php-mysql-alunos
```

No macOS, o caminho comum é `/Applications/XAMPP/xamppfiles/htdocs/php-mysql-alunos`. Se o XAMPP estiver instalado em outro lugar, procure sua pasta `htdocs`.

Copie os arquivos da [pasta do exemplo](php-mysql-alunos) para essa pasta, ou crie-os acompanhando os passos abaixo. Salve em UTF-8 e confira se o editor não adicionou `.txt` aos nomes.

```text
php-mysql-alunos/
├── index.html
├── conexao.php
├── salvar.php
├── sucesso.html
├── listar.php
└── banco.sql
```

## 2. Criar o banco e a tabela no phpMyAdmin

1. Com Apache e MySQL iniciados, abra `http://localhost/phpmyadmin`.
2. Clique na aba **SQL** da página inicial.
3. Cole o código abaixo e clique em **Executar** (ou **Continuar**, conforme a versão).

```sql
CREATE DATABASE IF NOT EXISTS escola
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE escola;

CREATE TABLE IF NOT EXISTS alunos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    curso VARCHAR(100) NOT NULL
);
```

Você também pode usar a aba **Importar**, escolher `banco.sql` e executar a importação. Escolha apenas uma das formas.

Na lateral esquerda, abra o banco **escola** e depois a tabela **alunos**. Se necessário, atualize a lista de bancos.

| Trecho | Significado |
| --- | --- |
| `CREATE DATABASE` | Cria o banco de dados. |
| `USE escola` | Seleciona o banco onde a tabela será criada. |
| `CREATE TABLE` | Cria a tabela que guarda os alunos. |
| `id` | Identifica cada aluno. |
| `AUTO_INCREMENT` | Gera o próximo número do ID automaticamente. |
| `PRIMARY KEY` | Define o identificador único de cada linha. |
| `VARCHAR(100)` | Guarda texto com até 100 caracteres. |
| `NOT NULL` | Não permite o valor SQL NULL; texto vazio é tratado pela validação no PHP. |
| `utf8mb4` | Permite armazenar acentos e outros caracteres Unicode. |

`IF NOT EXISTS` permite repetir a criação sem apagar dados existentes. Ele não corrige a estrutura de uma tabela criada anteriormente com outros campos.

## 3. Criar o formulário: index.html

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de alunos</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; margin: 0; padding: 24px; }
        main { max-width: 440px; margin: 32px auto; padding: 24px; background: white; border-radius: 12px; }
        h1 { color: #16324f; }
        label { display: block; margin-top: 16px; }
        input, button { box-sizing: border-box; width: 100%; padding: 12px; margin-top: 6px; font: inherit; }
        input { border: 1px solid #8795a5; border-radius: 6px; }
        button { margin-top: 24px; background: #185abc; color: white; border: 0; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>
    <main>
        <h1>Cadastro de alunos</h1>
        <p>Preencha os dados para cadastrar um aluno.</p>
        <form action="salvar.php" method="post">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" maxlength="100" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" maxlength="150" required>

            <label for="curso">Curso</label>
            <input type="text" id="curso" name="curso" maxlength="100" required>

            <button type="submit">Cadastrar aluno</button>
        </form>
        <p><a href="listar.php">Ver alunos cadastrados</a></p>
    </main>
</body>
</html>
```

Observe a linha `<form action="salvar.php" method="post">`:

- `action` indica o arquivo que receberá os dados.
- `method="post"` envia os campos no corpo da requisição HTTP.
- `name="nome"`, `name="email"` e `name="curso"` definem as chaves recebidas no PHP.
- `id` associa o campo ao seu `label`; é o `name` que identifica o dado enviado.
- `required` e `type="email"` ajudam a validar o preenchimento no navegador.
- `type="submit"` faz o botão enviar o formulário.

POST não criptografa os dados. Neste exercício usaremos o servidor local.

## 4. Criar a conexão: conexao.php

```php
<?php
// Configuração para o XAMPP local. Ajuste se tiver definido outra senha ou porta.
$servidor = '127.0.0.1';
$usuario = 'root';
$senha = '';
$banco = 'escola';
$porta = 3306;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexao = new mysqli($servidor, $usuario, $senha, $banco, $porta);
    $conexao->set_charset('utf8mb4');
} catch (mysqli_sql_exception $erro) {
    error_log($erro->getMessage());
    http_response_code(500);
    exit('Falha na conexão. Confira o MySQL, o banco escola e os dados de conexao.php.');
}
```

`new mysqli(...)` abre a conexão com o servidor e seleciona `escola`. `set_charset` define a codificação usada na comunicação. `try/catch` trata falhas de conexão.

O exemplo usa o usuário local `root`, senha vazia e porta `3306`. Se você configurou uma senha ou outra porta, ajuste esses valores. Esses dados servem para a demonstração local; aplicações publicadas devem usar um usuário próprio com as permissões necessárias.

## 5. Receber o POST e gravar: salvar.php

```php
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
```

Leia o arquivo com a turma em quatro partes:

1. **Receber:** `$_POST['nome']` acessa o valor enviado pelo campo `name="nome"`. `?? ''` fornece texto vazio se o campo estiver ausente.
2. **Validar:** `trim` remove espaços das extremidades; o PHP verifica os campos obrigatórios. No navegador, `required`, `maxlength` e `type="email"` orientam o preenchimento. A validação é reduzida para focar no cadastro e na consulta nesta aula local.
3. **Gravar:** `INSERT INTO alunos (nome, email, curso)` indica a tabela e as colunas. O ID não é enviado porque o banco o gera.
4. **Responder:** após salvar, o PHP redireciona para a página de sucesso. Atualizar essa página não envia novamente o formulário.

`prepare` prepara o SQL, `bind_param('sss', ...)` associa três valores do tipo string aos três `?`, na mesma ordem, e `execute` executa a inserção. Isso mantém os dados separados do SQL e evita SQL injection nesse comando. Não monte o INSERT concatenando os valores recebidos. Veja o [manual de instruções preparadas do PHP](https://www.php.net/manual/pt_BR/mysqli.quickstart.prepared-statements.php).

## 6. Criar a resposta: sucesso.html

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aluno cadastrado</title>
</head>
<body>
    <h1>Aluno cadastrado com sucesso!</h1>
    <p>O cadastro foi gravado no banco de dados.</p>
    <p><a href="index.html">Cadastrar outro aluno</a></p>
    <p><a href="listar.php">Ver alunos cadastrados</a></p>
</body>
</html>
```

## 7. Criar a tela de listagem: listar.php

Crie `listar.php` na mesma pasta dos outros arquivos. A página reutiliza a conexão, consulta os registros e monta uma tabela HTML.

```php
<?php
require __DIR__ . '/conexao.php';

// Lista primeiro os cadastros mais recentes.
$sql = 'SELECT id, nome, email, curso FROM alunos ORDER BY id DESC';
$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alunos cadastrados</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 24px; }
        main { max-width: 900px; margin: auto; padding: 24px; background: white; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #8795a5; padding: 12px; text-align: left; }
        th { background: #e8eef5; }
    </style>
</head>
<body>
    <main>
        <h1>Alunos cadastrados</h1>
        <p><a href="index.html">Cadastrar aluno</a></p>

        <?php if ($resultado->num_rows === 0): ?>
            <p>Nenhum aluno cadastrado ainda.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Curso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($aluno = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= $aluno['id'] ?></td>
                            <td><?= htmlspecialchars($aluno['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($aluno['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($aluno['curso'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
<?php
$conexao->close();
?>
```

Leia o código em etapas:

1. `require` carrega `conexao.php`, assim como no cadastro.
2. `SELECT` busca as quatro colunas. `ORDER BY id DESC` mostra os maiores IDs primeiro, começando pelos cadastros mais recentes.
3. `query($sql)` executa a consulta. Como o SQL é fixo e não recebe dados do usuário, usamos `query` diretamente.
4. `num_rows` informa quantos registros foram encontrados. Se for zero, mostramos a mensagem de lista vazia.
5. `while` repete uma linha da tabela para cada aluno. `fetch_assoc()` lê o próximo registro como um array com chaves como `nome`, `email` e `curso`. Quando não há mais registros, o laço termina.
6. `<?= ... ?>` é uma forma curta de escrever `<?php echo ...; ?>`. `htmlspecialchars` exibe os textos como conteúdo, evitando que sejam interpretados como HTML.

`if (...): ... else: ... endif;` e `while (...): ... endwhile;` são formas de escrever condições e repetições quando misturamos PHP com HTML.

O PHP monta a tabela no servidor e envia o HTML pronto ao navegador. Os dados são consultados novamente sempre que você abre ou atualiza essa página.

## 8. Executar a demonstração

1. Acesse **http://localhost/php-mysql-alunos/index.html**.
2. Preencha, por exemplo:

   | Campo | Valor |
   | --- | --- |
   | Nome | Ana Souza |
   | E-mail | ana@example.com |
   | Curso | Desenvolvimento Web |

3. Clique em **Cadastrar aluno**.
4. Confira a mensagem **Aluno cadastrado com sucesso!**.
5. Clique em **Ver alunos cadastrados** e confira a tabela com ID, nome, e-mail e curso.
6. Clique em **Cadastrar aluno**, cadastre outra pessoa e volte à listagem. O novo registro aparecerá primeiro.
7. Se quiser conferir pelo banco, abra o phpMyAdmin e selecione **escola → alunos → Visualizar** (ou **Procurar**).

Acesse também **http://localhost/php-mysql-alunos/listar.php** diretamente. Antes do primeiro cadastro, confira a mensagem **Nenhum aluno cadastrado ainda.**

Para consultar usando SQL, selecione o banco `escola`, abra a aba **SQL** e execute:

```sql
SELECT id, nome, email, curso FROM alunos ORDER BY id DESC;
```

Abra pelo endereço `http://localhost/...`, não por duplo clique no arquivo nem pelo Live Server do editor: o PHP precisa ser executado pelo Apache. Esta aplicação também não funciona no GitHub Pages, que não executa PHP nem hospeda esse banco.

O exemplo permite repetir um e-mail: cada envio válido cria um novo aluno. A atividade inclui cadastro e listagem; edição e exclusão podem ser trabalhadas em outra aula.

## 9. Problemas comuns

| Problema | O que conferir |
| --- | --- |
| `localhost` não abre | Veja se Apache está iniciado. Se usa outra porta, inclua-a na URL, como `http://localhost:8080/php-mysql-alunos/`. |
| Página não encontrada (404) | Confira se a pasta está dentro de `htdocs` e se o endereço e os nomes estão corretos. |
| Falha na conexão | Inicie MySQL e confira banco, usuário, senha e porta em `conexao.php`. |
| Não foi possível salvar | Confirme que `escola.alunos` existe com os campos do script. Consulte o log de erros do Apache para o detalhe técnico. |
| `Class "mysqli" not found` | Use o PHP do XAMPP e confira se a extensão mysqli está habilitada nele. |
| O código PHP aparece como texto | Confirme a extensão `.php` e o acesso via Apache em `localhost`. |
| A mensagem pede para preencher todos os campos | Preencha nome, e-mail e curso antes de enviar. |
| A listagem aparece vazia | Cadastre um aluno e confira se `salvar.php` e `listar.php` usam o mesmo `conexao.php`. |
| Acesso direto a `salvar.php` mostra uma mensagem | Esse arquivo aceita POST. Abra `index.html` e use o botão do formulário. |
| Apache ou MySQL não inicia | Consulte o log do painel para identificar conflitos de porta com outros serviços. |

## 10. Exercício de fixação

Acrescente o campo **turma** ao cadastro. Os alunos deverão alterar a tabela, o formulário, a gravação e a listagem:

1. No phpMyAdmin, com `escola` selecionado, execute `ALTER TABLE alunos ADD turma VARCHAR(50) NOT NULL DEFAULT '';`.
2. Adicione ao formulário um campo com `name="turma"` e limite de 50 caracteres.
3. Receba `$_POST['turma']` e valide o conteúdo no PHP.
4. Inclua `turma` na lista de colunas do INSERT e acrescente um quarto `?`.
5. Use `bind_param('ssss', $nome, $email, $curso, $turma)`.
6. Em `listar.php`, acrescente `turma` ao SELECT, crie o cabe?alho **Turma** e exiba o valor com `htmlspecialchars`.
7. Cadastre um aluno e confira a turma na tela de listagem.

Pergunta para fechar a aula: por que colocar apenas o campo no HTML não faz o valor ser salvo no banco?
