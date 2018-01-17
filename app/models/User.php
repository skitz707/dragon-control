<?php

class User extends Model{
    public $name  = 'handle';
    
    function __construct(){
        $this->db = new Database();
        $this->name = $this->GetCharacterName();
    }
    
    public function GetCharacterName(){
        //static function for tetsing, needs to be replaced with data from the view.
        $qry = "SELECT * FROM Characters WHERE characterID ='2'";
        $result = mysqli_query($this->db->conn, $qry);
        $row = mysqli_fetch_assoc($result);
        $characterName = $row['CharacterName'];
        
        return $characterName;
    }
}