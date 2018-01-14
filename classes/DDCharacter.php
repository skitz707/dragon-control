<?php
//-------------------------------------------------------------------------------------------
// DDCharacter.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// DDCharacter() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// character class definition
//-------------------------------------------------------------------------------------------
class DDCharacter extends DDObject {
	// class properties
	protected $characterId;
	protected $battleDetailId;
	protected $characterName;
	protected $characterRace;
	protected $characterClass;
	protected $characterLevel;
	protected $characterXP;
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
	// load character by idd
	//------------------------------------------------------------------------
	public function loadCharacterById($characterId) {
		$characterRecord = $this->database->getDatabaseRecord("dragons.characters", array("characterId"=>$characterId));
		
		$this->characterId = $characterId;
		$this->characterName = $characterRecord['characterName'];
		$this->characterRace = $characterRecord['characterRace'];
		$this->characterClass = $characterRecord['characterClass'];
		$this->characterLevel = $characterRecord['characterLevel'];
		$this->characterXP = $characterRecord['characterXP'];
		$this->maxHP = $characterRecord['maxHP'];
		$this->armorClass = $characterRecord['armorClass'];
		$this->strength = $characterRecord['strength'];
		$this->dexterity = $characterRecord['dexterity'];
		$this->constitution = $characterRecord['constitution'];
		$this->intelligence = $characterRecord['intelligence'];
		$this->wisdom = $characterRecord['wisdom'];
		$this->charisma = $characterRecord['charisma'];
		$this->imageLocation = $characterRecord['imageLocation'];
		$this->statusFlag = $characterRecord['statusFlag'];
		$this->lastChange = $characterRecord['lastChange'];
		$this->creationDate = $characterRecord['creationDate'];
		
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
			$this->currentHP = $characterRecord['currentHP'];
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load character by battle detail id
	//------------------------------------------------------------------------
	public function loadCharacterByBattleDetailId($battleDetailId) {
		$battleRecord = $this->database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$battleDetailId));
		$this->battleDetailId = $battleDetailId;
		$this->initiative = $battleRecord['initiative'];
		$this->currentHP = $battleRecord['currentHP'];
		
		$this->loadCharacterById($battleRecord['associatedId']);
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// print character card
	//------------------------------------------------------------------------
	public function printCharacterCard() {
		// check if character is unconscious
		if ($this->currentHP == 0) {
			$class = "characterCard characterUnconscious";
		} else {
			$class = "characterCard";
		}
		
		echo '
			<div class="' . $class . '">
				<img src="' . $this->imageLocation . '" width="120px" height="160px" /><br />
				<span class="characterName">' . $this->playerName . '</span><br />
				<em>' . $this->characterRace . ' / ' . $this->characterClass . '</em><br />
				AC: ' . $this->characterClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	
	//------------------------------------------------------------------------
	// print admin character card
	//------------------------------------------------------------------------
	public function printAdminCharacterCard() {
		// check if character is unconscious
		if ($this->currentHP == 0) {
			$class = "adminCharacterCard characterUnconscious";
		} else {
			$class = "adminCharacterCard";
		}
		
		if ($this->battleDetailId > 0) {
			$characterReference = $this->battleDetailId;
		} else {
			$characterReference = $this->characterId;
		}
		
		echo '
			<div class="' . $class . '">
				<img src="' . $this->imageLocation . '" width="80px" height="120px" /><br />
				<span class="adminCharacterName">' . $this->characterName . '</span><br />
				<em>' . $this->characterRace . ' / ' . $this->characterClass . '</em><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '<br />
				Str: ' . $this->strength . ' (' . $this->strengthModifier . ') | Dex: ' . $this->dexterity . ' (' . $this->dexterityModifier . ')<br />
				Con: ' . $this->constitution . 	' (' . $this->constitutionModifier . ') | Int: ' . $this->intelligence . ' (' . $this->intelligenceModifier . ')<br />
				Wis: ' . $this->wisdom . ' (' . $this->wisdomModifier . ') | Cha: ' . $this->charisma . ' (' . $this->charismaModifier . ')<br />
				<div class="blueButton" onClick="setInit(\'P\', ' . $characterReference . ');">Set Init</div><br /><div class="redButton" onClick="takeDamage(\'P\', ' . $characterReference . ');">Take Damage</div><br /><div class="greenButton" onClick="heal(\'P\', ' . $characterReference . ');">Heal</div>
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