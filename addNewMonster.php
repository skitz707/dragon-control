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
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();

var_dump($_POST);

// create monster record
$monsterData['campaignId'] = $_POST['campaignId'];
$monsterData['monsterName'] = $_POST['monsterName'];
$monsterData['health'] = $_POST['health'];
$monsterData['armorClass'] = $_POST['armorClass'];
$monsterData['strength'] = $_POST['strength'];
$monsterData['dexterity'] = $_POST['dexterity'];
$monsterData['constitution'] = $_POST['constitution'];
$monsterData['intelligence'] = $_POST['intelligence'];
$monsterData['wisdom'] = $_POST['wisdom'];
$monsterData['charisma'] = $_POST['charisma'];
$monsterData['xpRating'] = $_POST['xpRating'];

$database->insertDatabaseRecord("dragons.monsters", $monsterData);

// get monster id
$monsterId = $database->getColumnMax("dragons.monsters", "monsterId", array("campaignId"=>$_POST['campaignId']));

// create damage resistances
if (isset($_POST['damageResistances'])) {
	foreach ($_POST['damageResistances'] as $damageResistance) {
		$damageResistanceData['monsterId'] = $monsterId;
		$damageResistanceData['damageTypeId'] = $damageResistance;
		
		$database->insertDatabaseRecord("dragons.monsterResistances", $damageResistanceData);
	}
}

// create monster damage immunities
if (isset($_POST['damageImmunities'])) {
	foreach ($_POST['damageImmunities'] as $damageImmunity) {
		$damageImmunityData['monsterId'] = $monsterId;
		$damageImmunityData['damageTypeId'] = $damageImmunity;
		
		$database->insertDatabaseRecord("dragons.monsterDamageImmunities", $damageImmunityData);
	}
}

// create condition immunities
if (isset($_POST['conditionImmunities'])) {
	foreach ($_POST['conditionImmunities'] as $conditionImmunity) {
		$conditionImmunityData['monsterId'] = $monsterId;
		$conditionImmunityData['conditionId'] = $conditionImmunity;
		
		$database->insertDatabaseRecord("dragons.monsterConditionImmunities", $conditionImmunityData);
	}
}

// build array for sort
$i = 0;

foreach($_POST['monsterAttackMasterId'] as $monsterAttackMasterId) {
	$attackArray[$monsterAttackMasterId]['numberOfDice'][] = $_POST['numberOfDice'][$i];
	$attackArray[$monsterAttackMasterId]['rollId'][] = $_POST['rollIds'][$i];
	$attackArray[$monsterAttackMasterId]['itemActionType'][] = $_POST['itemActionTypes'][$i];
	$attackArray[$monsterAttackMasterId]['damageType'][] = $_POST['damageTypes'][$i];
	$attackArray[$monsterAttackMasterId]['diceRole'][] = $_POST['diceRoles'][$i];
	
	$i++;
}

foreach ($attackArray as $monsterAttackMasterId=>$attackValues) {
	$a = 0;
	
	// add monster attack record
	$monsterAttackData['monsterId'] = $monsterId;
	$monsterAttackData['monsterAttackMasterId'] = $monsterAttackMasterId;
	$monsterAttackData['specialText'] = "";
	
	$database->insertDatabaseRecord("dragons.monsterAttacks", $monsterAttackData);
	
	// get monster attack id
	$monsterAttackId = $database->getColumnMax("dragons.monsterAttacks", "monsterAttackId", array("monsterId"=>$monsterId));
	
	foreach ($attackValues['numberOfDice'] as $numberOfDice) {
		$monsterAttackDiceData['monsterAttackId'] = $monsterAttackId;
		$monsterAttackDiceData['numberOfDice'] = $numberOfDice;
		$monsterAttackDiceData['rollId'] = $attackValues['rollId'][$a];;
		$monsterAttackDiceData['damageTypeId'] = $attackValues['damageType'][$a];
		
		$database->insertDatabaseRecord("dragons.monsterAttackDice", $monsterAttackDiceData);
		
		$a++;
	}
}

// process spells
if (isset($_POST['spellIds'])) {
	foreach($_POST['spellIds'] as $spellId) {
		$spellData['monsterId'] = $monsterId;
		$spellData['spellId'] = $spellId;
		
		$database->insertDatabaseRecord("dragons.monsterSpells", $spellData);
	}
}

// process special skills
if (isset($_POST['specialSkillIds'])) {
	foreach ($_POST['specialSkillIds'] as $specialSkillId) {
		$skillData['monsterId'] = $monsterId;
		$skillData['specialSkillId'] = $specialSkillId;
		
		$database->insertDatabaseRecord("dragons.monsterSpecialSkills", $skillData);
	}
}

header("Location: manageMonsters.php?campaignId=" . $_POST['campaignId']);
//-------------------------------------------------------------------------------------------