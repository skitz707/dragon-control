<?php
//-------------------------------------------------------------------------------------------
// takeDamage.php - Take damage/update HP.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// December 1st, 2017
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
require_once("classes/DCMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$character = new DCCharacter($database);
$monster = new DCMonster($database);

if ($_GET['type'] == "C") {
	$character->loadCharacterById($_POST['id']);
	$character->takeDamage($_POST['damage']);
} else if ($_GET['type'] == "M") {
	$monster->loadMonsterByBattleDetailId($_POST['id']);
	$monster->takeDamage($_POST['damage']);
}

header("Location: DCBattleManager.php?campaignId=" . $_POST['campaignId']);
//-------------------------------------------------------------------------------------------