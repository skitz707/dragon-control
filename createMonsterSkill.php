<?php
//-------------------------------------------------------------------------------------------
// createMonsterSkill.php
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
$pageTitle = "DC - Manage Monster Skills";
$campaignId = $_GET['campaignId'];
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Create Monster Skill: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<form method="post" id="monsterSkillForm" action="addNewMonsterSkill.php">
	<table class="standardResultTable" style="width: 30%; margin-left: auto; margin-right: auto;">
		<tr>
			<td>Skill Name</td>
			<td><input type="text" class="textField" name="skillName" id="skillName" size="20" /></td>
		</tr>
		<tr>
			<td>Skill Description</td>
			<td><textarea id="skillDescription" name="skillDescription" cols="40" rows="4"></textarea></td>
		</tr>
	</table>
	<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $campaignId; ?>" />
	</form>
	<div class="greenButton" onClick="document.getElementById('monsterSkillForm').submit();">Create Skill</div>
	
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------