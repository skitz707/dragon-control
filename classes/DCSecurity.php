<?php
//-------------------------------------------------------------------------------------------
// DCSecurity.php
// Written by: Michael C. Szczepanik
// January 14th, 2018
// DCSecurity() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DCObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// security class definition
//-------------------------------------------------------------------------------------------
class DCSecurity extends DCObject {
	// class properties
	protected $securtyId;
	
	public function checkLogin() {
		$userId = $_SESSION['userId'];
		
		if ($userId > 0) {
			$userMaster = $this->database->getDatabaseRecord("dragons.userMaster", array("userId"));
			
			if (!$userMaster['userId'] > 0) {
				header("Location: index.php?error=notLoggedIn");
			}
		} else {
			header("Location: index.php?error=notLoggedIn");
		}
	}
}
//-------------------------------------------------------------------------------------------