#!/bin/bash
# post-deploy.sh - Script de pós-deploy simplificado para Laravel
# Descrição: publica assets do Livewire, limpa caches, ajusta permissões e garante existência de logs.

# -------------------------------------------------------------------------------
# CONFIGURAÇÃO INICIAL
# -------------------------------------------------------------------------------

# Obtém o caminho absoluto da pasta onde este script está localizado.
# Reason: garante que todos os comandos posteriores sejam executados no diretório correto do projeto.
ROOT_DIR=$(cd "$(dirname "$0")" && pwd)

# Define o usuário do sistema que deve possuir os arquivos (normalmente o usuário do PHP-FPM / web server).
# Reason: assegura que o servidor web tenha permissão de leitura e escrita.
SITE_USER="barterapp-test-deploy"

# Navega até o diretório raiz do projeto; sai se falhar.
cd "$ROOT_DIR" || { echo "❌ Não foi possível acessar $ROOT_DIR"; exit 1; }

echo "🚀 Iniciando script pós-deploy Laravel..."

# -----------------------------------------------------------------------------
# MODO DE MANUTENÇÃO
# -----------------------------------------------------------------------------
echo "🔒 Habilitando modo de manutenção..."
# Reason: Evita que usuários vejam erros durante o deploy e mantém o site offline temporariamente
php artisan down --secret="deploying"


# -------------------------------------------------------------------------------
# PUBLICAÇÃO DE ASSETS DO LIVEWIRE
# -------------------------------------------------------------------------------

echo "🔄 Publicando assets do Livewire..."
# Reason: copia os arquivos JavaScript/CSS do Livewire (ex: livewire.js) do diretório vendor
#         para a pasta public/vendor/livewire, garantindo que o front-end do Livewire funcione.
php artisan vendor:publish --tag=livewire:assets --force



# -------------------------------------------------------------------------------
# LIMPEZA DE CACHES DO LARAVEL
# -------------------------------------------------------------------------------

echo "⚡ Limpando caches do Laravel..."
# Reason: remove caches de configuração, rotas, views, eventos e otimizações antigas,
#         evitando inconsistências após deploy de novo código.
php artisan optimize:clear



# -------------------------------------------------------------------------------
# AJUSTE DE PERMISSÕES
# -------------------------------------------------------------------------------

echo "📁 Ajustando permissões em storage/ e bootstrap/cache..."
# Reason: tanto o diretório storage (sessões, cache, logs) quanto bootstrap/cache
#         precisam ser graváveis pelo servidor web. chmod 775 garante permissão
#         de leitura, escrita e execução para dono e grupo, e leitura/execução para outros.
chown -R $SITE_USER:$SITE_USER "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"
chmod -R 775 "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"



# -------------------------------------------------------------------------------
# GARANTIA DE EXISTÊNCIA DO ARQUIVO DE LOG
# -------------------------------------------------------------------------------

echo "📄 Garantindo que o arquivo de log exista..."
# Reason: o Laravel grava logs em storage/logs/laravel.log. Criar o arquivo e definir
#         as permissões evita erros de “permission denied” ou “file not found” ao escrever no log.
mkdir -p "$ROOT_DIR/storage/logs"
touch "$ROOT_DIR/storage/logs/laravel.log"
chown $SITE_USER:$SITE_USER "$ROOT_DIR/storage/logs/laravel.log"
chmod 664 "$ROOT_DIR/storage/logs/laravel.log"
# chmod 664 dá permissão de leitura e escrita ao dono e grupo, e leitura a outros.

echo "✅ Permissões e assets ajustados com sucesso!"







#OTIMIZAÇÕES




# -----------------------------------------------------------------------------
# OTIMIZAÇÃO DO AUTOLOAD
# -----------------------------------------------------------------------------
echo "🧩 Gerando autoload otimizado com Composer (dump-autoload -o)..."
# Reason: Reconstrói o mapa de classes para melhorar o desempenho de carregamento de classes
composer dump-autoload -o

# -----------------------------------------------------------------------------
# LIMPEZA DE CACHES DO LARAVEL
# -----------------------------------------------------------------------------
echo "⚡ Limpando caches (config, rotas, views, eventos)..."
# Reason: Remove caches antigas para evitar inconsistências após alterações de código
php artisan optimize:clear

# -----------------------------------------------------------------------------
# LINK SÍMBOLICO DO STORAGE
# -----------------------------------------------------------------------------
echo "🔗 Criando link simbólico storage (storage:link)..."
# Reason: Garante acesso público aos arquivos armazenados em storage/app/public via public/storage
php artisan storage:link || true

# -----------------------------------------------------------------------------
# BUILD DE ASSETS FRONT-END
# -----------------------------------------------------------------------------
echo "📦 Instalando dependências JavaScript/CSS (npm ci)..."
# Reason: Instala versões exatas de pacotes front-end definidas no package-lock.json
npm ci --silent

echo "🚧 Compilando assets front-end (npm run build)..."
# Reason: Gera arquivos otimizados (minificados) para produção
npm run build --silent


# -----------------------------------------------------------------------------
# CACHE DE CONFIGURAÇÃO, ROTAS E VIEWS
# -----------------------------------------------------------------------------
echo "⚡ Gerando cache de config, rotas e views para produção..."
# Reason: Cria arquivos de cache para acelerar inicialização da aplicação em produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# -----------------------------------------------------------------------------
# REINÍCIO DE FILAS E SERVIÇOS
# -----------------------------------------------------------------------------
echo "▶ Reiniciando workers de fila (queue:restart)..."
# Reason: Faz os workers recarregarem o novo código sem reiniciar o serviço manualmente
php artisan queue:restart

echo "🔄 Reiniciando serviços (PHP-FPM e Nginx)..."
# Reason: Aplica mudanças de configuração e limpa OPcache para o novo deployment
sudo systemctl restart php8.4-fpm || true
sudo systemctl reload nginx || true

# -----------------------------------------------------------------------------
# REGISTRO DE VERSÃO
# -----------------------------------------------------------------------------
echo "📌 Registrando versão no RELEASE.txt..."
# Reason: Mantém histórico de deploy com data/hora e hash do commit para auditoria
echo "$(date '+%Y-%m-%d %H:%M:%S') — $(git rev-parse --short HEAD)" > RELEASE.txt

# -----------------------------------------------------------------------------
# 13. DESATIVAÇÃO DO MODO DE MANUTENÇÃO
# -----------------------------------------------------------------------------
echo "🔓 Desativando modo de manutenção..."
# Reason: Traz o site de volta online após completar o deploy
php artisan up

# -----------------------------------------------------------------------------
# NOTIFICAÇÃO DE DEPLOY (SLACK)
# -----------------------------------------------------------------------------
# Reason: Informa a equipe sobre o sucesso do deploy via canal de comunicação
if [[ -n "$SLACK_WEBHOOK_URL" ]]; then
  echo "💬 Enviando notificação para o Slack..."
  payload=$(jq -nc --arg text "Deploy realizado: $(git rev-parse --short HEAD) em $(date '+%Y-%m-%d %H:%M:%S')" '{text: $text}')
  curl -X POST -H 'Content-type: application/json' --data "$payload" "$SLACK_WEBHOOK_URL"
fi

# -----------------------------------------------------------------------------
# FINALIZAÇÃO
# -----------------------------------------------------------------------------
echo "✅ Deploy concluído com sucesso 23456789111111111!"
