<?php
class Controller {
    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            // Extract the associative array into variables
            extract($data);
            require_once '../app/views/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }

    public function redirect($url) {
        header("Location: /quest/public/" . $url);
        exit();
    }
}
?>