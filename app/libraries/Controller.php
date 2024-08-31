<?php
namespace didikala\libraries;

 use didikala\models\User;

 class Controller {

    // Load model
    public function model($model){

        // Require model file
        require_once '../app/models/' . $model . '.php';
        $model = "didikala\models\\$model";

        // init model file
        return new $model;
    }

    // Load view
    public function view($view, $data = []){

        // Check for view file
        if(file_exists('../app/views/' . $view . '.php')){
            // Load view file
            require_once '../app/views/' . $view . '.php';
        }else{
            // view does not exist
            die('View does not exist');
        }

    }
 }