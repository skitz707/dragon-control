<?php
//-------------------------------------------------------------------------------------------
// campaigns.php
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// February 28th, 2018
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
require_once("classes/DCSecurity.php");
require_once("classes/DCUser.php");
require_once("classes/DCCampaign.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// program directives
//-------------------------------------------------------------------------------------------
session_start();
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$security = new DCSecurity($database);
$user = new DCUser($database);

// check for active user
$security->checkLogin();

$user->loadUserById($_SESSION['userId']);

$pageTitle = "Dragon Control - Login";
$crumbTrail = "Characters";
$menuOptions = file_get_contents('includes/mainMenuOptions.php');

$campaignsLeadingHTML = getCampaignsLeadingHTML($database, $user);

require_once("includes/header.php");
?>

<div id="mainContent">
	<div id="characterList" style="margin-top: 95px;">
	<?php echo $campaignsLeadingHTML; ?>
	</div>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get active characters html
//-------------------------------------------------------------------------------------------
function getCampaignsLeadingHTML($database, $user) {
	$campaign = new DCCampaign($database);
	$campaignIds = $user->getCampaignsLeading();
	$returnHTML = "";

	foreach ($campaignIds as $campaignId) {
		$campaign->loadCampaignById($campaignId);

		$returnHTML .= '<div class="characterSelect" onClick="document.location.href=\'campaignDetail.php?campaignId=' . $campaignId . '\';">
						<span style="font-size: 56pt;">' . $campaign->getCampaignName() . '</span><br />
						<span style="font-size: 32pt; font-style: italic;">Characters: ' . count($campaign->getActiveCharacters()) . ' | 
						Started: ' . $campaign->getCreationDate() . '</div>';
		
		/*
		$returnHTML .= '<tr>
							<td>' . $characterMaster['characterName'] . '</td>
							<td>' . $characterMaster['characterRace'] . '</td>
							<td>' . $characterMaster['characterClass'] . '</td>
							<td>' . $characterMaster['characterLevel'] . '</td>
							<td>' . $campaignMaster['campaignName'] . '</td>
							<td>' . $activeQuest . '</td>
							<td><a href="editCharacter.php?characterId=' . $characterId . '">Edit</a></td>
						</tr>
		';
		*/
	}
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------