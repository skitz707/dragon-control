<?php
//-------------------------------------------------------------------------------------------
// manageItems.php - Manage Items
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
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
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
	<span class="largeHeading">Manage Monsters: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<a href="createMonster.php?campaignId=<?php echo $campaignId; ?>">Create Monster</a> | 
	<a href="monsterSkills.php?campaignId=<?php echo $campaignId; ?>">Monster Skills</a> |
	<a href="monsterAttacks.php?campaignId=<?php echo $campaignId; ?>">Monster Attacks</a>
	<br /><br />
	<?php echo getMonsterTableHTML($database, $campaignId); ?>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get monster table
//-------------------------------------------------------------------------------------------
function getMonsterTableHTML($database, $campaignId) {
	$selectStmt = "select * from dragons.monsters where campaignId = ? order by xpRating, monsterName";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable">
						<tr>
							<th>Name</th>
							<th>XP</th>
							<th>HP</th>
							<th>AC</th>
							<th></th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$campaignId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<tr>
								<td>' . $data['monsterName'] . '</td>
								<td>' . $data['xpRating'] . '</td>
								<td>' . $data['health'] . '</td>
								<td>' . $data['armorClass'] . '</td>
								<td><a href="editMonster.php?monsterId=' . $data['monsterId'] . '">Edit</a></td>
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