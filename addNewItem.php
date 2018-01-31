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

$itemMaster['campaignId'] = $_POST['campaignId'];
$itemMaster['itemName'] = $_POST['itemName'];
$itemMaster['itemDescription'] = $_POST['itemDescription'];
$itemMaster['itemType'] = $_POST['itemType'];
$itemMaster['cost'] = $_POST['cost'];
$itemMaster['itemWeight'] = $_POST['weight'];

$database->insertDatabaseRecord("dragons.itemMaster", $itemMaster);

// get item id
$itemId = $database->getColumnMax("dragons.itemMaster", "itemId", array("campaignId"=>$_POST['campaignId']));

// build item properties entries
foreach ($_POST['weaponProperties'] as $weaponPropertyId) {
	$itemProperty['itemId'] = $itemId;
	$itemProperty['weaponPropertyId'] = $weaponPropertyId;
	
	$database->insertDatabaseRecord("dragons.itemProperties", $itemProperty);
}

// build item dice entries
$i = 0;

foreach ($_POST['numberOfDice'] as $numberOfDice) {
	$itemDice['itemId'] = $itemId;
	$itemDice['numberOfDice'] = $numberOfDice;
	$itemDice['rollId'] = $_POST['rollIds'][$i];
	$itemDice['rollModifier'] = $_POST['rollModifiers'][$i];
	$itemDice['itemActionTypeId'] = $_POST['itemActionTypes'][$i];
	$itemDice['damageTypeId'] = $_POST['damageTypes'][$i];
	$itemDice['diceRoleId'] = $_POST['diceRoles'][$i];
	
	$database->insertDatabaseRecord("dragons.itemDice", $itemDice);
	
	$i++;
}

//header("Location: manageMonsters.php?campaignId=" . $questMaster['campaignId']);
//-------------------------------------------------------------------------------------------