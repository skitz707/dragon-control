<?php
//-------------------------------------------------------------------------------------------
// getMonsterDetail.php - Updates set inititative.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// December 21st, 2017
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
require_once("classes/DCMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$monster = new DCMonster($database);
$monster->loadMonsterById($_GET['monsterId']);

$returnData['monsterName'] = $monster->getName();
$returnData['health'] = $monster->getMaxHP();
$returnData['armorClass'] = $monster->getArmorClass();
$returnData['strength'] = $monster->getStrength();
$returnData['strengthModifier'] = sprintf("%+d", $monster->getStrengthModifier());
$returnData['dexterity'] = $monster->getDexterity();
$returnData['dexterityModifier'] = sprintf("%+d", $monster->getDexterityModifier());
$returnData['constitution'] = $monster->getConstitution();
$returnData['constitutionModifier'] = sprintf("%+d", $monster->getConstitutionModifier());
$returnData['intelligence'] = $monster->getIntelligence();
$returnData['intelligenceModifier'] = sprintf("%+d", $monster->getIntelligenceModifier());
$returnData['wisdom'] = $monster->getWisdom();
$returnData['wisdomModifier'] = sprintf("%+d", $monster->getWisdomModifier());
$returnData['charisma'] = $monster->getCharisma();
$returnData['charismaModifier'] = sprintf("%+d", $monster->getCharismaModifier());
$returnData['xpRating'] = $monster->getXPRating();

// get damage resistances
$damageResistances = $monster->getDamageResistances();
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
	$attackText .= '<span style="font-weight: bold;">' . $monsterAttackMaster['attackName'] . '(+' . $monster->getRollModifier($monsterAttackMaster['monsterAttackId']) . '):</span> ';
	
	// get attack dice
	$diceStmt = "select * from dragons.monsterAttackDice where monsterAttackId = ?";
	
	if ($diceHandle = $database->databaseConnection->prepare($diceStmt)) {
		if (!$diceHandle->execute(array(0=>$attackId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($diceData = $diceHandle->fetch(PDO::FETCH_ASSOC)) {
			$dice = $database->getDatabaseRecord("dragons.diceRolls", array("rollId"=>$diceData['rollId']));
			$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$diceData['damageTypeId']));
			
			$attackText .= '(' . $diceData['numberOfDice'] . $dice['diceRoll'] . '+' . $monster->getDamageModifier($monsterAttackMaster['monsterAttackId']) . ') ' . $damageType['damageType'] . ' ';
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
	
	// check for recharge
	if ($magicSpellMaster['recharge'] > "") {
		$recharge = '(Recharge ' . $magicSpellMaster['recharge'] . ')';
	} else {
		$recharge = "";
	}
	
	$magicText .= '<span style="font-weight: bold;">' . $magicSpellMaster['spellName'] . $recharge . ':</span> <em>' . $magicSpellMaster['spellDescription'] . '</em><br />';
}

$returnData['magicSpells'] = $magicText;

echo json_encode($returnData);
//-------------------------------------------------------------------------------------------