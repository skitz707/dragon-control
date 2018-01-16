<?php
//-------------------------------------------------------------------------------------------
// DDEnemy.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// DDEnemy() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// database class definition
//-------------------------------------------------------------------------------------------
class DDEnemy extends DDObject {
	// class properties
	protected $enemyId;
	protected $enemyName;
	protected $maxHP;
	protected $currentHP;
	protected $armorClass;
	protected $strength;
	protected $dexterity;
	protected $constitution;
	protected $intelligence;
	protected $wisdom;
	protected $charisma;
	protected $initiative;
	protected $imageLocation;
	protected $statusFlag;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load player by playerId
	//------------------------------------------------------------------------
	public function loadEnemyById($enemyId) {
		$enemyRecord = $this->database->getDatabaseRecord("dragons.enemies", array("enemyId"=>$enemyId));
		
		$this->enemyId = $enemyId;
		$this->enemyName = $enemyRecord['enemyName'];
		$this->maxHP = $enemyRecord['maxHP'];
		$this->currentHP = $enemyRecord['currentHP'];
		$this->armorClass = $enemyRecord['armorClass'];
		$this->strength = $enemyRecord['strength'];
		$this->dexterity = $enemyRecord['dexterity'];
		$this->constitution = $enemyRecord['constitution'];
		$this->intelligence = $enemyRecord['intelligence'];
		$this->wisdom = $enemyRecord['wisdom'];
		$this->charisma = $enemyRecord['charisma'];
		$this->initiative = $enemyRecord['initiative'];
		$this->imageLocation = $enemyRecord['imageLocation'];
		$this->statusFlag = $enemyRecord['statusFlag'];
		$this->lastChange = $enemyRecord['lastChange'];
		$this->creationDate = $enemyRecord['creationDate'];
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// print player card
	//------------------------------------------------------------------------
	public function printEnemyCard() {
		echo '
			<div class="playerCard">
				<img src="' . $this->imageLocation . '" width="120px" height="160px" /><br />
				<span class="playerName">' . $this->enemyName . '</span><br />
				AC: ' . $this->armorClass . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	
	//------------------------------------------------------------------------
	// print player card
	//------------------------------------------------------------------------
	public function printAdminEnemyCard() {
		echo '
			<div class="adminPlayerCard">
				<img src="' . $this->imageLocation . '" width="80px" height="120px" /><br />
				<span class="adminPlayerName">' . $this->enemyName . '</span><br />
				<em>Enemy</em><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '
				<div class="blueButton" onClick="setInit(\'M\', ' . $this->enemyId . ');">Set Init</div> <div class="redButton" onClick="takeDamage(\'M\', ' . $this->enemyId . ');">Take Damage</div> <div class="greenButton" onClick="heal(\'M\', ' . $this->enemyId . ');">Heal</div>
			</div>
		';
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------