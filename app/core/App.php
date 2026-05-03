<?php
class App {
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        if ($url && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            if (defined('BASE_URL') && BASE_URL !== '') {
                $base = trim(BASE_URL, '/');
                if ($base !== '') {
                    $basePrefix = $base . '/';
                    if (stripos($url, $basePrefix) === 0) {
                        $url = substr($url, strlen($basePrefix));
                    } elseif (strcasecmp($url, $base) === 0) {
                        $url = '';
                    }
                }
            }

            if ($url === '') {
                return false;
            }

            return explode('/', $url);
        }
        return false;
    }
}
?>