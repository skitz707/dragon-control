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
require_once("classes/DDPlayer.php");
require_once("classes/DDEnemy.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
if ($_GET['type'] == "enemy") {
	$enemyRecord = $database->getDatabaseRecord("dragons.enemies", array("enemyId"=>$_POST['id']));
	$newHP = $enemyRecord['currentHP'] + $_POST['heal'];
	
	if ($newHP > $enemyRecord['maxHP']) {
		$newHP = $enemyRecord['maxHP'];
	}
	
	$database->updateDatabaseRecord("dragons.enemies", array("currentHP"=>$newHP), array("enemyId"=>$_POST['id']));
} else if ($_GET['type'] == "player") {
	$playerRecord = $database->getDatabaseRecord("dragons.players", array("playerId"=>$_POST['id']));
	$newHP = $playerRecord['currentHP'] + $_POST['heal'];
	
	if ($newHP > $playerRecord['maxHP']) {
		$newHP = $playerRecord['maxHP'];
	}
	
	$database->updateDatabaseRecord("dragons.players", array("currentHP"=>$newHP), array("playerId"=>$_POST['id']));
}

header("Location: DDBattleManager.php");
//-------------------------------------------------------------------------------------------