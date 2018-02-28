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
require_once("classes/DCBattle.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$battle = new DCBattle($database);
$questHeader = $database->getDatabaseRecord("dragons.questHeader", array("campaignId"=>$_GET['campaignId'], "statusFlag"=>"A"));
$battleHeader = $database->getDatabaseRecord("dragons.battleHeader", array("questId"=>$questHeader['questId'], "statusFlag"=>"A"));
$battle->loadBattleById($battleHeader['battleId']);

$battle->endBattle();

header("Location: DCBattleManager.php?campaignId=" . $_GET['campaignId']);
//-------------------------------------------------------------------------------------------