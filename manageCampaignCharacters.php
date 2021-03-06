<?php
//-------------------------------------------------------------------------------------------
// manageCampaignCharacters.php - Manage Items
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 31st, 2018
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
require_once("classes/DCCharacter.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$character = new DCCharacter($database);
$campaignId = $_GET['campaignId'];
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));

$pageTitle = "DC - Manage Monsters";
$crumbTrail = "";
$menuOptions = "";

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>
<br /><br />
<div id="mainContent">
	<span class="largeHeading">Manage Characters: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<a href="createMonster.php?campaignId=<?php echo $campaignId; ?>">Create Character</a>
	<br /><br />
	<?php echo getCharacterTableHTML($database, $campaignId, $character); ?>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get monster table
//-------------------------------------------------------------------------------------------
function getCharacterTableHTML($database, $campaignId, $character) {
	$selectStmt = "select * from dragons.characters where campaignId = ? order by characterName";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable" style="width: 35%;">
						<tr>
							<th style="text-align: left;">Name</th>
							<th style="text-align: right;">XP</th>
							<th style="text-align: right;">HP</th>
							<th style="text-align: right;">AC</th>
							<th style="text-align: center;">Status</th>
							<th></th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$campaignId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$character->loadCharacterById($data['characterId']);
			
			$returnHTML .= '<tr>
								<td style="text-align: left;">' . $data['characterName'] . '</td>
								<td style="text-align: right;">' . $data['characterXP'] . '</td>
								<td style="text-align: right;">' . $data['maxHP'] . '</td>
								<td style="text-align: right;">' . $character->getArmorClass() . '</td>
								<td style="text-align: center;">' . $data['statusFlag'] . '</td>
								<td style="text-align: center;"><a href="editCharacterInfo.php?characterId=' . $data['characterId'] . '">Edit</a></td>
							</tr>
			';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</table>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------