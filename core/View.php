<?php
/**
 * View Class
 * Handles view rendering
 */
class View
{
    /**
     * Render a view file
     */
    public static function render($view, $data = [])
    {
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View not found: {$view}");
        }

        // Extract data array to variables
        extract($data);

        // Start output buffering
        ob_start();

        // Include view file
        require $viewFile;

        // Return buffered content
        return ob_get_clean();
    }

    /**
     * Render and output a view
     */
    public static function make($view, $data = [])
    {
        echo self::render($view, $data);
    }

    /**
     * Include a partial view
     */
    public static function include($view, $data = [])
    {
        return self::render($view, $data);
    }
}
