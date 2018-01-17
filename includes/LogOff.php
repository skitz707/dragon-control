<?php
session_start();
unset ($_SESSION['UserName']);
unset ($_SESSION['LoggedIn']);
unset ($_SESSION['UserID']);
unset ($_SESSION['IsAdmin']);
header ('Location: ../');
 ?>
