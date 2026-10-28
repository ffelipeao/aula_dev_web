<?php
class Conexao
{
    // Abre e devolve a conexão PDO com o MySQL do XAMPP.
    public function conectar(): PDO
    {
        // Cria a conexão com endereço, banco, usuário, senha e opções.
        return new PDO(
            'mysql:host=127.0.0.1;port=3306;dbname=aula_estoque;charset=utf8mb4',
            'root',
            '', // Ajuste a senha de acordo com seu XAMPP.
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }
}
