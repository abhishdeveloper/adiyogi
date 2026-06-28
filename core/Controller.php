<?php
/**
 * Base Controller Class
 * Provides methods for loading views and models.
 */
class Controller {
    // Load model
    protected function model($model) {
        require_once APP_ROOT . '/app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    protected function view($view, $data = []) {
        if (file_exists(APP_ROOT . '/app/views/' . $view . '.php')) {
            require_once APP_ROOT . '/app/views/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }
}
