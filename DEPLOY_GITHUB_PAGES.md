# Publicando um site no GitHub Pages

O **GitHub Pages** publica gratuitamente sites estáticos feitos com **HTML, CSS e JavaScript**. Depois da configuração inicial, cada novo `push` para a branch `main` atualiza o site publicado.

> [!IMPORTANT]
> Antes de começar, confirme que seu projeto possui um arquivo chamado **`index.html`** na pasta principal. Ele será a página inicial do site.

## O que você fará

1. Criará uma conta no GitHub e entrará nela.
2. Criará um repositório público vazio.
3. Preparará o projeto local com Git.
4. Fará o primeiro `commit` e o primeiro `push`.
5. Ativará o GitHub Pages.
6. Alterará o projeto e verificará a atualização do site.

## Pré-requisitos

- Um projeto com pelo menos um arquivo `index.html`.
- O [Git](https://git-scm.com/downloads) instalado no computador.
- Um terminal aberto **dentro da pasta do projeto**.

Para verificar se o Git está instalado, execute:

```bash
git --version
```

Se aparecer uma versão, como `git version 2.x.x`, o Git está pronto para uso.

---

## 1. Criar uma conta ou entrar no GitHub

### Se você ainda não possui uma conta

1. Acesse [github.com](https://github.com/).
2. Clique em **Sign up**.
3. Informe seu e-mail, crie uma senha e escolha um nome de usuário.
4. Conclua a verificação solicitada pelo GitHub.
5. Abra o e-mail enviado pelo GitHub e confirme seu endereço.

> [!TIP]
> Guarde seu **nome de usuário**. Ele será usado no endereço do repositório e do site.

### Se você já possui uma conta

1. Acesse [github.com/login](https://github.com/login).
2. Informe seu nome de usuário ou e-mail e sua senha.
3. Clique em **Sign in**.
4. Se a autenticação em dois fatores estiver habilitada, informe o código solicitado.

Ao finalizar, você verá a página inicial da sua conta no GitHub.

---

## 2. Preparar o repositório e configurar sua identificação

Primeiro, transforme a pasta atual em um repositório Git:

```bash
# Cria um repositório Git somente na pasta atual
git init
```

Agora configure o nome e o e-mail que aparecerão nos commits deste projeto:

```bash
git config --local user.name "Seu Nome"
git config --local user.email "seu-email@exemplo.com"
```

Use, de preferência, o mesmo e-mail cadastrado no GitHub. Para conferir:

```bash
git config --local user.name
git config --local user.email
```

> [!NOTE]
> A opção `--local` salva a identificação somente neste repositório. Ela não altera
> a configuração de outros projetos existentes no computador.

---

## 3. Criar um repositório no GitHub

1. No canto superior direito do GitHub, clique no ícone **`+`**.
2. Selecione **New repository**.
3. Em **Repository name**, informe um nome sem espaços, por exemplo: `meu-site`.
4. Em **Visibility**, selecione **Public**.
5. **Não marque** as opções para adicionar `README`, `.gitignore` ou licença. O repositório deve ser criado vazio, pois os arquivos já estão no seu computador.
6. Clique em **Create repository**.

Depois da criação, mantenha essa página aberta. Você usará o endereço HTTPS exibido nela, semelhante a:

```text
https://github.com/SEU-USUARIO/meu-site.git
```

> [!WARNING]
> Substitua `SEU-USUARIO` e `meu-site` nos exemplos pelo seu nome de usuário e pelo nome real do seu repositório. Não copie os textos de exemplo literalmente.

---

## 4. Fazer o primeiro commit

No terminal, confirme que você está dentro da pasta do projeto. Em seguida, execute **um comando por vez**:

```bash
# Define main como o nome da branch principal
git branch -M main

# Prepara todos os arquivos do projeto para o commit
git add .

# Registra a primeira versão do projeto
git commit -m "Primeiro commit do site"
```

Confira o resultado:

```bash
git status
```

Se aparecer `nothing to commit, working tree clean`, o primeiro commit foi criado corretamente.

---

## 5. Conectar o projeto ao GitHub e fazer o primeiro push

Copie o endereço **HTTPS** do repositório criado e execute:

```bash
git remote add origin https://github.com/SEU-USUARIO/meu-site.git
git push -u origin main
```

O primeiro comando conecta a pasta local ao repositório do GitHub. O segundo envia a branch `main` e configura seu acompanhamento remoto.

### Autenticação durante o push

O GitHub pode abrir o navegador ou solicitar que você escolha uma forma de autenticação. Siga as instruções exibidas na tela e autorize o acesso à sua conta.

> [!IMPORTANT]
> O GitHub não aceita a senha comum da conta como senha para operações Git via HTTPS. Se o terminal pedir diretamente usuário e senha, use um **personal access token** no campo da senha ou autentique-se com uma ferramenta oficial, como o GitHub CLI.

Depois do `push`, atualize a página do repositório no navegador. Seus arquivos, incluindo `index.html`, deverão aparecer.

---

## 6. Ativar o GitHub Pages

Faça esta configuração **depois do primeiro push**, para que a branch `main` já exista no GitHub:

1. Na página do repositório, clique em **Settings**.
2. No menu lateral, na seção **Code and automation**, clique em **Pages**.
3. Em **Build and deployment**, localize **Source**.
4. Selecione **Deploy from a branch**.
5. Em **Branch**, escolha `main`.
6. Ao lado da branch, escolha `/ (root)`.
7. Clique em **Save**.

O GitHub iniciará a publicação. Ela pode levar alguns minutos.

### Endereço do site

Volte a **Settings > Pages**. Quando a publicação terminar, aparecerá a mensagem **Your site is live at**, acompanhada do endereço. Para um repositório chamado `meu-site`, o formato costuma ser:

```text
https://SEU-USUARIO.github.io/meu-site/
```

Abra esse endereço e confirme se o conteúdo do `index.html` foi exibido.

> [!TIP]
> Também é possível acompanhar a publicação pela aba **Actions** do repositório. Um ícone verde indica que o processo terminou com sucesso.

---

## 7. Atualizar o site publicado

Edite o `index.html` ou outro arquivo do projeto e salve a alteração. Depois execute:

```bash
git status
git add .
git commit -m "Atualiza conteúdo do site"
git push
```

Como o vínculo com `origin/main` foi criado no primeiro push, agora basta usar `git push`.

Aguarde a execução terminar na aba **Actions** e atualize a página do site. Se a versão antiga continuar aparecendo, aguarde alguns minutos e recarregue a página ignorando o cache do navegador (`Ctrl + Shift + R` no Windows/Linux ou `Command + Shift + R` no macOS).

---

## Checklist final

- [ ] A conta do GitHub foi criada e o e-mail foi confirmado.
- [ ] O repositório foi criado como público e inicialmente vazio.
- [ ] O projeto possui `index.html` na pasta principal.
- [ ] O primeiro commit foi criado.
- [ ] Os arquivos aparecem na branch `main` no GitHub.
- [ ] Em **Settings > Pages**, a origem está definida como `main` e `/ (root)`.
- [ ] O endereço `https://SEU-USUARIO.github.io/NOME-DO-REPOSITORIO/` abre o site.
- [ ] Um novo commit e push atualizam o conteúdo publicado.

## Problemas comuns

### A página mostra erro 404

- Confirme que o arquivo se chama exatamente `index.html` — letras minúsculas e sem espaços.
- Confirme que ele está na pasta selecionada no Pages; neste guia, `/ (root)`.
- Confira se o endereço contém corretamente o nome do usuário e do repositório.
- Aguarde a publicação terminar na aba **Actions**.

### A branch `main` não aparece em Settings > Pages

Faça primeiro o `git push -u origin main`, atualize a página do GitHub e tente novamente.

### Aparece `remote origin already exists`

Confira o endereço já configurado:

```bash
git remote -v
```

Se ele estiver incorreto, substitua-o:

```bash
git remote set-url origin https://github.com/SEU-USUARIO/meu-site.git
```

### Aparece `rejected` ou `failed to push`

Isso pode acontecer quando o repositório remoto já possui arquivos que não existem localmente. Para evitar esse conflito, crie o repositório vazio, conforme indicado no passo 3.

## Referências oficiais

- [Criar uma conta no GitHub](https://docs.github.com/pt/get-started/start-your-journey/creating-an-account-on-github)
- [Criar um repositório](https://docs.github.com/pt/repositories/creating-and-managing-repositories/creating-a-new-repository)
- [Adicionar um projeto local ao GitHub](https://docs.github.com/pt/migrations/importing-source-code/using-the-command-line-to-import-source-code/adding-locally-hosted-code-to-github)
- [Configurar uma fonte de publicação do GitHub Pages](https://docs.github.com/pt/pages/getting-started-with-github-pages/configuring-a-publishing-source-for-your-github-pages-site)
