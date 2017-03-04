<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $title;?></title>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		
		<meta name="description" content="<?php echo $description;?>">
		<meta name="keywords" content="jodelblue, jodel, blue, webclient, web, client, web-app, browser, app">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.css" integrity="sha256-8aNQFtmxcOMVoOhLD4mrHqaKC2Ui++LmlQsoKTqxwIE=" crossorigin="anonymous" />
		<link rel="stylesheet" href="<?php echo $baseUrl;?>css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo $baseUrl;?>style.css" type="text/css">
		
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $baseUrl;?>img/favicon/favicon.ico">
		<link rel="icon" type="image/x-icon" href="<?php echo $baseUrl;?>img/favicon/favicon.ico">
		<link rel="icon" type="image/gif" href="<?php echo $baseUrl;?>img/favicon/favicon.gif">
		<link rel="icon" type="image/png" href="<?php echo $baseUrl;?>img/favicon/favicon.png">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon.png">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-57x57.png" sizes="57x57">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-60x60.png" sizes="60x60">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-72x72.png" sizes="72x72">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-76x76.png" sizes="76x76">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-114x114.png" sizes="114x114">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-120x120.png" sizes="120x120">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-128x128.png" sizes="128x128">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-144x144.png" sizes="144x144">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-152x152.png" sizes="152x152">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-180x180.png" sizes="180x180">
		<link rel="apple-touch-icon" href="<?php echo $baseUrl;?>img/favicon/apple-touch-icon-precomposed.png">
		<link rel="icon" type="image/png" href="<?php echo $baseUrl;?>img/favicon/favicon-16x16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="<?php echo $baseUrl;?>img/favicon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php echo $baseUrl;?>img/favicon/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php echo $baseUrl;?>img/favicon/favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="<?php echo $baseUrl;?>img/favicon/favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php echo $baseUrl;?>img/favicon/favicon-196x196.png" sizes="196x196">
		<meta name="msapplication-TileImage" content="<?php echo $baseUrl;?>img/favicon/win8-tile-144x144.png"> 
		<meta name="msapplication-TileColor" content="#5682a3"> 
		<meta name="msapplication-navbutton-color" content="#5682a3"> 
		<meta name="application-name" content="JodelBlue"/> 
		<meta name="msapplication-tooltip" content="JodelBlue"/> 
		<meta name="apple-mobile-web-app-title" content="JodelBlue"/> 
		<meta name="msapplication-square70x70logo" content="<?php echo $baseUrl;?>img/favicon/win8-tile-70x70.png"> 
		<meta name="msapplication-square144x144logo" content="<?php echo $baseUrl;?>img/favicon/win8-tile-144x144.png"> 
		<meta name="msapplication-square150x150logo" content="<?php echo $baseUrl;?>img/favicon/win8-tile-150x150.png"> 
		<meta name="msapplication-wide310x150logo" content="<?php echo $baseUrl;?>img/favicon/win8-tile-310x150.png"> 
		<meta name="msapplication-square310x310logo" content="<?php echo $baseUrl;?>img/favicon/win8-tile-310x310.png"> 
	</head>

	<body>
	<header>
		<nav class="navbar navbar-full navbar-dark fixed-top">
			<div class="container">					
					<?php
						if(isset($view))
						{
							$refreshUrl = $view->toUrl();
						}
						else
						{
							$refreshUrl = $baseUrl;
						}
						if($backButton != '')
						{
							echo '<a id="comment-back" href="' . $backButton . '">';
							echo '<i class="fa fa-angle-left fa-3x"></i>';
							echo '</a>';
							echo '<h1>';
							echo '<a href="' . $refreshUrl . '" class="spinnable hidden-xs-down">';
						}
						else
						{
							echo '<h1>';	
							echo '<a href="' . $refreshUrl . '" class="spinnable">';
						}
					?>
					JodelBlue <i class="fa fa-refresh fa-1x"></i></a>
				</h1>

				<div id="location_mobile" class="hidden-sm-up">
					<form method="get">
						<input type="text" id="city_mobile" name="search" placeholder="<?php if(isset($newPositionStatus)) echo $newPositionStatus; ?>" required>

						<input type="submit" id="submit_mobile" class="fa" value="&#xf0ac;" />
					</form>
				</div>
			</div>
		</nav>
	</header>