#!/bin/bash

# Caminho absoluto do projeto
ROOT_DIR=$(cd "$(dirname "$0")"; pwd)
SITE_USER="barterapp-dev-croper"

# Navega para o diretório raiz do projeto
cd "$ROOT_DIR" || exit

echo "🚀 Iniciando script pós-deploy Laravel..."

# Publica os assets do Livewire (livewire.js)
echo "🔄 Publicando assets do Livewire..."
php artisan vendor:publish --tag=livewire:assets --force

# Limpa caches do Laravel (config, rotas, views, etc.)
echo "⚡ Limpando caches Laravel..."
php artisan optimize:clear

# Ajusta permissões em storage/ e bootstrap/cache
echo "📁 Ajustando permissões em storage/ e bootstrap/cache..."
chown -R $SITE_USER:$SITE_USER "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"
chmod -R 775 "$ROOT_DIR/storage" "$ROOT_DIR/bootstrap/cache"

# Garante que o arquivo de log exista
echo "📄 Garantindo que o arquivo de log exista..."
mkdir -p "$ROOT_DIR/storage/logs"
touch "$ROOT_DIR/storage/logs/laravel.log"
chown $SITE_USER:$SITE_USER "$ROOT_DIR/storage/logs/laravel.log"
chmod 664 "$ROOT_DIR/storage/logs/laravel.log"

echo "✅ Permissões e assets ajustados com sucesso!"