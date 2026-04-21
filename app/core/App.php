<?php
/*
 * App Core Class
 * Creates URL & loads controller
 * URL FORMAT - /controller/method/params
 */
class App {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        // print_r($this->getUrl());

        $url = $this->getUrl();

        // Look in controllers for first value
        if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
          // If exists, set as controller
          $this->currentController = ucwords($url[0]);
          // Unset 0 Index
          unset($url[0]);
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if(isset($url[1])){
          // Check to see if method exists in controller
          if(method_exists($this->currentController, $url[1])){
            $this->currentMethod = $url[1];
            // Unset 1 index
            unset($url[1]);
          }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        try {
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } catch (\Throwable $e) {
            // Global safety: if an error occurs and isn't caught by the controller, 
            // show a graceful error instead of a 500 crash.
            die('<div style="font-family:sans-serif; padding: 20px; border: 1px solid #cc0000; background: #fff5f5; color: #cc0000; border-radius: 8px; margin: 40px auto; max-width: 600px;">
                <h2 style="margin-top:0;">System Initialization Error</h2>
                <p>The application encountered a problem. Please ensure the database is connected and all tables exist.</p>
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 15px 0;">
                <small><strong>Error Detail:</strong> ' . $e->getMessage() . '</small>
                <br><br>
                <a href="' . (defined('URLROOT') ? URLROOT : '/') . '" style="display:inline-block; padding: 10px 20px; background: #cc0000; color: #white; text-decoration: none; border-radius: 4px; color: #fff;">Home</a>
            </div>');
        }
    }

    public function getUrl(){
        if(isset($_GET['url'])){
          $url = rtrim($_GET['url'], '/');
          $url = filter_var($url, FILTER_SANITIZE_URL);
          $url = explode('/', $url);
          return $url;
        }
    }
}
