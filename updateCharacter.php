<?php
//-------------------------------------------------------------------------------------------
// updateCharacter.php - Updates set inititative.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 31st, 2018
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

$character['characterName'] = $_POST['characterName'];
$character['maxHP'] = $_POST['health'];
$character['strength'] = $_POST['strength'];
$character['dexterity'] = $_POST['dexterity'];
$character['constitution'] = $_POST['constitution'];
$character['intelligence'] = $_POST['intelligence'];
$character['wisdom'] = $_POST['wisdom'];
$character['charisma'] = $_POST['charisma'];
$character['statusFlag'] = $_POST['statusFlag'];

$database->updateDatabaseRecord("dragons.characters", $character, array("characterId"=>$_POST['characterId']));

header("Location: manageCampaignCharacters.php?campaignId=" . $_POST['campaignId']);
//-------------------------------------------------------------------------------------------