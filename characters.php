<?php
//-------------------------------------------------------------------------------------------
// characters.php
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
$crumbTrail = "Characters";

//$campaignsLeadingHTML = getCampaignsLeadingHTML($database, $user);
$activeCharactersHTML = getActiveCharactersHTML($database, $user);

require_once("includes/header.php");
?>

<div id="mainContent">

</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get active characters html
//-------------------------------------------------------------------------------------------
function getActiveCharactersHTML($database, $user) {
	$returnHTML = "";
	$characterIds = $user->getActiveCharacters();
	
	$returnHTML = '<div class="characterSelect">';
	
	foreach ($characterIds as $characterId) {
		$characterMaster = $database->getDatabaseRecord("dragons.characters", array("characterId"=>$characterId));
		$campaignMaster = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$characterMaster['campaignId']));
		$questMaster = $database->getDatabaseRecord("dragons.questHeader", array("campaignId"=>$characterMaster['campaignId'], "statusFlag"=>"A"));
		
		if ($questMaster['questId'] > 0) {
			$activeQuest = $questMaster['questName'];
		} else {
			$activeQuest = "N/A";
		}
		
		$returnHTML .= $characterMaster['characterName'];
		
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
	
	$returnHTML .= '</div>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------