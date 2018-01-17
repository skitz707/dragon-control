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
class Admin extends Controller{
    
    public function index()
    {
        $this->CheckAuth();
        $this->CheckAdminAuth();
        $this->DisplayMenu();
        $user = $this->model('User');       
        $this->view('Dashboard/index', $user);
    }
    
    public function Users()
    {
        
    }
    
    public function CreateUser()
    {
        $this->CheckAuth();
        $user = $this->model('User');       
        $this->view('Dashboard/index', $user);
    }
    
    private function CheckAdminAuth()
    {
        if(isset($_SESSION['IsAdmin']))
        {
            $_SESSION['IsAdmin'] == 1;
        }
        else
        {
           header ('Location: /Dragon-Control/public/Login');
        }
    }
}
