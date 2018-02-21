<?php
//-------------------------------------------------------------------------------------------
// addNewSpell.php
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// February 21st, 2018
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

$spellData['campaignId'] = $_POST['campaignId'];
$spellData['spellName'] = $_POST['spellName'];
$spellData['spellLevel'] = $_POST['level'];
$spellData['spellSchoolId'] = $_POST['magicSchoolId'];
$spellData['castingTimeId'] = $_POST['castingTimeId'];
$spellData['spellDescription'] = $_POST['spellDescription'];

$database->insertDatabaseRecord("dragons.spells", $spellData);

header("Location: manageSpells.php?campaignId=" . $_POST['campaignId']);
//-------------------------------------------------------------------------------------------
