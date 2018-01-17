<?php

class Home extends Controller
{
    public function index($name = '')
    {
        $this->CheckAuth();
        $this->DisplayMenu();
        $user = $this->model('User');       
        $this->view('Home/index', $user);
    }
}