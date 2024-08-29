<?php
/*
 * App Core Class
 * Create URL & Load Core Controller
 * URL Format - controller/method/param
 */
class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        // Look in Controllers for first value
        if(file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')){

            // if exists set as controller
            $this->currentController = ucwords($url[0]);

            // unset 0 index
            unset($url[0]);
        }

        // Require the Controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Init the Controller
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if(isset($url[1])){

            // Check to see if method exists in controller
            if(method_exists($this->currentController,$url[1])){

                $this->currentMethod = $url[1];

                // unset 1 index
                unset($url[1]);
            }
        }

        // Get Params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController,$this->currentMethod],$this->params);
        
    }

    public function getUrl() {
        if( isset($_GET['url']) ){
            $url = str_replace('public/', '', $_GET['url']);
            $url = rtrim($url, '/');
            $url = explode('/' , $url);
            return $url;
        }
        return ['pages'];
    }
}