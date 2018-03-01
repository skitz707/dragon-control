<?php
//-------------------------------------------------------------------------------------------
// monsterSkills.php - Manage Items
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// February 21st, 2018
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

$pageTitle = "DC - Monster Skills";
$crumbTrail = "";
$menuOptions = "";

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Monster Skills: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<a href="createMonsterSkill.php?campaignId=<?php echo $campaignId; ?>">Create Skill</a>
	<br /><br />
	<?php echo getMonsterSkillsTableHTML($database, $campaignId); ?>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get monster skills table html
//-------------------------------------------------------------------------------------------
function getMonsterSkillsTableHTML($database, $campaignId) {
	$selectStmt = "select * from dragons.specialSkills where campaignId = ? order by specialSkillName";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable" width="66%">
						<tr>
							<th>Skill Name</th>
							<th>Skill Desc</th>
							<th></th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$campaignId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<tr>
								<td><span style="margin-right: 15px;">' . $data['specialSkillName'] . '</span></td>
								<td>' . $data['specialSkillDescription'] . '</td>
								<td><a href="editMonsterSkill.php?specialSkillId=' . $data['specialSkillId'] . '">Edit</a></td>
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