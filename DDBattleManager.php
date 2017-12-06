<?php
//-------------------------------------------------------------------------------------------
// DDBattleManager.php - Tracks and admins the current active battle.
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
$player = new DDPlayer($database);
$enemy = new DDEnemy($database);
$selectStmt = "select playerId as characterId, 'P' as characterType, initiative from dragons.players where statusFlag = 'A' 
			   union 
			   select enemyId as characterId, 'E' as characterType, initiative from dragons.enemies where statusFlag = 'A'
					order by initiative desc";
					
$pageTitle = "DD Battle Manager";

require_once("includes/header.php");

if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
	if (!$selectHandle->execute()) {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
		if ($data['characterType'] == "P") {
			$player->loadPlayerById($data['characterId']);
			$player->printAdminPlayerCard();
		} else if ($data['characterType'] == "E") {
			$enemy->loadEnemyById($data['characterId']);
			$enemy->printAdminEnemyCard();
		}
	}
} else {
	var_dump($database->databaseConnection->errorInfo());
}

?>
<div id="popUpBox" title="Enter Values"></div>
<script>
//----------------------------------------------------------------------------
// set initiative
//----------------------------------------------------------------------------
function setInit(type, id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="setInitiative.php?type=' + type + '" id="initForm">';
	divHTML += 'Initiative: <input type="text" size="2" id="initiative" name="initiative" /> <div class="blueButton" onClick="document.getElementById(\'initForm\').submit();">Set</div>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 350,
			height: 150
		});
	});
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// take damage
//----------------------------------------------------------------------------
function takeDamage(type, id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="takeDamage.php?type=' + type + '" id="damageForm">';
	divHTML += 'Damage: <input type="text" size="2" id="damage" name="damage" /> <div class="redButton" onClick="document.getElementById(\'damageForm\').submit();">Damage</div>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 350,
			height: 150
		});
	});
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// heal
//----------------------------------------------------------------------------
function heal(type, id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="heal.php?type=' + type + '" id="healForm">';
	divHTML += 'Heal: <input type="text" size="2" id="heal" name="heal" /> <div class="greenButton" onClick="document.getElementById(\'damageForm\').submit();">Heal</div>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 350,
			height: 150
		});
	});
}
//----------------------------------------------------------------------------
</script>
<?php

require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------