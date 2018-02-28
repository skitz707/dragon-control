<?php
//-------------------------------------------------------------------------------------------
// index.php - Index/login page.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 14th, 2018
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
$menuOptions = file_get_contents('includes/mainMenuOptions.php');

//$campaignsLeadingHTML = getCampaignsLeadingHTML($database, $user);
//$activeCharactersHTML = getActiveCharactersHTML($database, $user);

require_once("includes/header.php");
?>

<div id="mainContent">
	<!--
	<span class="mediumHeading">Campaigns Leading:</span>
	<br /><br />
	<?php //print($campaignsLeadingHTML); ?>
	<br /><br />
	<span class="mediumHeading">Active Characters:</span>
	<br /><br />
	<?php //print($activeCharactersHTML); ?>
	-->
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



//-------------------------------------------------------------------------------------------
// get active characters html
//-------------------------------------------------------------------------------------------
function getActiveCharactersHTML($database, $user) {
	$returnHTML = "";
	$characterIds = $user->getActiveCharacters();
	
	$returnHTML .= '<table class="standardResultTable" style="width: 60%;">
						<tr>
							<th>Character</td>
							<th>Race</td>
							<th>Class</td>
							<th>Level</td>
							<th>Campaign</td>
							<th>Active Quest</td>
							<th></td>
						</tr>
	';
	
	foreach ($characterIds as $characterId) {
		$characterMaster = $database->getDatabaseRecord("dragons.characters", array("characterId"=>$characterId));
		$campaignMaster = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$characterMaster['campaignId']));
		$questMaster = $database->getDatabaseRecord("dragons.questHeader", array("campaignId"=>$characterMaster['campaignId'], "statusFlag"=>"A"));
		
		if ($questMaster['questId'] > 0) {
			$activeQuest = $questMaster['questName'];
		} else {
			$activeQuest = "N/A";
		}
		
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
	}
	
	$returnHTML .= '</table>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------