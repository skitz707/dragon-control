<?php
//-------------------------------------------------------------------------------------------
// DDBattle.php
// Written by: Michael C. Szczepanik
// December 21st, 2017
// DDBattle() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// database class definition
//-------------------------------------------------------------------------------------------
class DDBattle extends DDObject {
	// class properties
	protected $battleId;
	protected $playersArray;
	protected $monstersArray;
	protected $battleOrder;
	protected $battleDifficulty;
	protected $easyEncounter;
	protected $mediumEncounter;
	protected $hardEncounter;
	protected $deadlyEncounter;
	protected $statusFlag;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load battle by battleId
	//------------------------------------------------------------------------
	public function loadBattleById($battleId) {
		$battleRecord = $this->database->getDatabaseRecord("dragons.battleHeader", array("entryId"=>$battleId));
		
		$this->battleId = $battleId;
		$this->battleDifficulty = 0;
		$this->statusFlag = $battleRecord['statusFlag'];
		$this->lastChange = $battleRecord['lastChange'];
		$this->creationDate = $battleRecord['creationDate'];
		
		// load players
		$playersStmt = "select * from dragons.battleDetail where battleId = ? and entryType = 'P'";
		$this->playersArray = array();
		$this->playerXPStrength = 0;
		
		if ($playersHandle = $this->database->databaseConnection->prepare($playersStmt)) {
			if (!$playersHandle->execute(array(0=>$this->battleId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($playerData = $playersHandle->fetch(PDO::FETCH_ASSOC)) {
				$playerMaster = $this->database->getDatabaseRecord("dragons.players", array("playerId"=>$playerData['associatedId']));
				$this->playersArray[] = $playerData['associatedId'];
				$this->playerXPStrength += $this->addPlayerXPFromLevel($playerMaster['playerLevel']);
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		// load monsters
		$playersStmt = "select * from dragons.battleDetail where battleId = ? and entryType = 'M'";
		$this->monstersArray = array();
		
		if ($playersHandle = $this->database->databaseConnection->prepare($playersStmt)) {
			if (!$playersHandle->execute(array(0=>$this->battleId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($monsterData = $playersHandle->fetch(PDO::FETCH_ASSOC)) {
				$monsterRecord = $this->database->getDatabaseRecord("dragons.monsters", array("entryId"=>$monsterData['associatedId']));
				$this->monstersArray[] = $monsterData['associatedId'];
				$this->battleDifficulty += $monsterRecord['xpRating'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}	
		
		// load battle order
		$orderStmt = "select * from dragons.battleDetail where battleId = ? order by initiative desc";
		$this->battleOrder = array();
		$i = 0;
		
		if ($orderHandle = $this->database->databaseConnection->prepare($orderStmt)) {
			if (!$orderHandle->execute(array(0=>$this->battleId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($orderData = $orderHandle->fetch(PDO::FETCH_ASSOC)) {
				$detailRecord = $this->database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$orderData['entryId']));
				
				if (!($orderData['entryType'] == "M" && $detailRecord['currentHP'] == 0)) {
					$this->battleOrder[$i]['detailId'] = $orderData['entryId'];
					$this->battleOrder[$i]['type'] = $orderData['entryType'];
					$this->battleOrder[$i]['associatedId'] = $orderData['associatedId'];
					
					$i++;
				}	
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get player xp based on level
	//------------------------------------------------------------------------
	private function addPlayerXPFromLevel($level) {
		if ($level == 1) {
			$this->easyEncounter += 25;
			$tihs->mediumEncounter += 50;
			$this->hardEncounter += 75;
			$this->deadlyEncounter += 100;
		} else if ($level == 2) {
			$this->easyEncounter += 50;
			$this->mediumEncounter += 100;
			$this->hardEncounter += 150;
			$this->deadlyEncounter += 200;
		} else if ($level == 3) {
			$this->easyEncounter += 75;
			$this->mediumEncounter += 150;
			$this->hardEncounter += 225;
			$this->deadlyEncounter += 300;
		} else if ($level == 4) {
			$this->easyEncounter += 100;
			$this->mediumEncounter += 200;
			$this->hardEncounter += 300;
			$this->deadlyEncounter += 400;
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get battle difficulty
	//------------------------------------------------------------------------
	public function getBattleDifficulty() {
		return $this->battleDifficulty;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get players array
	//------------------------------------------------------------------------
	public function getPlayersArray() {
		return $this->playersArray;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get monsters array
	//------------------------------------------------------------------------
	public function getMonstersArray() {
		return $this->monstersArray;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get battle order
	//------------------------------------------------------------------------
	public function getBattleOrder() {
		return $this->battleOrder;
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------