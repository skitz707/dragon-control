<?php
//-------------------------------------------------------------------------------------------
// campaigns.php - Index/login page.
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
$crumbTrail = "";

//$campaignsLeadingHTML = getCampaignsLeadingHTML($database, $user);
//$activeCharactersHTML = getActiveCharactersHTML($database, $user);

require_once("includes/header.php");
?>

<div id="mainContent">

</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get campaigns leading html
//-------------------------------------------------------------------------------------------
function getCampaignsLeadingHTML($database, $user) {
	$returnHTML = "";
	$campaignIds = $user->getCampaignsLeading();
	
	$returnHTML = '<table class="standardResultTable" style="width: 60%;">
					<tr>
						<th>Campaign Name</td>
						<th># of Quests Played</td>
						<th># of Characters</td>
						<th>Date Started</td>
						<th>Last Played</td>
						<th></td>
					</tr>
	';
	
	foreach ($campaignIds as $campaignId) {
		$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));
		$numberOfQuestsPlayed = $database->getUniqueCount("dragons.questHeader", "questId", array("campaignId"=>$campaignId));
		$numberOfCharactersInCampaign = $database->getUniqueCount("dragons.characters", "characterId", array("campaignId"=>$campaignId));
		
		$returnHTML .= '<tr>
							<td><a href="campaignDetail.php?campaignId=' . $campaignId . '">' . $campaignHeader['campaignName'] . '</a></td>
							<td>' . $numberOfQuestsPlayed . '</td>
							<td>' . $numberOfCharactersInCampaign . '</td>
							<td>' . $campaignHeader['creationDate'] . '</td>
							<td>' . $campaignHeader['lastPlayed'] . '</td>
							<td><a href="editCampaign.php?campaignId=' . $campaignId . '">Edit</a></td>
						</tr>
		';
	}
	
	$returnHTML .= '</table>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------