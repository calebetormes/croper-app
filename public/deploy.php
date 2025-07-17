<?php
// public/deploy.php

// --------------------------------------------------
// 1) Defina manualmente as credenciais de Basic Auth
define('DEPLOY_USER', 'calebetormes');          // usuário HTTP
define('DEPLOY_PASS', '#02062011Bm'); // senha HTTP

// --------------------------------------------------
// 2) Autenticação HTTP Basic
if (
    !isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_USER'] !== DEPLOY_USER ||
    $_SERVER['PHP_AUTH_PW']   !== DEPLOY_PASS
) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Área de Deploy"');
    exit('Autenticação necessária');
}

// --------------------------------------------------
// 3) Só aceita requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido');
}

// --------------------------------------------------
// 4) Token secreto (hard-code)
//    Este token deve bater com o ?token= enviado no webhook
$SECRET = '#02062011Bm';

// 5) Leitura do token via query ou header
$token = $_GET['token'] 
       ?? ($_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '');
if (!hash_equals($SECRET, $token)) {
    http_response_code(403);
    exit('Token inválido');
}

// --------------------------------------------------
// 6) (Opcional) validação de assinatura GitHub
// $payload = file_get_contents('php://input');
// $sigHeader = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
// $hash = 'sha256=' . hash_hmac('sha256', $payload, $SECRET);
// if (!hash_equals($hash, $sigHeader)) {
//     http_response_code(403);
//     exit('Assinatura inválida');
// }

// --------------------------------------------------
// 7) Executa o script de deploy e exibe saída
$deployScript = __DIR__ . '/../deploy.sh';
if (!is_executable($deployScript)) {
    http_response_code(500);
    exit('deploy.sh não encontrado ou não executável');
}

$output = shell_exec("$deployScript 2>&1");
echo "<pre>Deploy executado:\n$output</pre>";
