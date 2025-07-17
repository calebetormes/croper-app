#!/usr/bin/env bash
#
# deploy.sh
# Script de deploy automático para o ambiente DEV
# Logs todas as ações em deploy-run.log

# Caminho absoluto do projeto (ajuste se necessário)
APP_DIR="/home/barterapp-dev-croper/htdocs/croper-app"

# Arquivo de log
LOG="${APP_DIR}/deploy-run.log"

# Início do bloco de log
{
  echo "====================================="
  echo "Início do deploy: $(date '+%Y-%m-%d %H:%M:%S')"
  
  # Quem está executando o script
  echo "Usuário: $(whoami)"
  
  # Diretório atual antes de cd
  echo "PWD antes: $(pwd)"
  
  # Vai para o diretório da aplicação
  cd "${APP_DIR}" || { echo "Erro: não foi possível cd em ${APP_DIR}"; exit 1; }
  echo "PWD após cd: $(pwd)"
  
  # Remotos configurados
  echo "--- Git remotes ---"
  git remote -v
  
  # Atualização forçada do código
  echo "--- Reset hard e clean ---"
  git fetch origin main                # busca último estado de main
  git reset --hard origin/main         # joga fora alterações locais
  git clean -fd                        # remove arquivos não rastreados
  
  # Informação de branch e commit
  echo "Branch atual: $(git rev-parse --abbrev-ref HEAD)"
  echo "Commit HEAD: $(git rev-parse HEAD)"
  
  # Instalação das dependências PHP
  echo "--- composer install ---"
  composer install --no-dev --optimize-autoloader
  
  # Instalação e build dos assets JS/CSS
  echo "--- npm ci & build ---"
  npm ci
  npm run build
  
  # Migrations do Laravel
  echo "--- php artisan migrate ---"
  php artisan migrate --force
  
  echo "Fim do deploy: $(date '+%Y-%m-%d %H:%M:%S')"
  echo
} >> "${LOG}" 2>&1
