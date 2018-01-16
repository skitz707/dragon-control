<?php

class Auth extends Model
{   
    public $loginResult = true;
    
    function __construct(){
        $this->db = new Database();
    }
    
    function Login($username, $password){
        $qry = "SELECT * FROM Users WHERE Username = '". $username ."'";        
        $result = $this->db->Query($qry);
        
        if(mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_assoc($result); 
            
            if($this->LoginSuccess($password, $row))
            {
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['IsAdmin'] = $row['IsAdmin'];
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    
    private function LoginSuccess($password, $row)
    {
        if(password_verify($password, $row['Password']))
        {
           return true;
        }
        else
        {
            return false;
        }
    }
}