<?php

class Home extends Controller
{
    public function index($name = '')
    {
        $this->CheckAuth();
        $user = $this->model('User');       
        $this->view('Home/index', $user);
    }
}