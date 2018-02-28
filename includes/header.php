<html>
	<head>
		<title><?php echo $pageTitle; ?></title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
	</head>
	<body>
		<!-- control bar at top of the screen -->
		<div id="controlBar">
			<div id="controlLeft">
				<span id="menuBox" onClick="launchMenu();"></span> <?php print($crumbTrail); ?>
			</div>
			<div id="controlRight">
				<?php if (isset($_SESSION['username'])) { print(date("l, F jS h:iA")); ?> | User: <?php print(strtoupper($_SESSION['username'])); ?> | <a href="http://192.168.2.10:10080/logout.php">Logout</a><?php } ?>
			</div>
		</div>

		<!-- popup menu -->
		<div id="menu"><?php if (isset($_SESSION['username'])) { print($user->getMenuOptions($_SESSION['username'])); } ?></div>
		
		<div id="mainContainer">
		