<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of database
 *
 * @author Nick Orloski
 */
class Database {

    private $db_server = 'localhost';
    private $username = 'dc_admin';
    private $password = 'dc_admin';
    private $database = 'dragons';
    public $conn;
    
    function __construct(){
        $this->conn = mysqli_connect($this->db_server, $this->username, $this->password, $this->database);
    }
    
    public function Query($query){
        return $results = mysqli_query($this->conn, $query);
    }
    
    public function Connect(){
        $this->conn = mysqli_connect($this->db_server, $this->username, $this->password, $this->database);
    }
}