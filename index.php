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
require_once("classes/DDDatabase.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$pageTitle = "Dragon Control - Login";

require_once("includes/header.php");
?>

<div id="mainContent">
	<span class="largeHeading">Dragon-Control Login</span>
	<br /><br />
	<form method="post" action="login.php" id="loginForm">
	<table style="margin-left: auto; margin-right: auto;">
		<tr>
			<td>Email</td>
			<td><input type="text" id="emailAddress" name="emailAddress" size="20" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" id="password" name="password" size="20" /></td>
		</tr>
	</table>
	<br />
	<div class="blueButton" onClick="document.getElementById('loginForm').submit();">Login</div>
	</form>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------