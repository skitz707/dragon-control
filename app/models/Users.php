<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersGrid
 *
 * @author Nick Orloski
 */
require_once('User.php');

class Users extends Model
{
    public $users = [];
    
    function __construct()
    {
        $this->db = new Database();
    }
    
    public function FetchUsers()
    {        
        $results_array = [];
        $stmt = "Select * from userMaster";
        echo $stmt;
        $results = $this->db->conn->Query($stmt);
        
        if(mysqli_num_rows($results) > 0)
        {
          while ($row = mysqli_fetch_array($results)) 
         {
            $results_array[] = $row;
         }
            
        }
         
         $this->users = $results_array;
    }
}
