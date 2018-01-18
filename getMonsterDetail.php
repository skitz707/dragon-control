<?php
//-------------------------------------------------------------------------------------------
// newBattle.php - Updates set inititative.
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
require_once("classes/DDDatabase.php");
require_once("classes/DDMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$monster = new DDMonster($database);
$monster->loadMonsterById($_GET['monsterId']);

$returnData['monsterName'] = $monster->getCharacterName();
$returnData['health'] = $monster->getMaxHP();
$returnData['armorClass'] = $monster->getArmorClass();
$returnData['strength'] = $monster->getStrength();
$returnData['strengthModifier'] = $monster->getStrengthModifier();
$returnData['dexterity'] = $monster->getDexterity();
$returnData['dexterityModifier'] = $monster->getDexterityModifier();
$returnData['constitution'] = $monster->getConstitution();
$returnData['constitutionModifier'] = $monster->getConstitutionModifier();
$returnData['intelligence'] = $monster->getIntelligence();
$returnData['intelligenceModifier'] = $monster->getIntelligenceModifier();
$returnData['wisdom'] = $monster->getWisdom();
$returnData['wisdomModifier'] = $monster->getWisdomModifier();
$returnData['charisma'] = $monster->getCharisma();
$returnData['charismaModifier'] = $monster->getCharismaModifier();
$returnData['xpRating'] = $monster->getXPRating();

// get damage resistances
$damageResistances = $monster->getDamageResistances();

foreach($damageResistances as $damageResistance) {
	$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$damageResistance));
	$damageResistanceNames[] = ' ' . $damageType['damageType'];
}

$returnData['damageResistances'] = implode(",", $damageResistanceNames);


// get damage immunities
$damageImmunities = $monster->getDamageImmunities();

foreach($damageImmunities as $damageImmunity) {
	$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$damageImmunity));
	$damageImmunityNames[] = ' ' . $damageType['damageType'];
}

$returnData['damageImmunities'] = implode(",", $damageImmunityNames);

// get condition immunities
$conditionImmunities = $monster->getConditionImmunities();

foreach($conditionImmunities as $conditionImmunity) {
	$conditionType = $database->getDatabaseRecord("dragons.conditions", array("conditionId"=>$conditionImmunity));
	$conditionImmunityNames[] = ' ' . $conditionType['conditionName'];
}

$returnData['conditionImmunities'] = implode(",", $conditionImmunityNames);

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
			
			$attackText .= '(' . $dice['diceRoll'] . '+' . $diceData['damageModifier'] . ') ' . $damageType['damageType'] . ' ';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
}

$returnData['monsterAttacks'] = $attackText;

echo json_encode($returnData);
//-------------------------------------------------------------------------------------------