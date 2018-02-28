<?php
//-------------------------------------------------------------------------------------------
// DCMonster.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// DDEnemy() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DCCreature.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// database class definition
//-------------------------------------------------------------------------------------------
class DCMonster extends DCCreature {
	// class properties
	protected $xpRating;
	protected $monsterAttacks;
	
	//------------------------------------------------------------------------
	// load player by playerId
	//------------------------------------------------------------------------
	public function loadMonsterById($monsterId) {
		$monsterRecord = $this->database->getDatabaseRecord("dragons.monsters", array("monsterId"=>$monsterId));
		
		$this->id = $monsterId;
		$this->name = $monsterRecord['monsterName'];
		$this->maxHP = $monsterRecord['health'];
		$this->currentHP = $monsterRecord['health'];
		$this->armorClass = $monsterRecord['armorClass'];
		$this->strength = $monsterRecord['strength'];
		$this->dexterity = $monsterRecord['dexterity'];
		$this->constitution = $monsterRecord['constitution'];
		$this->intelligence = $monsterRecord['intelligence'];
		$this->wisdom = $monsterRecord['wisdom'];
		$this->charisma = $monsterRecord['charisma'];
		$this->xpRating = $monsterRecord['xpRating'];
		$this->imageLocation = $monsterRecord['imageLocation'];
		$this->lastChange = $monsterRecord['lastChange'];
		$this->creationDate = $monsterRecord['creationDate'];
		
		// calculate modifiers
		$this->strengthModifier = $this->calculateModifier($this->strength);
		$this->dexterityModifier = $this->calculateModifier($this->dexterity);
		$this->constitutionModifier = $this->calculateModifier($this->constitution);
		$this->intelligenceModifier = $this->calculateModifier($this->intelligence);
		$this->wisdomModifier = $this->calculateModifier($this->wisdom);
		$this->charismaModifier = $this->calculateModifier($this->charisma);
		$this->proficiencyBonus = $this->calculateProficiencyBonus();
		
		// check for battle detail id to override hp
		if ($this->battleDetailId > 0) {
			$battleDetail = $this->database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$this->battleDetailId));
			$this->currentHP = $battleDetail['currentHP'];
		}
		
		// load damage resistacnes
		$this->damageResistances = array();
		$damageResistancesStmt = "select * from dragons.monsterResistances where monsterId = ?";
		
		if ($damageResistanceHandle = $this->database->databaseConnection->prepare($damageResistancesStmt)) {
			if (!$damageResistanceHandle->execute(array(0=>$this->monsterId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($damageResistanceData = $damageResistanceHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->damageResistances[] = $damageResistanceData['damageTypeId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		// load damage immunities
		$this->damageImmunities = array();
		$damageImmunitiesStmt = "select * from dragons.monsterDamageImmunities where monsterId = ?";
		
		if ($damageImmunitiesHandle = $this->database->databaseConnection->prepare($damageImmunitiesStmt)) {
			if (!$damageImmunitiesHandle->execute(array(0=>$this->monsterId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($damageImmunityData = $damageImmunitiesHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->damageImmunities[] = $damageImmunityData['damageTypeId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		// load condition immunities
		$this->conditionImmunities = array();
		$conditionImmunitiesStmt = "select * from dragons.monsterConditionImmunities where monsterId = ?";
		
		if ($conditionImmunitiesHandle = $this->database->databaseConnection->prepare($conditionImmunitiesStmt)) {
			if (!$conditionImmunitiesHandle->execute(array(0=>$this->monsterId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($conditionImmunityData = $conditionImmunitiesHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->conditionImmunities[] = $conditionImmunityData['conditionId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		// load attacks
		$this->monsterAttacks = array();
		
		$monsterAttackStmt = "select * from dragons.monsterAttacks where monsterId = ?";
		
		if ($monsterAttackHandle = $this->database->databaseConnection->prepare($monsterAttackStmt)) {
			if (!$monsterAttackHandle->execute(array($this->monsterId))) {
				var_dump($database->databaseConnection->errorInfo());
			}
			
			while ($monsterAttackData = $monsterAttackHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->monsterAttacks[] = $monsterAttackData['monsterAttackId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		// load magic spells
		$this->magicSpells = array();
		
		$monsterSpellsStmt = "select * from dragons.monsterSpells where monsterId = ?";
		
		if ($monsterSpellsHandle = $this->database->databaseConnection->prepare($monsterSpellsStmt)) {
			if (!$monsterSpellsHandle->execute(array(0=>$this->monsterId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($spellData = $monsterSpellsHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->magicSpells[] = $spellData['spellId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
		
		// load special skills
		$this->specialSkills = array();
		$specialSkillsStmt = "select specialSkillId from dragons.monsterSpecialSkills where monsterId = ?";
		
		if ($specialSkillsHandle = $this->database->databaseConnection->prepare($specialSkillsStmt)) {
			if (!$specialSkillsHandle->execute(array(0=>$this->monsterId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($specialSkillData = $specialSkillsHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->specialSkills[] = $specialSkillData['specialSkillId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load monster by battle id
	//------------------------------------------------------------------------
	public function loadMonsterByBattleDetailId($battleDetailId) {
		$battleRecord = $this->database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$battleDetailId));
		$this->battleDetailId = $battleDetailId;
		$this->initiative = $battleRecord['initiative'];
		
		$this->loadMonsterById($battleRecord['associatedId']);
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// print monster card
	//------------------------------------------------------------------------
	public function printMonsterCard() {
		echo '
			<div class="monsterCard">
				<img src="' . $this->imageLocation . '" width="120px" height="160px" /><br />
				<span class="monsterName">' . $this->characterName . '</span><br />
				AC: ' . $this->armorClass . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// print admin monster card
	//------------------------------------------------------------------------
	public function printAdminMonsterCard() {
		echo '
			<div class="adminMonsterCard">
				<img src="' . $this->imageLocation . '" width="80px" height="120px" onClick="monsterDetails(\'' . $this->monsterId . '\');" /><br />
				<span class="adminMonsterName">' . $this->characterName . '</span><br />
				<em>Enemy</em><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '<br />
				Str: ' . $this->strength . ' (' . sprintf("%+d", $this->strengthModifier) . ') | Dex: ' . $this->dexterity . ' (' . sprintf("%+d", $this->dexterityModifier) . ')<br />
				Con: ' . $this->constitution . 	' (' . sprintf("%+d", $this->constitutionModifier) . ') | Int: ' . $this->intelligence . ' (' . sprintf("%+d", $this->intelligenceModifier) . ')<br />
				Wis: ' . $this->wisdom . ' (' . sprintf("%+d", $this->wisdomModifier) . ') | Cha: ' . $this->charisma . ' (' .sprintf("%+d",  $this->charismaModifier) . ')<br />
				<div class="blueButton" onClick="setInit(\'M\', ' . $this->battleDetailId . ');">Set Init</div><br /><div class="redButton" onClick="takeDamage(\'M\', ' . $this->battleDetailId . ');">Take Damage</div><br /><div class="greenButton" onClick="heal(\'M\', ' . $this->battleDetailId . ');">Heal</div>
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get roll modifier by attack id
	//------------------------------------------------------------------------
	public function getRollModifier($attackId) {
		$monsterAttackMaster = $this->database->getDatabaseRecord("dragons.monsterAttackMaster", array("monsterAttackId"=>$attackId));
		
		// if strength bonus for modifier
		if ($monsterAttackMaster['proficiencyStatId'] == 1) {
			return $this->proficiencyBonus + $this->strengthModifier;
		// if dexterity bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 2) {
			return $this->proficiencyBonus + $this->dexterityModifier;
		// if constitution bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 3) {
			return $this->proficiencyBonus + $this->constitutionModifier;
		// if intelligence bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 4) {
			return $this->proficiencyBonus + $this->intelligenceModifier;
		// if wisdom bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 5) {
			return $this->proficiencyBonus + $this->wisdomModifier;
		// if charisma bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 6) {
			return $this->proficiencyBonus + $this->charismaModifier;
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get damage modifier by attack id
	//------------------------------------------------------------------------
	public function getDamageModifier($attackId) {
		$monsterAttackMaster = $this->database->getDatabaseRecord("dragons.monsterAttackMaster", array("monsterAttackId"=>$attackId));
		
		// if strength bonus for modifier
		if ($monsterAttackMaster['proficiencyStatId'] == 1) {
			return $this->strengthModifier;
		// if dexterity bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 2) {
			return $this->dexterityModifier;
		// if constitution bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 3) {
			return $this->constitutionModifier;
		// if intelligence bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 4) {
			return $this->intelligenceModifier;
		// if wisdom bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 5) {
			return $this->wisdomModifier;
		// if charisma bonus for modifier
		} else if ($monsterAttackMaster['proficiencyStatId'] == 6) {
			return $this->charismaModifier;
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// calculate proficiency bonus
	//------------------------------------------------------------------------
	protected function calculateProficiencyBonus() {
		$selectStmt = "select max(proficiencyBonus) as profBonus from dragons.monsterProficiencyBonus where xpThreshold <= ?";
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->xpRating))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			$data = $selectHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
			
		return $data['profBonus'];
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// update hp
	//------------------------------------------------------------------------
	protected function updateHP() {
		$this->database->updateDatabaseRecord("dragons.battleDetail", array("currentHP"=>$this->currentHP), array("entryId"=>$this->battleDetailId));
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get xp rating
	//------------------------------------------------------------------------
	public function getXPRating() {
		return $this->xpRating;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get monster attacks
	//------------------------------------------------------------------------
	public function getMonsterAttacks() {
		return $this->monsterAttacks;
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------