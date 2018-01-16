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
		$battleRecord = $this->database->getDatabaseRecord("dragons.battleHeader", array("battleId"=>$battleId));
		
		$this->battleId = $battleId;
		$this->battleDifficulty = 0;
		$this->statusFlag = $battleRecord['statusFlag'];
		$this->lastChange = $battleRecord['lastChange'];
		$this->creationDate = $battleRecord['creationDate'];
		
		// load players
		$playersStmt = "select * from dragons.battleDetail where battleId = ? and entryType = 'C'";
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
				$monsterRecord = $this->database->getDatabaseRecord("dragons.monsters", array("monsterId"=>$monsterData['associatedId']));
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
			$this->deadlyEncounter += 400;
		} else if ($level == 4) {
			$this->easyEncounter += 125;
			$this->mediumEncounter += 250;
			$this->hardEncounter += 375;
			$this->deadlyEncounter += 500;
		} else if ($level == 5) {
			$this->easyEncounter += 250;
			$this->mediumEncounter += 500;
			$this->hardEncounter += 750;
			$this->deadlyEncounter += 1100;
		} else if ($level == 6) {
			$this->easyEncounter += 300;
			$this->mediumEncounter += 600;
			$this->hardEncounter += 900;
			$this->deadlyEncounter += 1400;
		} else if ($level == 7) {
			$this->easyEncounter += 350;
			$this->mediumEncounter += 750;
			$this->hardEncounter += 1100;
			$this->deadlyEncounter += 1700;
		} else if ($level == 8) {
			$this->easyEncounter += 450;
			$this->mediumEncounter += 900;
			$this->hardEncounter += 1400;
			$this->deadlyEncounter += 2100;
		} else if ($level == 9) {
			$this->easyEncounter += 550;
			$this->mediumEncounter += 1100;
			$this->hardEncounter += 1600;
			$this->deadlyEncounter += 2400;
		} else if ($level == 10) {
			$this->easyEncounter += 600;
			$this->mediumEncounter += 1200;
			$this->hardEncounter += 1900;
			$this->deadlyEncounter += 2800;
		} else if ($level == 11) {
			$this->easyEncounter += 800;
			$this->mediumEncounter += 1600;
			$this->hardEncounter += 2400;
			$this->deadlyEncounter += 3600;
		} else if ($level == 12) {
			$this->easyEncounter += 1000;
			$this->mediumEncounter += 2000;
			$this->hardEncounter += 3000;
			$this->deadlyEncounter += 4500;
		} else if ($level == 13) {
			$this->easyEncounter += 1100;
			$this->mediumEncounter += 2200;
			$this->hardEncounter += 3400;
			$this->deadlyEncounter += 5100;
		} else if ($level == 14) {
			$this->easyEncounter += 1250;
			$this->mediumEncounter += 2500;
			$this->hardEncounter += 3800;
			$this->deadlyEncounter += 5700;
		} else if ($level == 15) {
			$this->easyEncounter += 1400;
			$this->mediumEncounter += 2800;
			$this->hardEncounter += 4300;
			$this->deadlyEncounter += 6400;
		} else if ($level == 16) {
			$this->easyEncounter += 1600;
			$this->mediumEncounter += 3200;
			$this->hardEncounter += 4800;
			$this->deadlyEncounter += 7200;
		} else if ($level == 17) {
			$this->easyEncounter += 2000;
			$this->mediumEncounter += 3900;
			$this->hardEncounter += 5900;
			$this->deadlyEncounter += 8800;
		} else if ($level == 18) {
			$this->easyEncounter += 2100;
			$this->mediumEncounter += 4200;
			$this->hardEncounter += 6300;
			$this->deadlyEncounter += 9500;
		} else if ($level == 19) {
			$this->easyEncounter += 2400;
			$this->mediumEncounter += 4900;
			$this->hardEncounter += 7300;
			$this->deadlyEncounter += 10900;
		} else if ($level == 20) {
			$this->easyEncounter += 2800;
			$this->mediumEncounter += 5700;
			$this->hardEncounter += 8500;
			$this->deadlyEncounter += 12700;
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
	
	
	//------------------------------------------------------------------------
	// get easy encounter
	//------------------------------------------------------------------------
	public function getEasyEncounter() {
		return $this->easyEncounter;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get medium encounter
	//------------------------------------------------------------------------
	public function getMediumEncounter() {
		return $this->mediumEncounter;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get hard encounter
	//------------------------------------------------------------------------
	public function getHardEncounter() {
		return $this->hardEncounter;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get deadly encounter
	//------------------------------------------------------------------------
	public function getDeadlyEncounter() {
		return $this->deadlyEncounter;
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------