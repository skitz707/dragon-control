<?php
//-------------------------------------------------------------------------------------------
// getCharacterDetail.php - Updates set inititative.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// February 1st, 2018
//
// Change log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// debug values
//-------------------------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', 1);
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// program includes
//-------------------------------------------------------------------------------------------
require_once("classes/DCDatabase.php");
require_once("classes/DCCharacter.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$character = new DCCharacter($database);
$character->loadCharacterById($_GET['characterId']);

$returnData['characterName'] = $character->getName();
$returnData['characterRace'] = $character->getRace();
$returnData['characterClass'] = $character->getClass();
$returnData['characterLevel'] = $character->getLevel();
$returnData['characterXP'] = $character->getXP();
$returnData['currentHP'] = $character->getCurrentHP();
$returnData['maxHP'] = $character->getMaxHP();
$returnData['armorClass'] = $character->getArmorClass();
$returnData['strength'] = $character->getStrength();
$returnData['strengthModifier'] = sprintf("%+d", $character->getStrengthModifier());
$returnData['dexterity'] = $character->getDexterity();
$returnData['dexterityModifier'] = sprintf("%+d", $character->getDexterityModifier());
$returnData['constitution'] = $character->getConstitution();
$returnData['constitutionModifier'] = sprintf("%+d", $character->getConstitutionModifier());
$returnData['intelligence'] = $character->getIntelligence();
$returnData['intelligenceModifier'] = sprintf("%+d", $character->getIntelligenceModifier());
$returnData['wisdom'] = $character->getWisdom();
$returnData['wisdomModifier'] = sprintf("%+d", $character->getWisdomModifier());
$returnData['charisma'] = $character->getCharisma();
$returnData['charismaModifier'] = sprintf("%+d", $character->getCharismaModifier());
$returnData['proficiencyBonus'] = $character->getProficiencyBonus();

/*
// get damage resistances
$damageResistances = $character->getDamageResistances();
$damageResistanceNames = array();

foreach($damageResistances as $damageResistance) {
	$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$damageResistance));
	$damageResistanceNames[] = ' ' . $damageType['damageType'];
}

$returnData['damageResistances'] = implode(",", $damageResistanceNames);


// get damage immunities
$damageImmunities = $monster->getDamageImmunities();
$damageImmunityNames = array();

foreach($damageImmunities as $damageImmunity) {
	$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$damageImmunity));
	$damageImmunityNames[] = ' ' . $damageType['damageType'];
}

$returnData['damageImmunities'] = implode(",", $damageImmunityNames);

// get condition immunities
$conditionImmunities = $monster->getConditionImmunities();
$conditionImmunityNames = array();

foreach($conditionImmunities as $conditionImmunity) {
	$conditionType = $database->getDatabaseRecord("dragons.conditions", array("conditionId"=>$conditionImmunity));
	$conditionImmunityNames[] = ' ' . $conditionType['conditionName'];
}

$returnData['conditionImmunities'] = implode(",", $conditionImmunityNames);

// get special skills
$specialSkills = $monster->getSpecialSkills();
$specialSkillText = "";

foreach($specialSkills as $specialSkillId) {
	$specialSkillsMaster = $database->getDatabaseRecord("dragons.specialSkills", array("specialSkillId"=>$specialSkillId));
	
	$specialSkillText .= '<span style="font-weight: bold;">' . $specialSkillsMaster['specialSkillName'] . '</span> <em>' . $specialSkillsMaster['specialSkillDescription'] . '</em><br /><br />';
}

$returnData['specialSkills'] = $specialSkillText;

// get attack information
$monsterAttacks = $monster->getMonsterAttacks();
$attackText = "";

foreach($monsterAttacks as $attackId) {
	$monsterAttack = $database->getDatabaseRecord("dragons.monsterAttacks", array("monsterAttackId"=>$attackId));
	$monsterAttackMaster = $database->getDatabaseRecord("dragons.monsterAttackMaster", array("monsterAttackId"=>$monsterAttack['monsterAttackMasterId']));
	$attackText .= '<span style="font-weight: bold;">' . $monsterAttackMaster['attackName'] . '(+' . $monsterAttack['rollModifier'] . '):</span> ';
	
	// get attack dice
	$diceStmt = "select * from dragons.monsterAttackDice where monsterAttackId = ?";
	
	if ($diceHandle = $database->databaseConnection->prepare($diceStmt)) {
		if (!$diceHandle->execute(array(0=>$attackId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($diceData = $diceHandle->fetch(PDO::FETCH_ASSOC)) {
			$dice = $database->getDatabaseRecord("dragons.diceRolls", array("rollId"=>$diceData['rollId']));
			$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$diceData['damageTypeId']));
			
			$attackText .= '(' . $diceData['numberOfDice'] . $dice['diceRoll'] . '+' . $diceData['damageModifier'] . ') ' . $damageType['damageType'] . ' ';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$attackText .= '<br />';
	
	if ($monsterAttack['specialText'] > "") {
		$attackText .= '<em>' . $monsterAttack['specialText'] . '</em><br />';
	}
}

$returnData['monsterAttacks'] = $attackText;


// build magic spell text
$magicSpells = $monster->getMagicSpells();
$magicText = "";

foreach ($magicSpells as $spellId) {
	$magicSpellMaster = $database->getDatabaseRecord("dragons.spells", array("spellId"=>$spellId));
	$magicText .= '<span style="font-weight: bold;">' . $magicSpellMaster['spellName'] . ':</span> <em>' . $magicSpellMaster['spellDescription'] . '</em><br />';
}

$returnData['magicSpells'] = $magicText;
*/

echo json_encode($returnData);
//-------------------------------------------------------------------------------------------