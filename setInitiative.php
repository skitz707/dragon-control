<?php
//-------------------------------------------------------------------------------------------
// setInitiative.php - Updates set inititative.
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
	$database->updateDatabaseRecord("dragons.enemies", array("initiative"=>$_POST['initiative']), array("enemyId"=>$_POST['id']));;
} else if ($_GET['type'] == "player") {
	$database->updateDatabaseRecord("dragons.players", array("initiative"=>$_POST['initiative']), array("playerId"=>$_POST['id']));
}

header("Location: DDBattleManager.php");
//-------------------------------------------------------------------------------------------