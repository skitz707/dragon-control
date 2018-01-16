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
include_once("classes/DDCreature.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// character class definition
//-------------------------------------------------------------------------------------------
class DDCharacter extends DDCreature {
	// class properties
	protected $ownerId;
	
	
	//------------------------------------------------------------------------
	// load character by idd
	//------------------------------------------------------------------------
	public function loadCharacterById($characterId) {
		$characterRecord = $this->database->getDatabaseRecord("dragons.characters", array("characterId"=>$characterId));
		
		$this->characterId = $characterId;
		$this->ownerId = $characterRecord['ownerId'];
		$this->campaignId = $characterRecord['campaignId'];
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
		
		// check for active quest
		$questMaster = $this->database->getDatabaseRecord("dragons.questHeader", array("campaignId"=>$this->campaignId, "statusFlag"=>"A"));
		
		if ($questMaster['questId'] > 0) {
			$this->questId = $questMaster['questId'];
		} else {
			$this->questId = 0;
		}
		
		if (!$this->battleDetailId > 0) {
			$this->currentHP = $characterRecord['currentHP'];
		}
		
		// check for active battle id 
		$activeBattle = $this->database->getDatabaseRecord("dragons.battleHeader", array("questId"=>$this->questId, "statusFlag"=>"A"));
		$activeBattleDetail = $this->database->getDatabaseRecord("dragons.battleDetail", array("battleId"=>$activeBattle['battleId'], "associatedId"=>$this->characterId, "entryType"=>"C"));

		if ($activeBattleDetail['entryId'] > 0) {
			$this->battleDetailId = $activeBattleDetail['entryId'];
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
				<div class="blueButton" onClick="setInit(\'C\', ' . $this->battleDetailId . ');">Set Init</div><br /><div class="redButton" onClick="takeDamage(\'C\', ' . $this->characterId . ');">Take Damage</div><br /><div class="greenButton" onClick="heal(\'C\', ' . $this->characterId . ');">Heal</div>
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// update hp
	//------------------------------------------------------------------------
	protected function updateHP() {
		if ($this->battleDetailId > 0) {
			$this->database->updateDatabaseRecord("dragons.battleDetail", array("currentHP"=>$this->currentHP), array("entryId"=>$this->battleDetailId));
		} else {
			$this->database->updateDatabaseRecord("dragons.characters", array("currentHP"=>$this->currentHP), array("characterId"=>$this->characterId));
		}
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------