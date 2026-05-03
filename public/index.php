<?php
ob_start();
// Simple autoloader for our MVC architecture
spl_autoload_register(function ($className) {
    if (file_exists('../app/controllers/' . $className . '.php')) {
        require_once '../app/controllers/' . $className . '.php';
    } elseif (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
    } elseif (file_exists('../app/core/' . $className . '.php')) {
        require_once '../app/core/' . $className . '.php';
    }
});

if (!defined('BASE_URL')) {
    $envBase = getenv('APP_BASE_URL');
    if ($envBase !== false && $envBase !== '') {
        $baseUrl = '/' . trim($envBase, '/');
    } else {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        $baseUrl = rtrim($scriptDir, '/');
        if ($baseUrl === '/') {
            $baseUrl = '';
        }
    }
    define('BASE_URL', $baseUrl);
}

$cookiePath = getenv('SESSION_COOKIE_PATH');
if ($cookiePath === false || $cookiePath === '') {
    $cookiePath = BASE_URL !== '' ? BASE_URL . '/' : '/';
}
$forwardProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || strtolower($forwardProto) === 'https';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => $cookiePath,
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

$app = new App();
?>