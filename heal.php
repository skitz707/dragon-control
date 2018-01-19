<?php
//-------------------------------------------------------------------------------------------
// heal.php - Take heal/update HP.
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
require_once("classes/DDDatabase.php");
require_once("classes/DDCharacter.php");
require_once("classes/DDMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$character = new DDCharacter($database);
$monster = new DDMonster($database);

if ($_GET['type'] == "C") {
	$character->loadCharacterById($_POST['id']);
	$character->heal($_POST['heal']);
} else if ($_GET['type'] == "M") {
	$monster->loadMonsterByBattleDetailId($_POST['id']);
	$monster->heal($_POST['heal']);
}

header("Location: DCBattleManager.php?campaignId=" . $_POST['campaignId']);
//-------------------------------------------------------------------------------------------