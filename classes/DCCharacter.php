<?php
//-------------------------------------------------------------------------------------------
// DCCharacter.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// DCCharacter() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DCCreature.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// character class definition
//-------------------------------------------------------------------------------------------
class DCCharacter extends DCCreature {
	// class properties
	protected $ownerId;
	protected $characterProficiencies;
	
	//------------------------------------------------------------------------
	// load character by idd
	//------------------------------------------------------------------------
	public function loadCharacterById($characterId) {
		$characterRecord = $this->database->getDatabaseRecord("dragons.characters", array("characterId"=>$characterId));
		
		$this->id = $characterId;
		$this->ownerId = $characterRecord['ownerId'];
		$this->campaignId = $characterRecord['campaignId'];
		$this->name = $characterRecord['characterName'];
		$this->raceId = $characterRecord['characterRace'];
		$this->race = $this->database->getDatabaseRecord("dragons.characterRaces", array("characterRaceId"=>$characterRecord['characterRace']))['characterRace'];
		$this->classId = $characterRecord['characterClass'];
		$this->className = $this->database->getDatabaseRecord("dragons.characterClasses", array("characterClassId"=>$characterRecord['characterClass']))['characterClass'];
		$this->XP = $characterRecord['characterXP'];
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
		
		//----------------------------------------------------------------------------
		// check for additions to skill via feats or perks
		$bonusStmt = "select sum(statModifier) as totalModified from dragons.featDetails where featId in (select featId from dragons.characterFeats where characterId = ?) and statId = ?";
		$raceBonusStmt = "select sum(bonus) as totalBonus from dragons.raceBonuses where raceId = ? and statId = ?";
		
		if (!$bonusHandle = $this->database->databaseConnection->prepare($bonusStmt)) {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		if (!$raceBonusHandle = $this->database->databaseConnection->prepare($raceBonusStmt)) {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		//----------------------------------------------------------------------------
		// strength additions
		$statId = $this->database->getDatabaseRecord("dragons.creatureStats", array("statAbbrv"=>"STR"));
		
		// race bonuses
		if ($raceBonusHandle->execute(array(0=>$this->raceId, 1=>$statId['statId']))) {
			$raceBonus = $raceBonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->strength += $raceBonus['totalBonus'];
		
		// class bonuses
		
		// feat bonuses
		if ($bonusHandle->execute(array(0=>$this->id, 1=>$statId['statId']))) {
			$bonus = $bonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->strength += $bonus['totalModified'];
		//----------------------------------------------------------------------------
		
		
		//----------------------------------------------------------------------------
		// dexterity additions
		$statId = $this->database->getDatabaseRecord("dragons.creatureStats", array("statAbbrv"=>"DEX"));
		
		// race bonuses
		if ($raceBonusHandle->execute(array(0=>$this->raceId, 1=>$statId['statId']))) {
			$raceBonus = $raceBonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->dexterity += $raceBonus['totalBonus'];
		
		// class bonuses
		
		// feat bonuses
		if ($bonusHandle->execute(array(0=>$this->id, 1=>$statId['statId']))) {
			$bonus = $bonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->dexterity += $bonus['totalModified'];
		//----------------------------------------------------------------------------
		
		
		//----------------------------------------------------------------------------
		// constitution additions by class
		$statId = $this->database->getDatabaseRecord("dragons.creatureStats", array("statAbbrv"=>"CON"));
		
		// race bonuses
		if ($raceBonusHandle->execute(array(0=>$this->raceId, 1=>$statId['statId']))) {
			$raceBonus = $raceBonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->constitution += $raceBonus['totalBonus'];
		
		// class bonuses
		
		// feat bonuses
		if ($bonusHandle->execute(array(0=>$this->id, 1=>$statId['statId']))) {
			$bonus = $bonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->constitution += $bonus['totalModified'];
		//----------------------------------------------------------------------------
		
		
		//----------------------------------------------------------------------------
		// intelligence additions
		$statId = $this->database->getDatabaseRecord("dragons.creatureStats", array("statAbbrv"=>"INT"));
		
		// race bonuses
		if ($raceBonusHandle->execute(array(0=>$this->raceId, 1=>$statId['statId']))) {
			$raceBonus = $raceBonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->intelligence += $raceBonus['totalBonus'];
		
		// class bonuses
		
		// feat bonuses
		if ($bonusHandle->execute(array(0=>$this->id, 1=>$statId['statId']))) {
			$bonus = $bonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->intelligence += $bonus['totalModified'];
		//----------------------------------------------------------------------------
		
		
		//----------------------------------------------------------------------------
		// wisdom additions
		$statId = $this->database->getDatabaseRecord("dragons.creatureStats", array("statAbbrv"=>"WIS"));
		
		// race bonuses
		if ($raceBonusHandle->execute(array(0=>$this->raceId, 1=>$statId['statId']))) {
			$raceBonus = $raceBonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->wisdom += $raceBonus['totalBonus'];
		
		// class bonuses
		
		// feat bonuses
		if ($bonusHandle->execute(array(0=>$this->id, 1=>$statId['statId']))) {
			$bonus = $bonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->wisdom += $bonus['totalModified'];
		//----------------------------------------------------------------------------
		
		
		//----------------------------------------------------------------------------
		// charisma additions
		$statId = $this->database->getDatabaseRecord("dragons.creatureStats", array("statAbbrv"=>"CHA"));
		
		// race bonuses
		if ($raceBonusHandle->execute(array(0=>$this->raceId, 1=>$statId['statId']))) {
			$raceBonus = $raceBonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->charisma += $raceBonus['totalBonus'];
		
		// class bonuses
		
		// feat bonuses
		if ($bonusHandle->execute(array(0=>$this->id, 1=>$statId['statId']))) {
			$bonus = $bonusHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		$this->charisma += $bonus['totalModified'];
		//----------------------------------------------------------------------------
		
		
		// calculate modifiers
		$this->strengthModifier = $this->calculateModifier($this->strength);
		$this->dexterityModifier = $this->calculateModifier($this->dexterity);
		$this->constitutionModifier = $this->calculateModifier($this->constitution);
		$this->intelligenceModifier = $this->calculateModifier($this->intelligence);
		$this->wisdomModifier = $this->calculateModifier($this->wisdom);
		$this->charismaModifier = $this->calculateModifier($this->charisma);
		
		// calculate armor class
		$this->armorClass = $this->calculateArmorClass();
		
		// get character level
		$this->level = $this->calculateCharacterLevel();
		
		// load character proficiencies
		$this->characterProficiencies = $this->setCharacterProficiencies();
		
		// set proficiency bonus
		$profBonus = $this->database->getDatabaseRecord("dragons.classProficiencyBonus", array("characterClassId"=>$this->classId, "characterLevel"=>$this->level));
		$this->proficiencyBonus = $profBonus['proficiencyBonus'];
		
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
		$activeBattleDetail = $this->database->getDatabaseRecord("dragons.battleDetail", array("battleId"=>$activeBattle['battleId'], "associatedId"=>$this->id, "entryType"=>"C"));

		if ($activeBattleDetail['entryId'] > 0) {
			$this->battleDetailId = $activeBattleDetail['entryId'];
			$this->currentHP = $activeBattleDetail['currentHP'];
			
			// load initiative
			$this->initiative = $activeBattleDetail['initiative'];
		}
		
		// load character items
		$this->loadCharacterItems();
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
				<span class="characterName">' . $this->name . '</span><br />
				<em>' . $this->race . ' / ' . $this->className . '</em><br />
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
				<img src="' . $this->imageLocation . '" width="80px" height="120px" onClick="characterDetails(' . $this->id . ');" /><br />
				<span class="adminCharacterName">' . $this->name . '</span><br />
				<span style="font-size: 12pt; font-style: italic;">' . $this->race . ' / ' . $this->className . '</span><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '<br />
				Str: ' . $this->strength . ' (' . sprintf("%+d", $this->strengthModifier) . ') | Dex: ' . $this->dexterity . ' (' . sprintf("%+d", $this->dexterityModifier) . ')<br />
				Con: ' . $this->constitution . 	' (' . sprintf("%+d", $this->constitutionModifier) . ') | Int: ' . $this->intelligence . ' (' . sprintf("%+d", $this->intelligenceModifier) . ')<br />
				Wis: ' . $this->wisdom . ' (' . sprintf("%+d", $this->wisdomModifier) . ') | Cha: ' . $this->charisma . ' (' . sprintf("%+d", $this->charismaModifier) . ')<br />
				<div class="blueButton" onClick="setInit(\'C\', ' . $this->battleDetailId . ');">Set Init</div><br /><div class="redButton" onClick="takeDamage(\'C\', ' . $this->id . ');">Take Damage</div><br /><div class="greenButton" onClick="heal(\'C\', ' . $this->id . ');">Heal</div>
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
		$armorEquipped = $this->database->getDatabaseRecord("dragons.characterEquippedItems", array("characterId"=>$this->id, "equipableLocationId"=>$bodyId['equipableLocationId']));
		
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
			if (!$shieldHandle->execute(array(0=>$this->id))) {
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
		
		// check for feat bonuses
		
		
		// check for race bonuses
		
		
		// check for class bonuses
		$armorClass += $this->getClassArmorBonus();
		
		return $armorClass;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get class armor bonus
	//------------------------------------------------------------------------
	protected function getClassArmorBonus() {
		$bonusAC = 0;
		
		// fighter class
		if ($this->className == "Fighter") {
			// bonus +1 to AC when wearing armor
			$bodyId = $this->database->getDatabaseRecord("dragons.equipableLocations", array("equipableLocation"=>"Body"));
			$armorEquipped = $this->database->getDatabaseRecord("dragons.characterEquippedItems", array("characterId"=>$this->id, "equipableLocationId"=>$bodyId['equipableLocationId']));
			
			if ($armorEquipped['characterEquippedItemId'] > 0) {
				$bonusAC += 1;
			}
		}
		
		return $bonusAC;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// calculate character level
	//------------------------------------------------------------------------
	private function calculateCharacterLevel() {
		$levelStmt = "select max(characterLevel) as level from dragons.xpLevels where xpAmount <= ?";
		
		if ($levelHandle = $this->database->databaseConnection->prepare($levelStmt)) {
			if (!$levelHandle->execute(array(0=>$this->XP))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			$level = $levelHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		return $level['level'];
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// set character proficiencies
	//------------------------------------------------------------------------
	private function setCharacterProficiencies() {
		$returnArray = array();
		$selectStmt = "select * from dragons.characterProficiencies where characterId = ?";
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->id))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
				$returnArray[] = $data['proficiencyId'];
			}
		} else {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		return $returnArray;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load character items
	//------------------------------------------------------------------------
	private function loadCharacterItems() {
		$selectStmt = "select t1.characterItemId from dragons.characterItems t1 inner join dragons.itemMaster t2 
						on t1.itemId = t2.itemId where t1.characterId = ? order by t2.itemName";
		$this->itemArray = array();
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->id))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->itemArray[] = $data['characterItemId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get character proficiencies
	//------------------------------------------------------------------------
	public function getCharacterProficiencies() {
		return $this->characterProficiencies;
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------