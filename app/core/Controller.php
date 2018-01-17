<?php

class Controller
{
    protected function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
    
    protected function view($view, $data = [])
    {
        require_once '../app/views/' . $view .'.php';
    }
    
    protected function CheckAuth(){
        if(isset($_SESSION['UserID']) == false)
        {
            header ('Location: /Dragon-Control/public/Login');
        }
    }
    
    protected function DisplayMenu()
    {
        require_once '../app/views/Menu/Index.php';
    }
}