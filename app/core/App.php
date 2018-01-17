<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author Nick Orloski
 */
class App
{ 
    protected $controller = 'Dashboard';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct()
    {
        session_start();
        $url = $this->parseUrl();    
        
        if(file_exists('../app/controllers/' .$url[0]. '.php'))
        {
            $this->controller =$url[0];
            unset($url[0]);
        }
        
        require_once '../app/controllers/' .$this->controller . '.php';
        $this->controller = new $this->controller;
        
        if(isset($url[1]))
        {
            if(method_exists($this->controller, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    protected function parseUrl()
    {
        if(isset($_GET['url']))
        {
            return $url = explode('/',filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
    
    protected function CheckAuth(){
        $user = $_SESSION['UserName'];
        $loggedIn = $_SESSION['LoggedIn'];
        $userID = $_SESSION['UserID'];
        $IsAdmin = $_SESSION['IsAdmin'];
        
        if($loggedIn == false)
        {
            return false;
        }
        else 
        {
            return true;
        }
    }
    
    protected function RedidrecToLogin()
    {
        $this->controller = 'Login';
        $this->model = '';
    }
}