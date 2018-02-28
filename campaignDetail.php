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
require_once("classes/DCDatabase.php");
require_once("classes/DCCampaign.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$campaign = new DCCampaign($database);
$campaign->loadCampaignById($_GET['campaignId']);

$pageTitle = "DC - Campaign Detail";
$crumbTrail = "Campaigns > " . $campaign->getCampaignName();

// get menu options
ob_start();
require('includes/campaignMenuOptions.php');
$menuOptions = ob_get_clean();

require_once("includes/header.php");
?>

<div id="mainContent">
	<span class="largeHeading">Campaign: <?php print($campaignHeader['campaignName']); ?></span>
	<br /><br />
	<table style="margin-left: auto; margin-right: auto;">
		<tr>
			<td>1.</td>
			<td><a href="DCSessionManager.php?campaignId=<?php print($campaignId); ?>">Session Manager</a></td>
		</tr>
		<tr>
			<td>2.</td>
			<td><a href="DCBattleManager.php?campaignId=<?php print($campaignId); ?>">Battle Manager</a></td>
		</tr>
		<tr>
			<td>3.</td>
			<td><a href="manageCampaignCharacters.php?campaignId=<?php print($campaignId); ?>">Manage Characters</a></td>
		</tr>
		<tr>
			<td>4.</td>
			<td><a href="manageMonsters.php?campaignId=<?php print($campaignId); ?>">Manage Monsters</a></td>
		</tr>
		<tr>
			<td>5.</td>
			<td><a href="manageItems.php?campaignId=<?php print($campaignId); ?>">Manage Items</a></td>
		</tr>
	</table>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------