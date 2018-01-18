<?php
require_once 'classes/DDDatabase.php';

$db = mysqli_connect("45.46.168.12", "nicko", "nicko", "dragons");
$qry = "INSERT into userMster (emailAddress, firstName, lastName, passwordHash)
    values ('ncorlowski@gmail.com', 'Nick', 'Orlowski', md5('nick'))";

$mysqli_query($db, $qry);

