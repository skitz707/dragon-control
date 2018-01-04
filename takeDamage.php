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
require_once("classes/DDDatabase.php");
require_once("classes/DDPlayer.php");
require_once("classes/DDEnemy.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$currentRecord = $database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$_POST['id']));

$newHP = $currentRecord['currentHP'] - $_POST['damage'];

if ($newHP < 0) {
	$newHP = 0;
}

$updateData['currentHP'] = $newHP;
	
$database->updateDatabaseRecord("dragons.battleDetail", $updateData, array("entryId"=>$_POST['id']));

header("Location: DDBattleManager.php");
//-------------------------------------------------------------------------------------------