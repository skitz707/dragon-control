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
	protected $battleDetailId;
	protected $playerName;
	protected $playerRace;
	protected $playerClass;
	protected $playerLevel;
	protected $playerXP;
	protected $maxHP;
	protected $currentHP;
	protected $armorClass;
	protected $strength;
	protected $strengthModifier;
	protected $dexterity;
	protected $dexterityModifier;
	protected $constitution;
	protected $constitutionModifier;
	protected $intelligence;
	protected $intelligenceModifier;
	protected $wisdom;
	protected $wisdomModifier;
	protected $charisma;
	protected $charismaModifier;
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
		$this->armorClass = $playerRecord['armorClass'];
		$this->strength = $playerRecord['strength'];
		$this->dexterity = $playerRecord['dexterity'];
		$this->constitution = $playerRecord['constitution'];
		$this->intelligence = $playerRecord['intelligence'];
		$this->wisdom = $playerRecord['wisdom'];
		$this->charisma = $playerRecord['charisma'];
		$this->imageLocation = $playerRecord['imageLocation'];
		$this->statusFlag = $playerRecord['statusFlag'];
		$this->lastChange = $playerRecord['lastChange'];
		$this->creationDate = $playerRecord['creationDate'];
		
		// calculate modifiers
		$this->strengthModifier = $this->calculateModifier($this->strength);
		$this->dexterityModifier = $this->calculateModifier($this->dexterity);
		$this->constitutionModifier = $this->calculateModifier($this->constitution);
		$this->intelligenceModifier = $this->calculateModifier($this->intelligence);
		$this->wisdomModifier = $this->calculateModifier($this->wisdom);
		$this->charismaModifier = $this->calculateModifier($this->charisma);
		
		// check for hp override
		$battleCount = $this->database->getUniqueCount("dragons.battleHeader", "entryId", array("statusFlag"=>"A"));
		
		if (!isset($this->currentHP)) {
			$this->currentHP = $playerRecord['currentHP'];
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load player by battle detail id
	//------------------------------------------------------------------------
	public function loadPlayerByBattleDetailId($battleDetailId) {
		$battleRecord = $this->database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$battleDetailId));
		$this->battleDetailId = $battleDetailId;
		$this->initiative = $battleRecord['initiative'];
		$this->currentHP = $battleRecord['currentHP'];
		
		$this->loadPlayerById($battleRecord['associatedId']);
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
		
		if ($this->battleDetailId > 0) {
			$playerReference = $this->battleDetailId;
		} else {
			$playerReference = $this->playerId;
		}
		
		echo '
			<div class="' . $class . '">
				<img src="' . $this->imageLocation . '" width="80px" height="120px" /><br />
				<span class="adminPlayerName">' . $this->playerName . '</span><br />
				<em>' . $this->playerRace . ' / ' . $this->playerClass . '</em><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '<br />
				Str: ' . $this->strength . ' (' . $this->strengthModifier . ') | Dex: ' . $this->dexterity . ' (' . $this->dexterityModifier . ')<br />
				Con: ' . $this->constitution . 	' (' . $this->constitutionModifier . ') | Int: ' . $this->intelligence . ' (' . $this->intelligenceModifier . ')<br />
				Wis: ' . $this->wisdom . ' (' . $this->wisdomModifier . ') | Cha: ' . $this->charisma . ' (' . $this->charismaModifier . ')<br />
				<div class="blueButton" onClick="setInit(\'P\', ' . $playerReference . ');">Set Init</div><br /><div class="redButton" onClick="takeDamage(\'P\', ' . $playerReference . ');">Take Damage</div><br /><div class="greenButton" onClick="heal(\'P\', ' . $playerReference . ');">Heal</div>
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// calculate modifier
	//------------------------------------------------------------------------
	private function calculateModifier($value) {
		if ($value == 1) {
			return -5;
		} else if ($value == 2 || $value == 3) {
			return -4;
		} else if ($value == 4 || $value == 5) {
			return -3;
		} else if ($value == 6 || $value == 7) {
			return -2;
		} else if ($value == 8 || $value == 9) {
			return -1; 
		} else if ($value == 10 || $value == 11) {
			return 0;
		} else if ($value == 12 || $value == 13) {
			return 1;
		} else if ($value == 14 || $value == 15) {
			return 2;
		} else if ($value == 16 || $value == 17) {
			return 3;
		} else if ($value == 18 || $value == 19) {
			return 4;
		} else if ($value == 20 || $value == 21) {
			return 5;
		} else if ($value == 22 || $value == 23) {
			return 6;
		} else if ($value == 24 || $value == 25) {
			return 7;
		} else if ($value == 26 || $value == 27) {
			return 8;
		} else if ($value == 28 || $value == 29) {
			return 9;
		} else if ($value >= 30) {
			return 10;
		}
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------