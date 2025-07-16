#!/bin/bash

# Caminho absoluto do projeto
ROOT_DIR=$(cd "$(dirname "$0")"; pwd)

# Usuário do servidor (definido no painel)
SITE_USER="barterapp-dev-croper"

echo "🚀 Iniciando script pós-deploy Laravel..."

echo "📁 Ajustando permissões em storage/ e bootstrap/cache..."
chown -R $SITE_USER:$SITE_USER "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"
chmod -R 775 "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"

echo "📄 Garantindo que o arquivo de log exista..."
mkdir -p "$ROOT_DIR/storage/logs"
touch "$ROOT_DIR/storage/logs/laravel.log"
chown $SITE_USER:$SITE_USER "$ROOT_DIR/storage/logs/laravel.log"
chmod 664 "$ROOT_DIR/storage/logs/laravel.log"

echo "✅ Permissões corrigidas com sucesso!"
