<html>
	<head>
		<title><?php echo $pageTitle; ?></title>
		<link rel='stylesheet' media='screen and (min-width: 300px)' href='css/medium.css' />
		<link rel='stylesheet' media='screen and (min-width: 1000px)' href='css/main.css' />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
	</head>
	
	<script>
	//------------------------------------------------------------------
	// function to pop the navigation menu
	//------------------------------------------------------------------
	function launchMenu() {
		menu = document.getElementById('menu');
		
		if (menu.style.visibility == 'visible') {
			menu.style.visibility = 'hidden';
		} else {
			menu.style.visibility = 'visible';
		}
	}
	//------------------------------------------------------------------
	</script>

	<body>
		<!-- control bar at top of the screen -->
		<div id="controlBar">
			<div id="controlLeft">
				<span id="menuBox" onClick="launchMenu();"></span> <span class="crumbTrail"><?php print($crumbTrail); ?></span>
			</div>
			<div id="controlRight">
				<?php if (isset($_COOKIE['userId'])) { print(date("l, F jS h:iA")); ?> | <?php print($user->getEmailAddress()); ?> | <a href="logout.php">Logout</a><?php } ?>
			</div>
		</div>

		<!-- popup menu -->
		<div id="menu">
			<?php echo $menuOptions; ?>
		</div>
		
		<div id="mainContainer">
		