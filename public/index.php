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

$sessionPath = getenv('SESSION_SAVE_PATH');
if ($sessionPath === false || $sessionPath === '') {
    $sessionPath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR
        . 'itquest_sessions';
}
if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0755, true);
}
if (is_dir($sessionPath) && is_writable($sessionPath)) {
    session_save_path($sessionPath);
}

$cookiePath = getenv('SESSION_COOKIE_PATH');
if ($cookiePath === false || $cookiePath === '') {
    $cookiePath = BASE_URL !== '' ? BASE_URL . '/' : '/';
}
$cookieDomain = getenv('SESSION_COOKIE_DOMAIN');
$forwardProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || strtolower($forwardProto) === 'https';

$cookieParams = [
    'lifetime' => 0,
    'path' => $cookiePath,
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
];
if ($cookieDomain !== false && $cookieDomain !== '') {
    $cookieParams['domain'] = $cookieDomain;
}
session_set_cookie_params($cookieParams);

session_start();

$app = new App();
?>