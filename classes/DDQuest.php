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
	
	
	//------------------------------------------------------------------------
	// create new battle
	//------------------------------------------------------------------------
	public function createNewBattle() {
		$this->database->insertDatabaseRecord("dragons.battleHeader", array("questId"=>$this->questId, "statusFlag"=>"A"));
		$battleId = $this->database->getColumnMax("dragons.battleHeader", "battleId", array("questId"=>$this->questId));

		// add characters to the battle
		$characterStmt = "select * from dragons.characters where campaignId = ? and statusFlag = 'A'";

		if ($characterHandle = $this->database->databaseConnection->prepare($characterStmt)) {
			if (!$characterHandle->execute(array(0=>$this->campaignId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($characterData = $characterHandle->fetch(PDO::FETCH_ASSOC)) {
				$detail['battleId'] = $battleId;
				$detail['entryType'] = "C";
				$detail['associatedId'] = $characterData['characterId'];
				$detail['currentHP'] = $characterData['currentHP'];
				$detail['initiative'] = 0;
				
				$this->database->insertDatabaseRecord("dragons.battleDetail", $detail);
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get campaign id
	//------------------------------------------------------------------------
	public function getCampaignId() {
		return $this->campaignId;
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------