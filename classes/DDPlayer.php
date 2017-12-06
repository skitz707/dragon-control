<?php
//-------------------------------------------------------------------------------------------
// DDPlayer.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// DDPlayer() class definition.
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
class DDPlayer extends DDObject {
	// class properties
	protected $playerId;
	protected $playerName;
	protected $playerRace;
	protected $playerClass;
	protected $playerLevel;
	protected $playerXP;
	protected $maxHP;
	protected $currentHP;
	protected $armorClass;
	protected $strength;
	protected $dexterity;
	protected $constitution;
	protected $intelligence;
	protected $wisdom;
	protected $charisma;
	protected $initiative;
	protected $imageLocation;
	protected $statusFlag;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load player by playerId
	//------------------------------------------------------------------------
	public function loadPlayerById($playerId) {
		$playerRecord = $this->database->getDatabaseRecord("dragons.players", array("playerId"=>$playerId));
		
		$this->playerId = $playerId;
		$this->playerName = $playerRecord['playerName'];
		$this->playerRace = $playerRecord['playerRace'];
		$this->playerClass = $playerRecord['playerClass'];
		$this->playerLevel = $playerRecord['playerLevel'];
		$this->playerXP = $playerRecord['playerXP'];
		$this->maxHP = $playerRecord['maxHP'];
		$this->currentHP = $playerRecord['currentHP'];
		$this->armorClass = $playerRecord['armorClass'];
		$this->strength = $playerRecord['strength'];
		$this->dexterity = $playerRecord['dexterity'];
		$this->constitution = $playerRecord['constitution'];
		$this->intelligence = $playerRecord['intelligence'];
		$this->wisdom = $playerRecord['wisdom'];
		$this->charisma = $playerRecord['charisma'];
		$this->initiative = $playerRecord['initiative'];
		$this->imageLocation = $playerRecord['imageLocation'];
		$this->statusFlag = $playerRecord['statusFlag'];
		$this->lastChange = $playerRecord['lastChange'];
		$this->creationDate = $playerRecord['creationDate'];
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// print player card
	//------------------------------------------------------------------------
	public function printPlayerCard() {
		// check if player is unconscious
		if ($this->currentHP == 0) {
			$class = "playerCard playerUnconscious";
		} else {
			$class = "playerCard";
		}
		
		echo '
			<div class="' . $class . '">
				<img src="' . $this->imageLocation . '" width="120px" height="160px" /><br />
				<span class="playerName">' . $this->playerName . '</span><br />
				<em>' . $this->playerRace . ' / ' . $this->playerClass . '</em><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	
	//------------------------------------------------------------------------
	// print player card
	//------------------------------------------------------------------------
	public function printAdminPlayerCard() {
		// check if player is unconscious
		if ($this->currentHP == 0) {
			$class = "adminPlayerCard playerUnconscious";
		} else {
			$class = "adminPlayerCard";
		}
		
		echo '
			<div class="' . $class . '">
				<img src="' . $this->imageLocation . '" width="80px" height="120px" /><br />
				<span class="adminPlayerName">' . $this->playerName . '</span><br />
				<em>' . $this->playerRace . ' / ' . $this->playerClass . '</em><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '
				<div class="blueButton" onClick="setInit(\'player\', ' . $this->playerId . ');">Set Init</div> <div class="redButton" onClick="takeDamage(\'player\', ' . $this->playerId . ');">Take Damage</div> <div class="greenButton" onClick="heal(\'player\', ' . $this->playerId . ');">Heal</div>
			</div>
		';
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------