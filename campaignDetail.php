<?php
//-------------------------------------------------------------------------------------------
// campaignDetail.php - Campaign detail page.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 15th, 2018
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
$pageTitle = "DC - Campaign Detail";
$campaignId = $_GET['campaignId'];
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));


require_once("includes/header.php");
?>

<div id="mainContent">
	<span class="largeHeading">Campaign: <?php print($campaignHeader['campaignName']); ?></span>
	<br /><br />
	<table style="margin-left: auto; margin-right: auto;">
		<tr>
			<td>1. </td>
			<td><a href="DCSessionManager.php?campaignId=<?php print($campaignId); ?>">Session Manager</a></td>
		</tr>
		<tr>
			<td>2. </td>
			<td><a href="DCBattleManager.php?campaignId=<?php print($campaignId); ?>">Battle Manager</a></td>
		</tr>
		<tr>
			<td>3. </td>
			<td><a href="manageCampaignCharacters.php?campaignId=<?php print($campaignId); ?>">Manage Characters</a></td>
		</tr>
		<tr>
			<td>4. </td>
			<td><a href="createMonsters.php?campaignId=<?php print($campaignId); ?>">Create Monsters</a></td>
		</tr>
	</table>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------