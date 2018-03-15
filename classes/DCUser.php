<?php
//-------------------------------------------------------------------------------------------
// DCUser.php
// Written by: Michael C. Szczepanik
// January 14th, 2018
// DCUser() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DCObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// user class definition
//-------------------------------------------------------------------------------------------
class DCUser extends DCObject {
	// class properties
	protected $userId;
	protected $emailAddress;
	protected $firstName;
	protected $lastName;
	protected $lastLogin;
	protected $lastChange;
	protected $creationDate;
	
	
	//-----------------------------------------------------------------------
	// constructor override
	//-----------------------------------------------------------------------
	public function __construct($database) {
		parent::__construct($database);
		
		// get user cookie
		if (isset($_COOKIE['userId'])) {
			$this->loadUserById($_COOKIE['userId']);
		}
	}
	//-----------------------------------------------------------------------
	
	
	//-----------------------------------------------------------------------
	// load user by id
	//-----------------------------------------------------------------------
	public function loadUserById($userId) {
		$userMaster = $this->database->getDatabaseRecord("dragons.userMaster", array("userId"=>$userId));
		
		$this->userId = $userId;
		$this->emailAddress = $userMaster['emailAddress'];
		$this->firstName = $userMaster['firstName'];
		$this->lastName = $userMaster['lastName'];
		$this->lastLogin = $userMaster['lastLogin'];
		$this->lastChange = $userMaster['lastChange'];
		$this->creationDate = $userMaster['creationDate'];
	}
	//-----------------------------------------------------------------------
	
	
	//-----------------------------------------------------------------------
	// get campaigns leading
	//-----------------------------------------------------------------------
	public function getCampaignsLeading() {
		$selectStmt = "select campaignId from dragons.campaignHeader where campaignLeader = ?";
		$returnArray = array();
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->userId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
				$returnArray[] = $data['campaignId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		return $returnArray;
	}
	//-----------------------------------------------------------------------
	
	
	
	//-----------------------------------------------------------------------
	// get active characers
	//-----------------------------------------------------------------------
	public function getActiveCharacters() {
		$selectStmt = "select characterId from dragons.characters where ownerId = ?";
		$returnArray = array();
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->userId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
				$returnArray[] = $data['characterId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		return $returnArray;
	}
	//-----------------------------------------------------------------------
	
	
	//-----------------------------------------------------------------------
	// get email address
	//-----------------------------------------------------------------------
	public function getEmailAddress() {
		return $this->emailAddress;
	}
	//-----------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------