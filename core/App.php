<?php
/**
 * Core App Class
 * Handles routing by parsing the URL.
 */
class App {
    protected $controller = 'Pages';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check if the requested controller exists
        if (isset($url[0]) && file_exists(APP_ROOT . '/app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->controller = ucwords($url[0]);
            unset($url[0]);
        }

        // Require the controller and instantiate it
        require_once APP_ROOT . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check if the requested method exists within the controller
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Get parameters
        $this->params = $url ? array_values($url) : [];

        // Call the method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
