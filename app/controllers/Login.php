<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author Nick Orloski
 */
class Login extends Controller
{
    public function index()
    {
        $auth = $this->model('Auth');       
        $this->view('Login/Index', $auth);
    }
    
    public function Process()
    {
        $auth = $this->model('Auth'); 

        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $un = $_POST['username'];
            $pw = $_POST['password'];
            
            if($auth->Login($un, $pw))
            {
                header ('Location: /Dragon-Control/public/');
            }
            else
            {
                $auth->loginResult = false;
                $this->view('Login/Index', $auth);
            }
        }
        else
        {
            $auth->loginResult = false;
            $this->view('Login/Index', $auth);
        }
    }
    
    public function LogOff()
    {
        session_destroy();
        session_start();
        header ('Location: /Dragon-Control/public');
    }
}