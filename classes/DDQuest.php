<?php
//-------------------------------------------------------------------------------------------
// DDQuest.php
// Written by: Michael C. Szczepanik
// January 14th, 2018
// DDQuest() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// quest class definition
//-------------------------------------------------------------------------------------------
class DDQuest extends DDObject {
	// class properties
	protected $questId;
	protected $campaignId;
	protected $questName;
	protected $statusFlag;
	protected $lastPlayed;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load quest by id
	//------------------------------------------------------------------------
	public function loadQuestById($questId) {
		$questHeader = $this->database->getDatabaseRecord("dragons.questHeader", array("questId"=>$questId));
		
		$this->questId = $questId;
		$this->campaignId = $questHeader['campaignId'];
		$this->questName = $questHeader['questName'];
		$this->statusFlag = $questHeader['statusFlag'];
		$this->lastPlayer = $questHeader['lastPlayed'];
		$this->lastChange = $questHeader['lastChange'];
		$this->creationDate = $questHeader['creationDate'];
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------