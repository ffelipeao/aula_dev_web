# Aula de Desenvolvimento Web — Sistema de Cadastro Multi-página (HTML + CSS + JavaScript)

## 🎯 Objetivo da aula

Construir um pequeno sistema com **três telas** (páginas HTML separadas), navegando entre elas, para simular um sistema de cadastro de produtos:

1. **Home (`index.html`)** — tela inicial com o menu do sistema.
2. **Consulta (`consulta.html`)** — lista de produtos cadastrados (dados fixos, "mockados", por enquanto).
3. **Cadastro (`cadastro.html`)** — formulário para cadastrar um novo produto.
4. **Confirmação (`confirmacao.html`)** — tela para onde os dados do formulário são enviados e exibidos.

> ⚠️ **Importante:** Nesta etapa **não usamos banco de dados nem PHP**. Os dados do formulário são passados de uma tela para outra usando **JavaScript** (`localStorage`), só para simular o fluxo de um sistema real. Nas próximas aulas, vamos:
> - Validar os campos do formulário com JavaScript (campos obrigatórios, formato de preço, etc.);
> - Conectar o sistema a um banco de dados usando PHP para gravar os dados de verdade.

---

## 🗂️ Estrutura de arquivos

Crie uma pasta para o projeto (ex: `sistema-cadastro`) e, dentro dela, crie os seguintes arquivos:

```
sistema-cadastro/
├── index.html
├── consulta.html
├── cadastro.html
├── cadastro-lista.html
├── confirmacao.html
└── style.css
```

Todas as páginas vão usar o **mesmo arquivo `style.css`**, para manter a aparência padronizada.

---

## 1️⃣ Passo 1 — CSS compartilhado (`style.css`)

Crie o arquivo `style.css` com o código abaixo. Ele será usado em todas as páginas.

```css
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, Helvetica, sans-serif;
}

body {
  background-color: #f4f6f8;
  color: #222;
}

header {
  background-color: #2c3e50;
  color: #fff;
  padding: 20px;
  text-align: center;
}

nav {
  background-color: #34495e;
  display: flex;
  justify-content: center;
  gap: 20px;
  padding: 12px;
}

nav a {
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  padding: 8px 16px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

nav a:hover {
  background-color: #1abc9c;
}

main {
  max-width: 900px;
  margin: 30px auto;
  background-color: #fff;
  padding: 25px;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

h1, h2 {
  margin-bottom: 15px;
  color: #2c3e50;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

table th, table td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: left;
}

table th {
  background-color: #2c3e50;
  color: #fff;
}

table tr:nth-child(even) {
  background-color: #f2f2f2;
}

form label {
  display: block;
  margin-top: 15px;
  margin-bottom: 5px;
  font-weight: bold;
}

form input,
form select,
form textarea {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

form button {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #1abc9c;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 15px;
}

form button:hover {
  background-color: #16a085;
}

.aviso {
  background-color: #fff3cd;
  border: 1px solid #ffeeba;
  color: #856404;
  padding: 12px;
  border-radius: 4px;
  margin-top: 20px;
  font-size: 14px;
}

.dados-recebidos {
  background-color: #eafaf1;
  border: 1px solid #b2f2d9;
  border-radius: 6px;
  padding: 15px;
  margin-top: 15px;
}

.dados-recebidos p {
  margin-bottom: 8px;
}

footer {
  text-align: center;
  padding: 15px;
  color: #777;
  font-size: 13px;
}
```

---

## 2️⃣ Passo 2 — Tela inicial (`index.html`)

Essa é a página principal do sistema, com o menu de navegação.

```html
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Sistema de Cadastro - Início</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <h1>Sistema de Cadastro de Produtos</h1>
  </header>

  <nav>
    <a href="index.html">Início</a>
    <a href="consulta.html">Consultar Produtos</a>
    <a href="cadastro.html">Cadastrar Produto</a>
    <a href="cadastro-lista.html">Cadastrar (lista na mesma página)</a>
  </nav>

  <main>
    <h2>Bem-vindo(a)!</h2>
    <p>
      Este é um sistema simples de cadastro de produtos, construído com
      HTML, CSS e JavaScript, para fins de aprendizado.
    </p>
    <p style="margin-top: 10px;">
      Use o menu acima para navegar entre as telas:
    </p>
    <ul style="margin: 15px 0 0 20px; line-height: 1.8;">
      <li><strong>Consultar Produtos</strong>: mostra uma lista de produtos.</li>
      <li><strong>Cadastrar Produto</strong>: formulário para cadastrar um novo produto.</li>
      <li><strong>Cadastrar (lista na mesma página)</strong>: mesma ideia, mas mostrando a lista de produtos cadastrados sem sair da página.</li>
    </ul>

    <div class="aviso">
      🚧 Este sistema ainda não está conectado a um banco de dados.
      Os dados de cadastro serão apenas exibidos na tela seguinte,
      como simulação do fluxo do sistema.
    </div>
  </main>

  <footer>
    Aula de Desenvolvimento Web — Exemplo didático
  </footer>

</body>
</html>
```

---

## 3️⃣ Passo 3 — Tela de consulta (`consulta.html`)

Esta tela lista produtos. Como ainda não temos banco de dados, vamos criar uma **lista de produtos fixa dentro do JavaScript** (um "mock") e usar JS para montar a tabela na tela.

```html
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Sistema de Cadastro - Consulta</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <h1>Sistema de Cadastro de Produtos</h1>
  </header>

  <nav>
    <a href="index.html">Início</a>
    <a href="consulta.html">Consultar Produtos</a>
    <a href="cadastro.html">Cadastrar Produto</a>
  </nav>

  <main>
    <h2>Consulta de Produtos</h2>
    <p>Lista de produtos cadastrados (dados de exemplo, fixos no código):</p>

    <table id="tabela-produtos">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nome</th>
          <th>Categoria</th>
          <th>Preço</th>
          <th>Quantidade</th>
        </tr>
      </thead>
      <tbody>
        <!-- As linhas serão geradas automaticamente pelo JavaScript -->
      </tbody>
    </table>

    <div class="aviso">
      📌 Nesta etapa, os produtos abaixo são apenas exemplos fixos no
      código JavaScript. Nas próximas aulas, essa lista virá do banco
      de dados.
    </div>
  </main>

  <footer>
    Aula de Desenvolvimento Web — Exemplo didático
  </footer>

  <script>
    // Lista de produtos "mockada" (simulada), só para exibição
    const produtos = [
      { codigo: 1, nome: "Teclado Mecânico", categoria: "Periféricos", preco: 249.90, quantidade: 15 },
      { codigo: 2, nome: "Mouse sem fio", categoria: "Periféricos", preco: 89.90, quantidade: 30 },
      { codigo: 3, nome: "Monitor 24''", categoria: "Monitores", preco: 799.00, quantidade: 8 },
      { codigo: 4, nome: "Notebook", categoria: "Computadores", preco: 3499.00, quantidade: 5 },
      { codigo: 5, nome: "Headset Gamer", categoria: "Periféricos", preco: 199.90, quantidade: 20 }
    ];

    // Seleciona o <tbody> da tabela para inserir as linhas
    const corpoTabela = document.querySelector("#tabela-produtos tbody");

    // Para cada produto, cria uma linha (<tr>) com as informações
    produtos.forEach((produto) => {
      const linha = document.createElement("tr");

      linha.innerHTML = `
        <td>${produto.codigo}</td>
        <td>${produto.nome}</td>
        <td>${produto.categoria}</td>
        <td>R$ ${produto.preco.toFixed(2)}</td>
        <td>${produto.quantidade}</td>
      `;

      corpoTabela.appendChild(linha);
    });
  </script>

</body>
</html>
```

### 💡 O que o JavaScript está fazendo aqui?

- Cria um **array de objetos** (`produtos`), onde cada objeto representa um produto.
- Usa `document.querySelector` para encontrar o `<tbody>` da tabela.
- Usa `forEach` para percorrer a lista e, para cada produto, criar uma linha `<tr>` com `document.createElement`.
- Insere a linha na tabela com `appendChild`.

---

## 4️⃣ Passo 4 — Tela de cadastro (`cadastro.html`)

Esta é a tela com o **formulário de cadastro**. Quando o aluno clicar em "Salvar", o JavaScript vai:

1. Impedir o envio padrão do formulário (`preventDefault`);
2. Pegar os valores digitados pelo usuário;
3. Guardar esses valores temporariamente usando `localStorage`;
4. Redirecionar o usuário para a tela `confirmacao.html`.

```html
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Sistema de Cadastro - Cadastro de Produto</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <h1>Sistema de Cadastro de Produtos</h1>
  </header>

  <nav>
    <a href="index.html">Início</a>
    <a href="consulta.html">Consultar Produtos</a>
    <a href="cadastro.html">Cadastrar Produto</a>
  </nav>

  <main>
    <h2>Cadastro de Produto</h2>

    <form id="form-cadastro">
      <label for="nome">Nome do produto:</label>
      <input type="text" id="nome" name="nome" placeholder="Ex: Teclado Mecânico">

      <label for="categoria">Categoria:</label>
      <select id="categoria" name="categoria">
        <option value="">Selecione...</option>
        <option value="Periféricos">Periféricos</option>
        <option value="Monitores">Monitores</option>
        <option value="Computadores">Computadores</option>
        <option value="Outros">Outros</option>
      </select>

      <label for="preco">Preço (R$):</label>
      <input type="number" id="preco" name="preco" placeholder="Ex: 199.90" step="0.01">

      <label for="quantidade">Quantidade em estoque:</label>
      <input type="number" id="quantidade" name="quantidade" placeholder="Ex: 10">

      <label for="descricao">Descrição:</label>
      <textarea id="descricao" name="descricao" rows="4" placeholder="Detalhes do produto..."></textarea>

      <button type="submit">Salvar Cadastro</button>
    </form>

    <div class="aviso">
      🚧 Por enquanto, este formulário ainda **não valida** os campos
      (por exemplo, campo vazio ou preço negativo). A validação com
      JavaScript será feita em uma próxima aula, antes de gravarmos
      os dados em um banco de dados.
    </div>
  </main>

  <footer>
    Aula de Desenvolvimento Web — Exemplo didático
  </footer>

  <script>
    // Seleciona o formulário pelo id
    const formulario = document.getElementById("form-cadastro");

    // Escuta o evento de "submit" (quando o botão Salvar é clicado)
    formulario.addEventListener("submit", function (evento) {
      // Impede que a página recarregue / envie de forma tradicional
      evento.preventDefault();

      // Monta um objeto com os dados digitados pelo usuário
      const produto = {
        nome: document.getElementById("nome").value,
        categoria: document.getElementById("categoria").value,
        preco: document.getElementById("preco").value,
        quantidade: document.getElementById("quantidade").value,
        descricao: document.getElementById("descricao").value
      };

      // Aqui, futuramente, entrará a VALIDAÇÃO dos campos com JavaScript
      // Exemplo (próximas aulas):
      // if (produto.nome === "") { alert("Informe o nome do produto"); return; }

      // Guarda o objeto no localStorage (como um "rascunho" temporário),
      // convertendo o objeto em texto (JSON) para poder ser armazenado
      localStorage.setItem("produtoCadastrado", JSON.stringify(produto));

      // Redireciona o usuário para a tela de confirmação
      window.location.href = "confirmacao.html";
    });
  </script>

</body>
</html>
```

### 💡 O que o JavaScript está fazendo aqui?

- `addEventListener("submit", ...)`: "escuta" o momento em que o formulário é enviado.
- `evento.preventDefault()`: evita que a página recarregue (comportamento padrão de formulários HTML).
- Um **objeto `produto`** é criado com os valores digitados pelo usuário, um valor por campo do formulário.
- `JSON.stringify(produto)`: transforma o objeto em texto, porque o `localStorage` só guarda texto (strings).
- `localStorage.setItem(...)`: guarda esse texto no navegador, com a "chave" `produtoCadastrado`.
- `window.location.href = "confirmacao.html"`: leva o usuário para a próxima tela.

---

## 5️⃣ Passo 5 — Tela de confirmação (`confirmacao.html`)

Esta tela **recebe** os dados salvos no `localStorage` pela tela de cadastro e os exibe para o usuário.

```html
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Sistema de Cadastro - Confirmação</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <h1>Sistema de Cadastro de Produtos</h1>
  </header>

  <nav>
    <a href="index.html">Início</a>
    <a href="consulta.html">Consultar Produtos</a>
    <a href="cadastro.html">Cadastrar Produto</a>
  </nav>

  <main>
    <h2>Dados recebidos do cadastro</h2>

    <div id="area-dados">
      <!-- Os dados serão inseridos aqui pelo JavaScript -->
    </div>

    <div class="aviso">
      📌 Estes dados ainda <strong>não foram validados</strong> nem
      gravados em um banco de dados. Nas próximas etapas, vamos:
      <ul style="margin: 10px 0 0 20px; line-height: 1.6;">
        <li>Validar os campos com JavaScript (obrigatórios, tipo de dado, valores válidos);</li>
        <li>Enviar os dados para um servidor com PHP;</li>
        <li>Gravar os dados em um banco de dados real.</li>
      </ul>
    </div>
  </main>

  <footer>
    Aula de Desenvolvimento Web — Exemplo didático
  </footer>

  <script>
    // Recupera o texto salvo no localStorage
    const dadosSalvos = localStorage.getItem("produtoCadastrado");

    // Seleciona a div onde os dados serão exibidos
    const areaDados = document.getElementById("area-dados");

    if (dadosSalvos) {
      // Converte o texto (JSON) de volta para um objeto JavaScript
      const produto = JSON.parse(dadosSalvos);

      // Monta o HTML com os dados do produto cadastrado
      areaDados.innerHTML = `
        <div class="dados-recebidos">
          <p><strong>Nome:</strong> ${produto.nome}</p>
          <p><strong>Categoria:</strong> ${produto.categoria}</p>
          <p><strong>Preço:</strong> R$ ${produto.preco}</p>
          <p><strong>Quantidade:</strong> ${produto.quantidade}</p>
          <p><strong>Descrição:</strong> ${produto.descricao}</p>
        </div>
      `;
    } else {
      // Caso o usuário acesse esta página sem vir do formulário
      areaDados.innerHTML = `
        <p>Nenhum dado de cadastro encontrado. 
        Volte para a tela de <a href="cadastro.html">Cadastro</a> e preencha o formulário.</p>
      `;
    }
  </script>

</body>
</html>
```

### 💡 O que o JavaScript está fazendo aqui?

- `localStorage.getItem("produtoCadastrado")`: busca o texto que foi salvo na tela de cadastro.
- `JSON.parse(dadosSalvos)`: converte o texto de volta em um **objeto JavaScript**, para acessar `produto.nome`, `produto.preco`, etc.
- Usa um `if/else` para tratar o caso em que o usuário chega nesta página **sem ter preenchido o formulário** antes.
- Monta o HTML dinamicamente com `innerHTML`, usando os dados do objeto.

---

## 6️⃣ Passo 6 — Segunda opção: cadastro com lista na mesma página (`cadastro-lista.html`)

A tela `cadastro.html` que criamos no Passo 4 **continua existindo e funcionando normalmente** — ela envia os dados para a tela `confirmacao.html`.

Agora vamos criar **uma segunda forma de fazer o cadastro**, como alternativa: em vez de redirecionar para outra página, os dados digitados serão **adicionados a uma lista exibida na própria página**, a cada clique em "Salvar". Ou seja, o formulário permanece na tela e, abaixo dele, vai crescendo uma tabela com todos os produtos cadastrados durante o uso da página.

Crie um novo arquivo chamado **`cadastro-lista.html`**:

```html
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Sistema de Cadastro - Cadastro com Lista na Página</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <h1>Sistema de Cadastro de Produtos</h1>
  </header>

  <nav>
    <a href="index.html">Início</a>
    <a href="consulta.html">Consultar Produtos</a>
    <a href="cadastro.html">Cadastrar Produto</a>
    <a href="cadastro-lista.html">Cadastrar (lista na mesma página)</a>
  </nav>

  <main>
    <h2>Cadastro de Produto (com lista na mesma página)</h2>

    <form id="form-cadastro-lista">
      <label for="nome2">Nome do produto:</label>
      <input type="text" id="nome2" name="nome" placeholder="Ex: Teclado Mecânico">

      <label for="categoria2">Categoria:</label>
      <select id="categoria2" name="categoria">
        <option value="">Selecione...</option>
        <option value="Periféricos">Periféricos</option>
        <option value="Monitores">Monitores</option>
        <option value="Computadores">Computadores</option>
        <option value="Outros">Outros</option>
      </select>

      <label for="preco2">Preço (R$):</label>
      <input type="number" id="preco2" name="preco" placeholder="Ex: 199.90" step="0.01">

      <label for="quantidade2">Quantidade em estoque:</label>
      <input type="number" id="quantidade2" name="quantidade" placeholder="Ex: 10">

      <label for="descricao2">Descrição:</label>
      <textarea id="descricao2" name="descricao" rows="4" placeholder="Detalhes do produto..."></textarea>

      <button type="submit">Salvar Cadastro</button>
    </form>

    <div class="aviso">
      🚧 Assim como na outra tela de cadastro, aqui também **não há
      validação** dos campos ainda, e **nenhum dado é gravado em banco
      de dados**. A cada "Salvar", o produto é apenas adicionado à
      lista abaixo, guardada temporariamente na memória do navegador.
    </div>

    <h2 style="margin-top: 25px;">Produtos cadastrados nesta sessão</h2>

    <table id="tabela-cadastrados">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Categoria</th>
          <th>Preço</th>
          <th>Quantidade</th>
          <th>Descrição</th>
        </tr>
      </thead>
      <tbody>
        <!-- As linhas serão adicionadas aqui pelo JavaScript, a cada "Salvar" -->
      </tbody>
    </table>

    <p id="mensagem-vazia" style="margin-top: 10px; color: #777;">
      Nenhum produto cadastrado ainda nesta página.
    </p>
  </main>

  <footer>
    Aula de Desenvolvimento Web — Exemplo didático
  </footer>

  <script>
    // Array que guarda, em memória, todos os produtos cadastrados
    // enquanto o usuário estiver usando esta página
    const produtosCadastrados = [];

    // Referências aos elementos da página
    const formulario = document.getElementById("form-cadastro-lista");
    const corpoTabela = document.querySelector("#tabela-cadastrados tbody");
    const mensagemVazia = document.getElementById("mensagem-vazia");

    formulario.addEventListener("submit", function (evento) {
      // Impede o comportamento padrão do formulário (recarregar a página)
      evento.preventDefault();

      // Monta um objeto com os dados digitados
      const produto = {
        nome: document.getElementById("nome2").value,
        categoria: document.getElementById("categoria2").value,
        preco: document.getElementById("preco2").value,
        quantidade: document.getElementById("quantidade2").value,
        descricao: document.getElementById("descricao2").value
      };

      // Aqui, futuramente, entrará a VALIDAÇÃO dos campos com JavaScript
      // Exemplo (próximas aulas):
      // if (produto.nome === "") { alert("Informe o nome do produto"); return; }

      // Adiciona o novo produto no array em memória
      produtosCadastrados.push(produto);

      // Redesenha a tabela inteira com todos os produtos já cadastrados
      atualizarTabela();

      // Limpa os campos do formulário para o próximo cadastro
      formulario.reset();
    });

    function atualizarTabela() {
      // Limpa o conteúdo atual da tabela antes de redesenhar
      corpoTabela.innerHTML = "";

      // Mostra ou esconde a mensagem "nenhum produto cadastrado"
      mensagemVazia.style.display = produtosCadastrados.length === 0 ? "block" : "none";

      // Para cada produto já cadastrado, cria uma linha na tabela
      produtosCadastrados.forEach(function (produto, indice) {
        const linha = document.createElement("tr");

        linha.innerHTML = `
          <td>${indice + 1}</td>
          <td>${produto.nome}</td>
          <td>${produto.categoria}</td>
          <td>R$ ${produto.preco}</td>
          <td>${produto.quantidade}</td>
          <td>${produto.descricao}</td>
        `;

        corpoTabela.appendChild(linha);
      });
    }
  </script>

</body>
</html>
```

### 💡 O que muda em relação à tela de cadastro do Passo 4?

| `cadastro.html` (Passo 4) | `cadastro-lista.html` (Passo 6) |
|---|---|
| Ao salvar, os dados vão para `localStorage` | Ao salvar, os dados vão para um **array em memória** (`produtosCadastrados`) |
| O usuário é **redirecionado** para `confirmacao.html` | O usuário **permanece na mesma página** |
| Mostra apenas o **último** cadastro feito | Mostra **todos** os cadastros feitos durante o uso da página, em uma tabela que cresce |
| Usa `JSON.stringify` / `JSON.parse` para guardar e ler os dados | Usa `push()` para adicionar itens diretamente no array |

### 💡 Detalhes importantes do JavaScript

- `produtosCadastrados.push(produto)`: adiciona o novo objeto ao final do array, sem apagar os produtos anteriores.
- A função `atualizarTabela()` é chamada a cada "Salvar" e **redesenha a tabela inteira** a partir do array — por isso ela sempre limpa (`corpoTabela.innerHTML = ""`) antes de recriar as linhas.
- `formulario.reset()`: limpa os campos do formulário depois de cada cadastro, para facilitar cadastrar o próximo produto.
- Como o array `produtosCadastrados` vive apenas na **memória da página**, se o usuário atualizar (F5) ou fechar a página, a lista é perdida — diferente de um banco de dados, que guardaria os dados de forma permanente (assunto de uma próxima aula).

> ℹ️ As duas telas de cadastro (`cadastro.html` e `cadastro-lista.html`) podem conviver no mesmo projeto, como **duas formas diferentes de resolver o mesmo problema**. É um bom exercício para o aluno comparar as duas abordagens.

---

## ▶️ Como testar o sistema

1. Salve todos os arquivos (`index.html`, `consulta.html`, `cadastro.html`, `cadastro-lista.html`, `confirmacao.html`, `style.css`) na **mesma pasta**.
2. Dê dois cliques no arquivo `index.html` para abri-lo no navegador (ou use a extensão **Live Server** no VS Code).
3. Navegue pelo menu:
   - Clique em **"Consultar Produtos"** e veja a lista de produtos sendo montada pelo JavaScript.
   - Clique em **"Cadastrar Produto"**, preencha o formulário e clique em **"Salvar Cadastro"**.
   - Você será redirecionado para a tela de **confirmação**, mostrando os dados que você digitou.
   - Volte ao menu e clique em **"Cadastrar (lista na mesma página)"**. Preencha o formulário e clique em **"Salvar Cadastro"** algumas vezes seguidas — repare que a tabela abaixo do formulário vai crescendo, sem sair da página.

---

## 📚 Para fixar o conteúdo (exercícios sugeridos)

1. Adicione um novo campo no formulário de cadastro (ex: `fornecedor`) e faça-o aparecer também na tela de confirmação.
2. Adicione mais 3 produtos na lista `produtos` da tela de consulta.
3. Crie um botão "Voltar para o cadastro" na tela de confirmação.
4. **(Desafio)** Pesquise sobre o método `localStorage.removeItem()` e explique quando ele poderia ser útil neste sistema.
5. **(Desafio)** Na tela `cadastro-lista.html`, adicione um botão "Limpar lista" que esvazia o array `produtosCadastrados` e atualiza a tabela.
6. **(Reflexão)** Compare as duas telas de cadastro (`cadastro.html` e `cadastro-lista.html`): em que situação real cada abordagem faria mais sentido?

---

## 🔜 Próximos passos (próximas aulas)

- ✅ **Validação com JavaScript**: impedir o envio do formulário se campos obrigatórios estiverem vazios, se o preço for negativo, etc. (usando `if`, `alert()`, mensagens de erro na tela).
- ✅ **Conexão com banco de dados**: usar **PHP** para receber os dados do formulário e gravá-los em um banco de dados (ex: MySQL), substituindo o uso do `localStorage`.
- ✅ **Listagem dinâmica**: a tela de consulta passará a buscar os produtos diretamente do banco de dados, em vez da lista fixa no JavaScript.
