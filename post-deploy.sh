#!/bin/bash
# post-deploy.sh - Script de pós-deploy automatizado para Laravel + Filament
# Descrição: publica assets, limpa caches, executa migrations, build front-end,
#             ajusta permissões, reinicia serviços e envia notificação opcional.

# Interrompe a execução em caso de qualquer erro para evitar deploy parcial
set -e

# -----------------------------------------------------------------------------
# CONFIGURAÇÃO INICIAL
# -----------------------------------------------------------------------------
# Obtém o caminho absoluto do diretório raiz do projeto (onde está este script)
ROOT_DIR=$(cd "$(dirname "$0")" && pwd)

# Define o usuário do sistema responsável pelo servidor web / PHP-FPM
SITE_USER="barterapp-dev-croper"

# Lê variável opcional para enviar notificações ao Slack
SLACK_WEBHOOK_URL="${SLACK_WEBHOOK_URL:-}"

# Move-se para o diretório do projeto; necessário para que todos os comandos sejam executados no contexto correto
echo "📂 Acessando diretório do projeto: $ROOT_DIR"
cd "$ROOT_DIR" || { echo "❌ Não foi possível acessar $ROOT_DIR"; exit 1; }

# -----------------------------------------------------------------------------
# 1. MODO DE MANUTENÇÃO
# -----------------------------------------------------------------------------
echo "🔒 Habilitando modo de manutenção..."
# Reason: Evita que usuários vejam erros durante o deploy e mantém o site offline temporariamente
php artisan down --secret="deploying"

# -----------------------------------------------------------------------------
# 2. OTIMIZAÇÃO DO AUTOLOAD
# -----------------------------------------------------------------------------
echo "🧩 Gerando autoload otimizado com Composer (dump-autoload -o)..."
# Reason: Reconstrói o mapa de classes para melhorar o desempenho de carregamento de classes
composer dump-autoload -o

# -----------------------------------------------------------------------------
# 3. PUBLICAÇÃO DE ASSETS DO LIVEWIRE
# -----------------------------------------------------------------------------
echo "🔄 Publicando assets do Livewire (livewire.js)..."
# Reason: Garante que os arquivos de front-end do Livewire sejam copiados para public/vendor/livewire
php artisan vendor:publish --tag=livewire:assets --force

# -----------------------------------------------------------------------------
# 4. LIMPEZA DE CACHES DO LARAVEL
# -----------------------------------------------------------------------------
echo "⚡ Limpando caches (config, rotas, views, eventos)..."
# Reason: Remove caches antigas para evitar inconsistências após alterações de código
php artisan optimize:clear

# -----------------------------------------------------------------------------
# 5. LINK SÍMBOLICO DO STORAGE
# -----------------------------------------------------------------------------
echo "🔗 Criando link simbólico storage (storage:link)..."
# Reason: Garante acesso público aos arquivos armazenados em storage/app/public via public/storage
php artisan storage:link || true

# -----------------------------------------------------------------------------
# 6. MIGRAÇÕES E SEEDERS
# -----------------------------------------------------------------------------
echo "🗄️  Executando migrações de banco de dados..."
# Reason: Atualiza a estrutura do banco de dados conforme mudanças em migrations
php artisan migrate --force

echo "🌱 Executando seeders (população de dados)..."
# Reason: Popula tabelas com dados padrão ou de teste necessários para a aplicação
php artisan db:seed --force

# -----------------------------------------------------------------------------
# 7. BUILD DE ASSETS FRONT-END
# -----------------------------------------------------------------------------
echo "📦 Instalando dependências JavaScript/CSS (npm ci)..."
# Reason: Instala versões exatas de pacotes front-end definidas no package-lock.json
npm ci --silent

echo "🚧 Compilando assets front-end (npm run build)..."
# Reason: Gera arquivos otimizados (minificados) para produção
npm run build --silent

# -----------------------------------------------------------------------------
# 8. CACHE DE CONFIGURAÇÃO, ROTAS E VIEWS
# -----------------------------------------------------------------------------
echo "⚡ Gerando cache de config, rotas e views para produção..."
# Reason: Cria arquivos de cache para acelerar inicialização da aplicação em produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# -----------------------------------------------------------------------------
# 9. AJUSTE DE PERMISSÕES
# -----------------------------------------------------------------------------
echo "📁 Ajustando permissões em storage/ e bootstrap/cache..."
# Reason: Garante permissão de leitura/escrita para o servidor web nesses diretórios críticos
chown -R $SITE_USER:$SITE_USER "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"
chmod -R 775 "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"

# -----------------------------------------------------------------------------
# 10. GARANTIA DO ARQUIVO DE LOG
# -----------------------------------------------------------------------------
echo "📄 Garantindo existência e permissão do laravel.log..."
# Reason: Assegura que o arquivo de log exista e possa ser escrito pela aplicação
mkdir -p "$ROOT_DIR/storage/logs"
touch "$ROOT_DIR/storage/logs/laravel.log"
chown $SITE_USER:$SITE_USER "$ROOT_DIR/storage/logs/laravel.log"
chmod 664 "$ROOT_DIR/storage/logs/laravel.log"

# -----------------------------------------------------------------------------
# 11. REINÍCIO DE FILAS E SERVIÇOS
# -----------------------------------------------------------------------------
echo "▶ Reiniciando workers de fila (queue:restart)..."
# Reason: Faz os workers recarregarem o novo código sem reiniciar o serviço manualmente
php artisan queue:restart

echo "🔄 Reiniciando serviços (PHP-FPM e Nginx)..."
# Reason: Aplica mudanças de configuração e limpa OPcache para o novo deployment
sudo systemctl restart php8.4-fpm || true
sudo systemctl reload nginx || true

# -----------------------------------------------------------------------------
# 12. REGISTRO DE VERSÃO
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
# 14. NOTIFICAÇÃO DE DEPLOY (SLACK)
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
echo "✅ Deploy concluído com sucesso!"
