# Tutorial Passo a Passo para Publicar o Sistema Laravel + Filament

Este guia unifica os principais comandos e etapas para provisionar o servidor, configurar DNS, clonar e fazer o deploy inicial, gerar SSL, rodar scripts pós-deploy e testar o sistema. No final, há um **resumo** com todas as ações essenciais.

---

## 1. Provisionamento do Servidor (CloudPanel)

1. Acesse o painel do **CloudPanel** no seu servidor.
2. Clique em **+ ADD SITE** e selecione **PHP Site**.
   - Escolha o **nome de usuário** (ex: `barterapp-dev`) e o **domínio/subdomínio** (ex: `dev.croper.barterapp.com.br`).
   - O CloudPanel já provisiona **Nginx**, **PHP-FPM** e configura logs centralizados.

---

## 2. Configuração de DNS

No painel do seu provedor de domínio (p.ex. Cloudflare, Registro.br):

1. **Registro A**  
   - **Tipo**: A  
   - **Nome**: `dev.croper`  
   - **Aponta para**: `62.72.63.220`  
   - **TTL**: `300`

2. **(Opcional) Registro AAAA**  
   - Caso possua IPv6, crie também um AAAA apontando para o IPv6 do servidor.

3. **Validação**  
   ```bash
   dig +short dev.croper.barterapp.com.br
   ```
   Deve retornar o IP configurado. Aguarde até 5 min para propagação.

---

## 3. Clonagem do Repositório e Deploy Inicial

Conecte-se via SSH (com sua chave já configurada):

```bash
ssh root@???????
```

### 3.1. Acessar a pasta raiz do site

```bash
cd /home/barterapp-dev/htdocs
# (Opcional) Limpar conteúdo antigo
rm -rf ./*
```

### 3.2. Clonar o repositório

```bash
git clone https://github.com/calebetormes/croper-app.git
cd croper-app
```

### 3.3. Instalar dependências PHP (Composer)

```bash
composer install --no-dev --optimize-autoloader
```

### 3.4. Instalar dependências JavaScript (NPM)

```bash
npm install
npm run build        # ou `npm run prod` para produção
```

### 3.5. Criar o Banco de Dados

- Pelo **CloudPanel**: vá em **Database > + ADD DATABASE**, defina nome, usuário e senha.
- Ou via terminal MySQL:
  ```sql
  CREATE DATABASE croper_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  CREATE USER 'croper_user'@'localhost' IDENTIFIED BY 'senha_segura';
  GRANT ALL PRIVILEGES ON croper_db.* TO 'croper_user'@'localhost';
  FLUSH PRIVILEGES;
  ```

### 3.6. Configurar o `.env`

```bash
cp .env.example .env
```
Edite o `.env` para apontar ao seu banco:

```
APP_NAME="CroperApp"
APP_URL=https://dev.croper.barterapp.com.br

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=croper_db
DB_USERNAME=croper_user
DB_PASSWORD=senha_segura
```

### 3.7. Gerar a chave de aplicação

```bash
php artisan key:generate
```

### 3.8. Rodar *migrations* e *seeders*

```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## 4. Geração e Implantação de Certificado SSL

O CloudPanel facilita o SSL via Let’s Encrypt:

1. No CloudPanel, acesse **SSL/TLS**.
2. Clique em **Actions > New Let’s Encrypt Certificate**.
3. Selecione o site (`dev.croper.barterapp.com.br`) e confirme.
4. Aguarde a emissão (< 1 min). O painel instala e configura automaticamente no Nginx.

---

## 5. Execução do `post-deploy.sh`

No diretório raiz do projeto (`croper-app/`):

```bash
chmod +x post-deploy.sh
./post-deploy.sh
```

Esse script automatiza:

- Entrar e sair do modo de manutenção  
- Otimização de autoload  
- Publicação de assets Livewire  
- Limpeza de caches (config, rota, view)  
- Criação do *storage link*  
- Execução de migrations e seeders  
- Build front-end (npm)  
- Ajustes de permissões em `storage` e `bootstrap/cache`  
- Reinício de filas e serviços  
- Geração de `RELEASE.txt` com a versão atual  

---

## 6. Testes Finais e Acesso

1. Abra no navegador:  
   ```
   https://dev.croper.barterapp.com.br/admin/login
   ```
2. Verifique o cadeado verde (SSL OK).  
3. Faça login no Filament e confira todos os recursos.  
4. Caso algo falhe, verifique os logs em **CloudPanel > Logs** (app e Nginx).

---

## Resumo das Etapas

1. **Provisionar servidor** (CloudPanel + PHP Site)  
2. **Configurar DNS** (A e AAAA + validação com `dig`)  
3. **Clonar repositório** e **instalar dependências** (Composer & NPM)  
4. **Criar DB** e **configurar `.env`**  
5. **Gerar APP_KEY** (`php artisan key:generate`)  
6. **Rodar migrations & seeders** (`php artisan migrate && db:seed`)  
7. **Gerar SSL** com Let’s Encrypt no CloudPanel  
8. **Executar `post-deploy.sh`** para ajustes finais  
9. **Testar** em `https://dev.croper.barterapp.com.br/admin/login`

> **Dica**: para futuros deploys, basta:
> ```bash
> git pull
> composer install --no-dev --optimize-autoloader
> npm install && npm run build
> php artisan migrate --force
> ./post-deploy.sh
> ```
> e pronto! Monitorar logs regularmente e considerar automação com GitHub Actions + SSH.
