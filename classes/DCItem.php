<?php
//-------------------------------------------------------------------------------------------
// DCItem.php
// Written by: Michael C. Szczepanik
// February 1st, 2018
// DCItem() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DCObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// item class definition
//-------------------------------------------------------------------------------------------
class DCItem extends DCObject {
	// class properties
	protected $itemId;
	protected $campaignId;
	protected $itemName;
	protected $itemDescription;
	protected $itemTypeId;
	protected $itemType;
	protected $itemCost;
	protected $itemWeight;
	protected $itemProperties;
	protected $itemDice;
	protected $itemEquipableLocations;
	protected $imageLocation;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load item by item id
	//------------------------------------------------------------------------
	public function loadItemById($itemId) {
		$itemMaster = $this->database->getDatabaseRecord("dragons.itemMaster", array("itemId"=>$itemId));
		$itemTypes = $this->database->getDatabaseRecord("dragons.itemTypes", array("itemTypeId"=>$itemMaster['itemType']));
		
		$this->itemId = $itemId;
		$this->campaignId = $itemMaster['campaignId'];
		$this->itemName = $itemMaster['itemName'];
		$this->itemDescription = $itemMaster['itemDescription'];
		$this->itemTypeId = $itemMaster['itemType'];
		$this->itemType = $itemTypes['itemType'];
		$this->itemCost = $itemMaster['cost'];
		$this->itemWeight = $itemMaster['itemWeight'];
		$this->imageLocation = $itemMaster['imageLocation'];
		
		$this->loadItemProperties();
		$this->loadEquipableLocations();
		$this->loadItemDice();
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load item properties
	//------------------------------------------------------------------------
	private function loadItemProperties() {
		// load item properties
		$selectStmt = "select * from dragons.itemProperties where itemId = ?";
		$this->itemProperties = array();
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->itemId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->itemProperties[] = $data['weaponPropertyId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load equipable locations
	//------------------------------------------------------------------------
	private function loadEquipableLocations() {
		// load equipable locations
		$selectStmt = "select * from dragons.itemEquipableLocations where itemId = ?";
		$this->itemEquipableLocations = array();
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->itemId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->itemEquipableLocations[] = $data['equipableLocationId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load item dice
	//------------------------------------------------------------------------
	private function loadItemDice() {
		// load equipable locations
		$selectStmt = "select * from dragons.itemDice where itemId = ? order by itemDiceId";
		$this->itemDice = array();
		
		if ($selectHandle = $this->database->databaseConnection->prepare($selectStmt)) {
			if (!$selectHandle->execute(array(0=>$this->itemId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->itemDice[] = $data['itemDiceId'];
			}
		} else {
			var_dump($this->database->databaseConnection->errorInfo());
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get item properties
	//------------------------------------------------------------------------
	public function getItemProperties() {
		return $this->itemProperties;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get item equipable locations
	//------------------------------------------------------------------------
	public function getItemEquipableLocations() {
		return $this->itemEquipableLocations;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get item name
	//------------------------------------------------------------------------
	public function getItemName() {
		return $this->itemName;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get image location
	//------------------------------------------------------------------------
	public function getImageLocation() {
		return $this->imageLocation;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get item type
	//------------------------------------------------------------------------
	public function getItemType() {
		return $this->itemType;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get item weight
	//------------------------------------------------------------------------
	public function getItemWeight() {
		return $this->itemWeight;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get item cost
	//------------------------------------------------------------------------
	public function getItemCost() {
		return $this->itemCost;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get item dice
	//------------------------------------------------------------------------
	public function getItemDice() {
		return $this->itemDice;
	}
	//------------------------------------------------------------------------
}	
//-------------------------------------------------------------------------------------------