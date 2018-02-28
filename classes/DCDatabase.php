<?php
//-------------------------------------------------------------------------------------------
// DCDatabase.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// This is the database interaction class for the PDO interface
//
// Change Log:
//-------------------------------------------------------------------------------------------




//-------------------------------------------------------------------------------------------
// database class definition
//-------------------------------------------------------------------------------------------
class DCDatabase {
	// live database connection handle
	public $databaseHandle;
	
	//-------------------------------------------------------------------------------------------
	// constructor function
	//-------------------------------------------------------------------------------------------
	public function __construct() {
		$database = "dragons";
		$username = "mcs";
		$password = "C@m31w@1K";
		
		try {
			$this->databaseConnection = new PDO('mysql:' . $database, $username, $password);
			$this->databaseConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch (Exception $e) {
			print("Connection failed: " . $e->getMessage());
		}
	}
	//-------------------------------------------------------------------------------------------
	
	

	//-------------------------------------------------------------------------------------------
	// public function to get database record 
	//-------------------------------------------------------------------------------------------
	public function getDatabaseRecord($fileName, $keysToFile) {
		$selectKeyArray	= array();
		$parameterArray	= array();
		$selectStmt	= "select * from " . $fileName . " where ";
		
		foreach (array_keys($keysToFile) as $key) {
			$selectKeyArray[] = $key . " = ?";
			$parameterArray[] = $keysToFile[$key];
		}
		
		$selectStmt .= implode(" and ", $selectKeyArray);
		
		if ($statementHandle = $this->databaseConnection->prepare($selectStmt)) {
			if (!$statementHandle->execute($parameterArray)) {
				var_dump($this->databaseConnection->errorInfo());
			}
			
			$data = $statementHandle->fetch(PDO::FETCH_ASSOC);
		} else {
			var_dump($this->databaseConnection->errorInfo());
		}
		
		return $data;
	}
	//-------------------------------------------------------------------------------------------
	
	
	
	//-------------------------------------------------------------------------------------------
	// public function to get database record 
	//-------------------------------------------------------------------------------------------
	public function deleteDatabaseRecord($fileName, $keysToFile) {
		$selectKeyArray	= array();
		$parameterArray	= array();
		$deleteStmt	= "delete from " . $fileName . " where ";
		
		foreach (array_keys($keysToFile) as $key) {
			$selectKeyArray[] = $key . " = ?";
			$parameterArray[] = $keysToFile[$key];
		}
		
		$deleteStmt .= implode(" and ", $selectKeyArray);
		
		if ($statementHandle = $this->databaseConnection->prepare($deleteStmt)) {
			if (!$statementHandle->execute($parameterArray)) {
				var_dump($this->databaseConnection->errorInfo());
			}
		} else {
			var_dump($this->databaseConnection->errorInfo());
			print($deleteStmt);
		}
	}
	//-------------------------------------------------------------------------------------------
	
	
	
	//-------------------------------------------------------------------------------------------
	// public function to update database record
	//-------------------------------------------------------------------------------------------
	public function updateDatabaseRecord($fileName, $fileData, $keysToFile) {
		$updateArray = array();
		$updateKeyArray	= array();
		$parameterArray = array();
		$updateStmt	= "update " . $fileName . " set ";
		
		foreach (array_keys($fileData) as $column) {
			$updateArray[] = $column . " = ?";
			$parameterArray[] = $fileData[$column];
		}
		
		foreach (array_keys($keysToFile) as $key) {
			$updateKeyArray[] = $key . " = ?";
			$parameterArray[] = $keysToFile[$key];
		}

		$updateStmt .= implode(", ", $updateArray);
		$updateStmt .= ", lastChange = CURRENT_TIMESTAMP";
		$updateStmt .= " where " . implode (" and ", $updateKeyArray);
		
		if ($statementHandle = $this->databaseConnection->prepare($updateStmt)) {
			if (!$statementHandle->execute($parameterArray)) {
				print_r($this->databaseConnection->errorInfo());
				print($updateStmt);
				var_dump($fileData);
				var_dump($keysToFile);
			}
		} else {
			print_r($this->databaseConnection->errorInfo());
			print($updateStmt);
			var_dump($fileData);
			var_dump($keysToFile);
		}
	}
	//-------------------------------------------------------------------------------------------
	
	
	
	//-------------------------------------------------------------------------------------------
	// public function to insert a database record
	//-------------------------------------------------------------------------------------------
	public function insertDatabaseRecord($fileName, $data) {
		$insertArray = array();
		$parameterArray = array();
		$questionMarkArray = array();
		$insertStmt	= "insert into " . $fileName . ' (' . implode(", ", array_keys($data)) . ", lastChange, creationDate) values(";
		
		foreach($data as $d) {
			$questionMarkArray[] = "?";
			$parameterArray[] = $d;
		}
		
		$insertStmt .= implode(", ", $questionMarkArray) . ", CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
		
		if ($statementHandle = $this->databaseConnection->prepare($insertStmt)) {
			if (!$statementHandle->execute($parameterArray)) {
				print_r($this->databaseConnection->errorInfo());
			}
		} else {
			print_r($this->databaseConnection->errorInfo());
		}
	}
	//-------------------------------------------------------------------------------------------
	
	
	
	//-------------------------------------------------------------------------------------------
	// function to retrieve a database record
	//-------------------------------------------------------------------------------------------
	public function getColumnSum($fileName, $columnName, $keysToFile) {
		$selectKeyArray = array();
		$parameterArray	= array();
		$selectStmt	= "select sum(" . $columnName . ") as coltotal from " . $fileName . " where ";
		
		foreach (array_keys($keysToFile) as $key) {
			$selectKeyArray[] = $key . " = ?";
			$parameterArray[] = $keysToFile[$key];
		}
		
		$selectStmt .= implode(" and ", $selectKeyArray);
		
		if ($statementHandle = $this->databaseConnection->prepare($selectStmt)) {
			if (!$statementHandle->execute($parameterArray)) {
				print($this->databaseConnection->errorInfo());
			}
			
			$data = $statementHandle->fetch(PDO::FETCH_ASSOC);
			$data = array_change_key_case($data, CASE_LOWER);
		} else {
			print($this->databaseConnection->errorInfo());
		}
		
		return $data['coltotal'];
	}
	//-------------------------------------------------------------------------------------------
	
	
	
	//-------------------------------------------------------------------------------------------
	// public function to retrieve a unique count of a given column based on keys
	//-------------------------------------------------------------------------------------------
	public function getUniqueCount($fileName, $columnName, $keysToFile) {
		$selectKeyArray = array();
		$parameterArray	= array();
		$selectStmt = "select count(distinct " . $columnName . ") as distcnt from " . $fileName . " where ";
		
		foreach (array_keys($keysToFile) as $key) {
			$selectKeyArray[] = $key . " = ?";
			$parameterArray[] = $keysToFile[$key];
		}
		
		$selectStmt .= implode(" and ", $selectKeyArray);
		
		if ($statementHandle = $this->databaseConnection->prepare($selectStmt)) {
			if (!$statementHandle->execute($parameterArray)) {
				print($this->databaseConnection->errorInfo());
			}
			
			$data = $statementHandle->fetch(PDO::FETCH_ASSOC);
			$data = array_change_key_case($data, CASE_LOWER);
		} else {
			print($this->databaseConnection->errorInfo());
		}
		
		return $data['distcnt'];
	}
	//-------------------------------------------------------------------------------------------
	
	
	
	//-------------------------------------------------------------------------------------------
	// public function to get column max based on keys
	//-------------------------------------------------------------------------------------------
	public function getColumnMax($fileName, $columnName, $keysToFile) {
		$selectKeyArray = array();
		$parameterArray	= array();
		$selectStmt	= "select max(" . $columnName . ") as maxcount from " . $fileName . " where ";
		
		foreach (array_keys($keysToFile) as $key) {
			$selectKeyArray[] = $key . " = ?";
			$parameterArray[] = $keysToFile[$key];
		}
		
		$selectStmt .= implode(" and ", $selectKeyArray);
		
		if ($statementHandle = $this->databaseConnection->prepare($selectStmt)) {
			if (!$statementHandle->execute($parameterArray)) {
				print($databaseConnection->errorInfo());
			}

			$data = $statementHandle->fetch(PDO::FETCH_ASSOC);
			$data = array_change_key_case($data, CASE_LOWER);
		} else {
			print($databaseConnection->errorInfo());
		}
		
		return $data['maxcount'];
	}
	//-------------------------------------------------------------------------------------------
	
}
//-------------------------------------------------------------------------------------------
?>