<?php

class Dashboard extends Controller
{
    public function index($name = '')
    {
        $this->CheckAuth();
        $this->DisplayMenu();
        $user = $this->model('User');       
        $this->view('Dashboard/index', $user);
    }
}