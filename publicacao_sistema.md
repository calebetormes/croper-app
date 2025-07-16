**Passo a Passo para Publicar o Sistema Laravel + Filament**

Este guia tem como objetivo cobrir:

1. Provisionamento do servidor (via CloudPanel)
2. Configuração de DNS
3. Clonagem do repositório e deploy inicial
4. Geração e implantação de certificado SSL (Let’s Encrypt no CloudPanel)
5. Execução do `post-deploy.sh`
6. Testes finais e acesso

---

## 1. Provisionamento do Servidor no CloudPanel

1. Acesse o painel do CloudPanel.
2. Em **Servers**, clique em **Add Server** e forneça:
   - **Name**: `croper-dev`
   - **Host**: IP público do seu VPS
   - **User**: `barterapp-dev-croper`
   - Chave SSH ou senha (`#02062011Bm`).
3. Depois de conectar, selecione o server e em **Apps** crie um novo **Application**:
   - **Type**: PHP / Laravel
   - **Name**: `dev.croper.barterapp.com.br`
   - **Document Root**: `/home/barterapp-dev-croper/htdocs/dev.croper.barterapp.com.br/public`
   - **PHP Version**: compatível (ex: 8.4).

> O CloudPanel já provisiona Nginx, PHP-FPM e mostra logs centralizados.

## 2. Configuração de DNS

1. No painel do seu provedor de domínio (por ex. Cloudflare, Registro.br):
   - Adicione um registro **A**:
     - **Host**: `dev.croper.barterapp.com.br`
     - **Value**: IP do servidor.
   - Se usar IPv6, adicione também registro **AAAA**.
2. Aguarde a propagação (pode levar até 5 minutos).
3. Verifique no terminal:
   ```bash
   dig +short dev.croper.barterapp.com.br
   ```
   Deve retornar o IP configurado.

## 3. Clonagem e Deploy Inicial

No terminal SSH conectado ao servidor:

```bash
cd /home/barterapp-dev-croper/htdocs
# Clona o repositório na pasta do app
git clone git@github.com:sua-org/seu-repo.git dev.croper.barterapp.com.br
cd dev.croper.barterapp.com.br

# Instala dependências PHP
composer install --no-dev --optimize-autoloader

# Copia .env e gera chave
cp .env.example .env
php artisan key:generate
```

## 4. Geração de Certificado SSL (Let’s Encrypt)

O CloudPanel simplifica o SSL via Let’s Encrypt:

1. No CloudPanel, acesse **Apps > dev.croper.barterapp.com.br**.
2. Clique em **SSL Certificates**.
3. Selecione **Let's Encrypt** e confirme o domínio.
4. Aguarde alguns segundos até o painel gerar e instalar o certificado.
5. Verifique status: deve mostrar **Valid** com data de expiração em \~90 dias.

> O CloudPanel configura automaticamente o Nginx para usar o certificado.

## 5. Execução do `post-deploy.sh`

Ainda no diretório do projeto, torne o script executável e execute:

```bash
chmod +x post-deploy.sh
./post-deploy.sh
```

O script irá:

- Colocar o site em manutenção
- Otimizar autoload
- Publicar assets Livewire
- Limpar caches
- Criar `storage:link`
- Executar migrations e seeders
- Build front-end (npm)
- Cache de config, rotas e views
- Ajustar permissões
- Garantir laravel.log
- Reiniciar filas e serviços
- Desativar manutenção
- Registrar versão em RELEASE.txt
- Notificar Slack (se configurado)

## 6. Testes Finais e Acesso

1. Abra no navegador:
   ```
   https://dev.croper.barterapp.com.br/admin/login
   ```
2. Verifique se o **cadeado verde** aparece (SSL OK).
3. Faça login no Filament e confira recursos.
4. Se tudo funcionar, seu deploy está concluído com sucesso!

---

**Dicas adicionais:**

- Para próximos deploys, basta `git pull`, `composer install`, `./post-deploy.sh`, `php artisan migrate --force` e **recarregar** o serviço.
- Monitore logs em **CloudPanel > Logs** (app e Nginx).
- Automatize ainda mais com GitHub Actions + SSH.

