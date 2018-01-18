<?php

class User extends Model
{
    public $FirstName  = 'handle';
    public $LastName;
    public $email;
    public $activeCharacterId;
    
    function __construct(){
        $this->db = new Database();
    }
    
    public function Insert($fn, $ln, $email, $pw)
    {
        $stmt = "Insert into userMaster (firstName, lastName, emailAddress, passwordHash)"
                . "values ('".$fn ."','" . $ln . "','" . $email. "','". $pw . "')";
        echo $stmt;
        $result = mysqli_query($this->db->conn, $stmt);
        
        if($result == null){
            echo "<br>This failed for some reason";
            echo mysqli_error($this->db->conn);
        }
    }
}