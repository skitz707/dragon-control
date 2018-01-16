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
class Admin {
    
    public function index()
    {
        $this->CheckAuth();
        $user = $this->model('User');       
        $this->view('Home/index', $user);
    }
    
    public function Users()
    {
        
    }
}
