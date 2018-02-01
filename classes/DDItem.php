<?php
//-------------------------------------------------------------------------------------------
// DDItem.php
// Written by: Michael C. Szczepanik
// February 1st, 2018
// DDItem() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// item class definition
//-------------------------------------------------------------------------------------------
class DDItem extends DDObject {
	// class properties
	protected $itemId;
	protected $campaignId;
	protected $itemName;
	protected $itemDescription;
	protected $itemTypeId;
	protected $itemTypeText;
	protected $itemCost;
	protected $itemWeight;
	protected $itemProperties;
	protected $itemDice;
	protected $itemEquipableLocations;
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
		$this->itemTypeText = $itemTypes['itemType'];
		$this->itemCost = $itemMaster['cost'];
		$this->itemWeight = $itemMaster['itemWeight'];
		
		// load item properties
		$propertiesStmt = "select * from dragons.itemProperties where itemId = ?";
		$this->itemProperties = array();
		
		if ($propertiesHandle = $this->database->databaseConnection->prepare($propertiesStmt)) {
			if (!$propertiesHandle->execute(array(0=>$this->itemId))) {
				var_dump($this->database->databaseConnection->errorInfo());
			}
			
			while ($propertyData = $propertiesHandle->fetch(PDO::FETCH_ASSOC)) {
				$this->itemProperties[] = $propertyData['weaponPropertyId'];
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
}	
//-------------------------------------------------------------------------------------------