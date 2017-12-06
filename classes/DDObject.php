<?php
//-------------------------------------------------------------------------------------------
// Dungeons and Dragons - DDObject()
// 
// Written by: Michael C. Szczepanik
// November 30th, 2017
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// class includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDDatabase.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// class definition
//-------------------------------------------------------------------------------------------
abstract class DDObject {
	// public variable declaration
	protected $database;
	
	
	//-----------------------------------------------------------------------
	// constructor function
	//-----------------------------------------------------------------------
	public function __construct($databaseObj) {
		$this->database = new DDDatabase();
	}
	//-----------------------------------------------------------------------
	
	
	//-----------------------------------------------------------------------
	// print contents of object properties
	//-----------------------------------------------------------------------
	public function description() {
		//echo "Description (Object)\n";
		
		$classPropertiesArray = get_object_vars($this);
		
		var_dump($classPropertiesArray);
	}
	//-----------------------------------------------------------------------
	
	
	//-----------------------------------------------------------------------
	// reset variables in class object
	//-----------------------------------------------------------------------
	protected function resetObject() {
		foreach (get_class_vars(get_class($this)) as $var => $value) {
			if ($var != "database") {
				$this->$var = null;
			}
		}
	}
	//-----------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------
?>