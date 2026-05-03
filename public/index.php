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

session_start();

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

$app = new App();
?>