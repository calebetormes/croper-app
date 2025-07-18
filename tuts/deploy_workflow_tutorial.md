# Tutorial de Deploy via SSH com GitHub Actions

Este tutorial guia você por todo o processo de configuração, desde o servidor até o GitHub Actions, para automatizar o deploy do seu projeto em cada push na branch `main`.

---

## 1. Preparar o servidor

### 1.1. Criar o script de deploy

No servidor, conecte-se via SSH e crie o arquivo `deploy.sh`:

```bash
ssh root@62.72.63.220
cd /home/barterapp-dev-croper/htdocs/croper-app/

cat > deploy.sh << 'EOF'
#!/usr/bin/env bash
set -e

# Vai para a pasta do projeto
cd /home/barterapp-dev-croper/htdocs/croper-app/

# Atualiza o repositório
git fetch --all
git reset --hard origin/main

# Instale dependências e rode migrations/builds
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci && npm run build
EOF
```

### 1.2. Ajustar permissões

```bash
chmod +x /home/barterapp-dev-croper/htdocs/croper-app/deploy.sh
```

### 1.3. Verificar pré-requisitos

```bash
git --version
php --version
composer --version
node --version
npm --version
```

Instale o que faltar via gerenciador de pacotes (apt, yum, etc.).

---

## 2. Configurar acesso SSH

### 2.1. Gerar chave SSH (máquina local)

```bash
ssh-keygen -t ed25519 -C "github-actions@croper" -f ~/.ssh/gh-actions-croper
```

Pressione ENTER para não usar passphrase. Isso cria:

- `~/.ssh/gh-actions-croper` (chave privada)
- `~/.ssh/gh-actions-croper.pub` (chave pública)

### 2.2. Instalar chave pública no servidor

```bash
ssh root@62.72.63.220
mkdir -p ~/.ssh && chmod 700 ~/.ssh
cat >> ~/.ssh/authorized_keys << 'EOF'
<cole aqui o conteúdo de ~/.ssh/gh-actions-croper.pub>
EOF
chmod 600 ~/.ssh/authorized_keys
```

Teste:

```bash
ssh -i ~/.ssh/gh-actions-croper root@62.72.63.220 'echo OK'
```

---

## 3. Adicionar secrets no GitHub

No repositório GitHub, vá em **Settings → Secrets and variables → Actions** e crie:

- `SERVER_HOST` = `62.72.63.220`
- `SERVER_USER` = `root`
- `SERVER_SSH_KEY` = cole todo o conteúdo de `~/.ssh/gh-actions-croper`

---

## 4. Criar workflow no repositório (local)

No projeto local:

```bash
cd /caminho/para/seu/projeto
mkdir -p .github/workflows
```

Crie `.github/workflows/deploy.yml` com:

```yaml
name: Deploy via SSH

on:
  push:
    branches:
      - main

jobs:
  deploy:
    name: SSH Deploy
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Deploy via SSH
        uses: appleboy/ssh-action@v0.1.6
        with:
          host: ${{ secrets.SERVER_HOST }}
          username: ${{ secrets.SERVER_USER }}
          key:      ${{ secrets.SERVER_SSH_KEY }}
          port:     22
          script: |
            cd /home/barterapp-dev-croper/htdocs/croper-app/
            git fetch --all
            git reset --hard origin/main
            chmod +x deploy.sh
            ./deploy.sh
```

---

## 5. Commit e push

```bash
git add .github/workflows/deploy.yml
git commit -m "🏷️ Add GitHub Actions SSH deploy workflow"
git push origin main
```

---

## 6. Verificar execução

- Acesse a aba **Actions** no GitHub.
- Selecione o workflow **Deploy via SSH** e veja a run mais recente.
- Logs detalham cada passo e mostram erros ou sucesso.

---

## 7. Dicas

- Versione o bit de execução do `deploy.sh` com:
  ```bash
  ```

git update-index --chmod=+x deploy.sh git commit -m "Mark deploy.sh executable" git push

```
- Para variáveis de ambiente no script, use `env:` no step `uses: appleboy/ssh-action`.

---

Pronto! A partir de agora, toda vez que você fizer **push** na `main`, o GitHub Actions conectará ao seu servidor e executará o seu deploy automaticamente. Qualquer dúvida, avise!

```
