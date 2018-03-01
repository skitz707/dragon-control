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
$character = new DCCharacter($database);
$character->loadCharacterById($_GET['characterId']);

// check for active user
$security->checkLogin();

$user->loadUserById($_SESSION['userId']);

$pageTitle = "Dragon Control - Login";
$crumbTrail = "Characters > " . $character->getName();

// get menu options
ob_start();
require('includes/characterMenuOptions.php');
$menuOptions = ob_get_clean();

require_once("includes/header.php");
?>

<div id="mainContent">
	<div id="characterDetail" style="padding-top: 110px; text-align: left;">
		<img src="<?php echo $character->getImageLocation(); ?>" width="250px" height="250px" style="float: left; padding-right: 25px; padding-left: 25px;" />
		<span style="font-size: 6vw;"><?php echo $character->getName(); ?></span><br />
		<span style="font-style: italic; font-size: 38pt;"><?php echo $character->getRace(); ?> / <?php echo $character->getClass(); ?></span><br />
		<span style="font-size: 38pt;">Level: <?php echo $character->getLevel(); ?> | XP: <?php echo $character->getXP(); ?></span>
		<div style="clear:both"></div>
		<br />
		<div style="text-align: center; font-size: 62pt;">
			AC: <?php echo $character->getArmorClass(); ?><br />
			Prof Bonus: <?php echo $character->getProficiencyBonus(); ?><br />
			Initiative: <?php echo number_format($character->getInitiative(), 0, "", ""); ?><br />
			HP: <?php echo $character->getCurrentHP() . '/' . $character->getMaxHP(); ?><br />
			<table style="font-size:46pt; width: 90%; margin-left: auto; margin-right: auto; margin-top: 25px; margin-bottom: 25px;">
				<tr>
					<td>STR:</td>
					<td><?php echo $character->getStrength() .'(' . sprintf("%+d", $character->getStrengthModifier()); ?>)</td>
					<td>DEX:</td>
					<td><?php echo $character->getDexterity() .'(' . sprintf("%+d", $character->getDexterityModifier()); ?>)</td>
				</tr>
				<tr>
					<td>CON:</td>
					<td><?php echo $character->getConstitution() .'(' . sprintf("%+d", $character->getConstitutionModifier()); ?>)</td>
					<td>INT:</td>
					<td><?php echo $character->getIntelligence() .'(' . sprintf("%+d", $character->getIntelligenceModifier()); ?>)</td>
				</tr>
				<tr>
					<td>WIS:</td>
					<td><?php echo $character->getWisdom() .'(' . sprintf("%+d", $character->getWisdomModifier()); ?>)</td>
					<td>CHA:</td>
					<td><?php echo $character->getCharisma() .'(' . sprintf("%+d", $character->getCharismaModifier()); ?>)</td>
				</tr>
			</table>
			<div class="blueButton" onClick="setInitiative('<?php echo $character->getBattleDetailId(); ?>');">Set Initiative</div>
		</div>
	</div>
	<div id="popUpBox"></div>
</div>

<script>
//----------------------------------------------------------------------------
// set initiative
//----------------------------------------------------------------------------
function setInitiative(id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="setInitiative.php?type=C" id="initForm">';
	divHTML += '<span style="font-size: 48pt;">Initiative: <input type="text" size="2" id="initiative" name="initiative" /> <div class="blueButton" onClick="document.getElementById(\'initForm\').submit();">Set</div></span>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $character->getCampaignId(); ?>" />';
	divHTML += '<input type="hidden" name="returnTo" id="returnTo" value="characterDetail.php?characterId=<?php echo $character->getId(); ?>" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 725,
			height: 250
		});
	});
}
//----------------------------------------------------------------------------
</script>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------