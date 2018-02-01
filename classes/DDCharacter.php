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
		$this->characterRace = $this->database->getDatabaseRecord("dragons.characterRaces", array("characterRaceId"=>$characterRecord['characterRace']))['characterRace'];
		$this->characterClass = $this->database->getDatabaseRecord("dragons.characterClasses", array("characterClassId"=>$characterRecord['characterClass']))['characterClass'];
		$this->characterLevel = $characterRecord['characterLevel'];
		$this->characterXP = $characterRecord['characterXP'];
		$this->maxHP = $characterRecord['maxHP'];
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
		
		// calculate armor class
		$this->armorClass = $this->calculateArmorClass();
		
		if (!$this->battleDetailId > 0) {
			$this->currentHP = $characterRecord['currentHP'];
		}
		
		// check for active quest
		$questMaster = $this->database->getDatabaseRecord("dragons.questHeader", array("campaignId"=>$this->campaignId, "statusFlag"=>"A"));
		
		if ($questMaster['questId'] > 0) {
			$this->questId = $questMaster['questId'];
		} else {
			$this->questId = 0;
		}
		
		// check for active battle id 
		$activeBattle = $this->database->getDatabaseRecord("dragons.battleHeader", array("questId"=>$this->questId, "statusFlag"=>"A"));
		$activeBattleDetail = $this->database->getDatabaseRecord("dragons.battleDetail", array("battleId"=>$activeBattle['battleId'], "associatedId"=>$this->characterId, "entryType"=>"C"));

		if ($activeBattleDetail['entryId'] > 0) {
			$this->battleDetailId = $activeBattleDetail['entryId'];
			$this->currentHP = $activeBattleDetail['currentHP'];
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
				<span class="characterName">' . $this->characterName . '</span><br />
				<em>' . $this->characterRace . ' / ' . $this->characterClass . '</em><br />
				AC: ' . $this->armorClass . '<br />
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
				<img src="' . $this->imageLocation . '" width="80px" height="120px" onClick="characterDetails(' . $this->characterId . ');" /><br />
				<span class="adminCharacterName">' . $this->characterName . '</span><br />
				<span style="font-size: 12pt; font-style: italic;">' . $this->characterRace . ' / ' . $this->characterClass . '</span><br />
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
	
	
	//------------------------------------------------------------------------
	// calculate armor class
	//------------------------------------------------------------------------
	private function calculateArmorClass() {
		// check for equipped armor
		$armorClass = 0;
		$bodyId = $this->database->getDatabaseRecord("dragons.equipableLocations", array("equipableLocation"=>"Body"));
		$armorEquipped = $this->database->getDatabaseRecord("dragons.characterEquippedItems", array("characterId"=>$this->characterId, "equipableLocationId"=>$bodyId['equipableLocationId']));
		
		// is there armor equipped?
		if ($armorEquipped['characterEquippedItemId'] > 0) {
			$itemMaster = $this->database->getDatabaseRecord("dragons.itemMaster", array("itemId"=>$armorEquipped['itemId']));
			$itemArmorClass = $this->database->getDatabaseRecord("dragons.itemArmorClass", array("itemId"=>$armorEquipped['itemId']));
			$armorClass = $itemArmorClass['armorClass'];
			
			// check armor type to determine if dex modifiers are added
			// is it light armor?
			if ($itemMaster['itemType'] == 8) {
				$armorClass += $this->dexterityModifier;
			// is it medium armor?
			} else if ($itemMaster['itemType'] == 16) {
				if ($this->dexterityModifier > 2) {
					$armorClass += 2;
				} else {
					$armorClass += $this->dexterityModifier;
				}
			// is it heavy armor?
			} else {
				// no dexterity bonuses
			}
		} else {
			$armorClass = 10 + $this->dexterityModifier;
		}
		
		// check for an equipped shield
		$shieldStmt = "select * from dragons.characterEquippedItems t1 inner join dragons.itemMaster t2 on t1.itemId = t2.itemId where characterId = ? and t2.itemType = 18";
		
		if ($shieldHandle = $this->database->databaseConnection->prepare($shieldStmt)) {
			if (!$shieldHandle->execute(array(0=>$this->characterId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			$equippedShield = $shieldHandle->fetcH(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		if ($equippedShield['characterEquippedItemId'] > 0) {
			$shieldArmorClass = $this->database->getDatabaseRecord("dragons.itemArmorClass", array("itemId"=>$equippedShield['itemId']));
			
			$armorClass += $shieldArmorClass['armorClass'];
		}
		
		return $armorClass;
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------