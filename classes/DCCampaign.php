<?php
//-------------------------------------------------------------------------------------------
// DCCampaign.php
// Written by: Michael C. Szczepanik
// January 14th, 2018
// DCCampaign() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DCObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// campaign class definition
//-------------------------------------------------------------------------------------------
class DCCampaign extends DCObject {
	// class properties
	protected $campaignId;
	protected $campaignName;
	protected $campaignLeader;
	protected $statusFlag;
	protected $lastPlayed;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load campaign by id
	//------------------------------------------------------------------------
	public function loadCampaignById($campaignId) {
		$campaignHeader = $this->database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));
		
		$this->campaignId = $campaignId;
		$this->campaignName = $campaignHeader['campaignName'];
		$this->campaignLeader = $campaignHeader['campaignLeader'];
		$this->statusFlag = $campaignHeader['statusFlag'];
		$this->lastPlayer = $campaignHeader['lastPlayed'];
		$this->lastChange = $campaignHeader['lastChange'];
		$this->creationDate = $campaignHeader['creationDate'];
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get active players in campaign
	//------------------------------------------------------------------------
	public function getActiveCharacters() {
		$selectStmt = "select characterId from dragons.characters where campaignId = ? and statusFlag = 'A'";
		$returnArray = array();
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->campaignId))) {
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
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get campaign name
	//------------------------------------------------------------------------
	public function getCampaignName() {
		return $this->campaignName;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get creationd ate
	//------------------------------------------------------------------------
	public function getCreationDate() {
		return $this->creationDate;
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------