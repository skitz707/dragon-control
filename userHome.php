<?php
//-------------------------------------------------------------------------------------------
// index.php - Index/login page.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 14th, 2018
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
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$security = new DCSecurity($database);

// check for active user
$security->checkLogin();

$pageTitle = "Dragon Control - Login";
$crumbTrail = "";
$menuOptions = file_get_contents('includes/mainMenuOptions.php');

require_once("includes/header.php");
?>

<div id="mainContent">

</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------