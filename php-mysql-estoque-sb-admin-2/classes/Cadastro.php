<?php
// Todas as operações de produtos ficam nesta classe.
class Cadastro
{
    private PDO $pdo;

    // Recebe a conexão pronta e a guarda para os outros métodos.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lista os produtos e permite filtrar parte do nome.
    public function listar(string $busca = ''): array
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('SELECT * FROM produtos WHERE nome LIKE ? ORDER BY nome');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute(['%' . $busca . '%']);
        // Devolve todas as linhas encontradas em um array.
        return $sql->fetchAll();
    }

    // Busca um produto pelo ID; retorna false se não existir.
    public function buscar(int $id): array|false
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('SELECT * FROM produtos WHERE id = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$id]);
        // Devolve uma linha ou false quando o ID não existe.
        return $sql->fetch();
    }

    // Insere um produto com nome, preço e quantidade.
    public function cadastrar(string $nome, string $preco, int $quantidade): void
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('INSERT INTO produtos (nome, preco, quantidade) VALUES (?, ?, ?)');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$nome, $preco, $quantidade]);
    }

    // Atualiza os campos de um produto identificado pelo ID.
    public function alterar(int $id, string $nome, string $preco, int $quantidade): void
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('UPDATE produtos SET nome = ?, preco = ?, quantidade = ? WHERE id = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$nome, $preco, $quantidade, $id]);
    }

    // Remove somente o produto identificado pelo ID.
    public function excluir(int $id): void
    {
        // Prepara o SQL; cada ? receberá um valor separado do comando.
        $sql = $this->pdo->prepare('DELETE FROM produtos WHERE id = ?');
        // Executa o SQL com os valores na ordem dos pontos de interrogação.
        $sql->execute([$id]);
    }
}
