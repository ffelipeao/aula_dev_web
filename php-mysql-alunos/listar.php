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
