<?php
//-------------------------------------------------------------------------------------------
// createSpell.php
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
require_once("classes/DCDatabase.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$pageTitle = "DC - Create Spell";
$campaignId = $_GET['campaignId'];
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Create Spell: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<form method="post" id="spellForm" action="addNewSpell.php">
	<table class="standardResultTable" style="width: 30%; margin-left: auto; margin-right: auto;">
		<tr>
			<td>Spell Name</td>
			<td><input type="text" class="textField" name="spellName" id="spellName" size="20" /></td>
		</tr>
		<tr>
			<td>Level</td>
			<td><input type="text" class="textField" name="level" id="level" size="3" /></td>
		</tr>
		<tr>
			<td>Magic School</td>
			<td><?php echo getMagicSchoolsDropdown($database); ?></td>
		</tr>
		<tr>
			<td>Casting Time</td>
			<td><?php echo getCastingTimesDropdown($database); ?></td>
		</tr>
		<tr>
			<td>Recharge</td>
			<td><input type="text" class="textField" name="recharge" id="recharge" size="5" /></td>
		</tr>
		<tr>
			<td>Spell Description</td>
			<td><textarea id="spellDescription" name="spellDescription" cols="40" rows="4"></textarea></td>
		</tr>
	</table>
	<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $campaignId; ?>" />
	</form>
	<div class="greenButton" onClick="document.getElementById('spellForm').submit();">Create Spell</div>
	
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get magic schools dropdown
//-------------------------------------------------------------------------------------------
function getMagicSchoolsDropdown($database) {
	$selectStmt = "select * from dragons.magicSchools order by magicSchool";
	$returnHTML = "";
	
	$returnHTML .= '<select name="magicSchoolId" id="magicSchoolId"><option>[magic school]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['magicSchoolId'] . '">' . $data['magicSchool'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get casting times dropdown
//-------------------------------------------------------------------------------------------
function getCastingTimesDropdown($database) {
	$selectStmt = "select * from dragons.castingTimes order by castingTime";
	$returnHTML = "";
	
	$returnHTML .= '<select name="castingTimeId" id="castingTimeId"><option>[casting time]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['castingTimeId'] . '">' . $data['castingTime'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------