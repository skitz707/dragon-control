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
$crumbTrail = "";

require_once("includes/header.php");
?>

<div id="mainContent">
	<div id="loginFormDiv" style="margin-top: 90px;">
		<span style="font-size: 36pt; color: #ffffff;">Dragon Control Login</span>
		<br /><br /><br />
		<form method="post" action="login.php" id="loginForm">
		<table style="margin-left: auto; margin-right: auto;">
			<tr>
				<td style="font-size: 24pt;">Email</td>
				<td><input type="text" id="emailAddress" name="emailAddress" size="30" class="loginTextField" style="margin-left: 15px;" /></td>
			</tr>
			<tr>
				<td style="font-size: 24pt;">Password</td>
				<td><input type="password" id="password" name="password" size="30" class="loginTextField" style="margin-left: 15px;" /></td>
			</tr>
		</table>
		<br />
		<div class="redButton" onClick="document.getElementById('loginForm').submit();"><span style="font-size: 18pt;">Login</div>
		</form>			
	</div>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------