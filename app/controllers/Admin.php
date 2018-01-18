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
        $users = $this->model('Users');
        $users->FetchUsers();
        $this->view('Admin/Users/UsersGrid', $users);       
        
    }
    
    public function CreateUser()
    {
        $user = $this->model('User');       
        $this->view('Admin/Users/Edit', $user);
    }
    
    public function InsertUser()
    {
        $user = $this->model('User');
        $this->view('Admin/Users/Insert', $user);
        $fn = "";
        $ln = "";
        $pw = "";
        $email = "";
        
        if(isset($_POST['FirstName']))
        {   
            $fn = $_POST['FirstName'];
        }
        if(isset($_POST['LastName']))
        {
            $ln = $_POST['LastName'];
        }
        
        if(isset($_POST['Password']))
        {
            $pw = $_POST['Password'];
            $pw = md5($pw);
        }
        
        if(isset($_POST['Email']))
        {
            $email = $_POST['Email'];
        }
        
        $user->Insert($fn,$ln,$email, $pw);
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
