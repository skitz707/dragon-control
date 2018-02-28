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
require_once("classes/DCCharacter.php");
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
	<div id="characterList" style="margin-top: 95px;">
	<?php echo $activeCharactersHTML; ?>
	</div>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get active characters html
//-------------------------------------------------------------------------------------------
function getActiveCharactersHTML($database, $user) {
	$character = new DCCharacter($database);
	$returnHTML = "";
	$characterIds = $user->getActiveCharacters();
	
	
	
	foreach ($characterIds as $characterId) {
		$character->loadCharacterById($characterId);
		$campaignMaster = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$character->getCampaignId()));

		$returnHTML .= '<div class="characterSelect" onClick="document.location.href=\'characterDetail.php?characterId=' . $characterId . '\';">
						<img src="' . $character->getImageLocation() .'" height="180px" width="180px" style="float: left; padding-right: 25px;" />' . 
						'<span style="font-size: 56pt;">' . $character->getCharacterName() . '</span><br />
						<span style="font-size: 32pt; font-style: italic;">Aborrition/Sprite - Level: 1<br />
						Campaign: ' . $campaignMaster['campaignName'] . '</span></div>';
		
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