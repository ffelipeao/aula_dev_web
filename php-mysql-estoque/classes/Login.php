<?php
class Login
{
    private PDO $pdo;

    // Recebe a conexão pronta e a guarda para os outros métodos.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Confere e-mail e senha; retorna true quando o login funciona.
    public function entrar(string $email, string $senha): bool
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$email]);
        // Lê a linha do usuário ou recebe false quando não há resultado.
        $usuario = $sql->fetch();

        // Compara a senha digitada com o hash armazenado.
        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return false;
        }

        // Renova o identificador da sessão após autenticar.
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome']
        ];
        return true;
    }

    // Informa se existe um usuário identificado na sessão.
    public function estaLogado(): bool
    {
        // Verifica se o login gravou os dados do usuário na sessão.
        return isset($_SESSION['usuario']);
    }

    // Envia visitantes para o login e interrompe o acesso protegido.
    public function exigirLogin(): void
    {
        // Bloqueia a continuação quando não há usuário autenticado.
        if (!$this->estaLogado()) {
            // Define a próxima página que o navegador deve abrir.
            header('Location: index.php?tela=login');
            exit;
        }
    }

    // Apaga os dados da sessão e encerra o login.
    public function sair(): void
    {
        $_SESSION = [];
        // Remove a sessão mantida pelo servidor.
        session_destroy();
    }
}
