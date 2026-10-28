<?php
class Conexao
{
    // Abre e devolve a conexão PDO com o MySQL do XAMPP.
    public function conectar(): PDO
    {
        // Define os dados de acesso antes de abrir a conexão.
        $host = '127.0.0.1';
        $porta = '3306';
        $banco = 'aula_estoque';
        $usuario = 'root';
        $senha = ''; // Ajuste a senha de acordo com seu XAMPP.
        $charset = 'utf8mb4';

        // Cria a conexão usando as variáveis definidas acima.
        return new PDO(
            "mysql:host=$host;port=$porta;dbname=$banco;charset=$charset",
            $usuario,
            $senha,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }
}
