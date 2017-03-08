<?php

include 'php/jodel-web.php';


if(isset($_GET['pw']))
{
	setcookie('JodelAdminPassword', $_GET['pw'], time()+60*60*24*365*10);
	error_log('admin password saved for [' . $_SERVER ['HTTP_USER_AGENT'] . ']');
	header('Location: ' . $baseUrl . 'admin.php');
	exit;
}
else if(isset($_GET['voterPw']))
{
	setcookie('JodelVoterPassword', $_GET['voterPw'], time()+60*60*24*365*10);
	error_log('voter password saved for [' . $_SERVER ['HTTP_USER_AGENT'] . ']');
	header('Location: ' . $baseUrl);
	exit;
}

if(isUserAdmin())
{
	$userIsAdmin = true;
	$userIsVoter = true;
	$votesRemaining = 'Unlimited';
}
else if(isUserVoter())
{
	$userIsAdmin = false;
	$userIsVoter = true;

	$result = $db->query("SELECT user_token, remaining_votes FROM users WHERE user_token = '" . $_COOKIE['JodelVoterPassword'] . "'");
	if($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		$votesRemaining = $row['remaining_votes'];
	}
	else
	{
		error_log('Hard error: isUser voter, get remaining votes in admin.php');
	}
}
else
{
	error_log($_SERVER['REMOTE_ADDR']  . ' used a wrong voterPw / pw on admin.php');
	die();
}


if($userIsAdmin && isset($_POST['createAccount']) && $_POST['createAccount'])
{
	$newJodelAccount = new JodelAccount();
}

if($userIsAdmin && isset($_POST['createVoter']) && $_POST['createVoter'])
{
	//insert voter into db
	$db = new DatabaseConnect();
    $result = $db->query("INSERT INTO users (user_token, remaining_votes, device_uid, rights)
                    VALUES ('" 	. $db->escape_string($_POST['user_token'])
                    	. "','" . $db->escape_string($_POST['remaining_votes'])
                    	. "','" . $db->escape_string($_POST['device_uid'])
                    	. "','" . $db->escape_string($_POST['rights']) . "')");
    
    if($result === false){
            $error = db_error();
            error_log($error);
            error_log("Adding Voter failed: (" . $result->errno . ") " . $result->error);
    } 
}

//Vote
/*
if($userIsVoter && isset($_POST['vote']) && isset($_POST['postId']) && isset($_POST['quantity']))
{
	$i = 0;
	$result = $db->query("SELECT access_token, device_uid FROM accounts WHERE device_uid NOT IN (SELECT device_uid FROM votes WHERE postId = '" . $_POST['postId'] . "')");

	if($result->num_rows > 0)
	{
		// output data of each row
		while(($row = $result->fetch_assoc()) && $i < $_POST['quantity'])
		{
			$jodelAccount = new JodelAccount($row['device_uid']);

			if($jodelAccount->votePostId($_POST['postId'], $_POST['vote']))
			{
				$i++;
			}
		}
	}
	else
	{
		error_log("Error: 0 results");
	}
}
*/

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Backend - JodelBlue WebClient</title>
		
		<meta charset="utf8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		
		<meta name="description" content="JodelBlue is a WebClient for the Jodel App. No registration required! Browse Jodels all over the world. Send your own Jodels or upvote others.">
		<meta name="keywords" content="jodelblue, jodel, blue, webclient, web, client">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
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
			<nav class="navbar navbar-full navbar-dark navbar-fixed-top">
				<div class="container">					
					<h1>
						<a href="./admin.php" class="spinnable">						
							JodelBlue <i class="fa fa-refresh fa-1x"></i>
						</a>
					</h1>					
				</div>
			</nav>
		</header>
		
		<div class="mainContent container">		
			<div class="row">
				<article class="topContent col-12 col-sm-12">
					<content id="posts" class="adminpanel">
						<?php if($userIsAdmin) { ?>
							<div class="row">
								<div class="col-md-12">
									<h2>Account management</h2>
								</div>

								<div class="col-md-4">
										<h3>User accounts</h3>
										<form method="post">
											<div>
												<?php
													$result = $db->query("SELECT COUNT(*) FROM accounts");
													echo $result->fetch_row()[0];
													$db->close();
												?>
												accounts in the database
											</div>
											<button type="submit" name="createAccount" value="TRUE">Create new Account</button>
										</form>
								</div>

								<div class="col-md-8">
									<h3>Create Voter</h3>
									<form method="post">
										<div class="form-group">
											<label for="user_token">User token</label>
											<input type="text" class="form-control" id="user_token" name="user_token" placeholder="user_token" required="true">
										</div>
										<div class="form-group">
											<label for="remaining_votes">Remaining votes</label>
											<input type="number" class="form-control" name="remaining_votes" placeholder="remaining_votes" required="true">
										</div>
										<div class="form-group">
											<label for="device_uid">Device Uid</label>
											<input type="text" class="form-control" name="device_uid" placeholder="device_uid" required="true">
										</div>
										<div class="form-group">
											<label for="rights">Rights</label>
											<input type="text" class="form-control" name="rights" placeholder="rights" required="true">
										</div>
										<button type="submit" name="createVoter" value="TRUE">Create new Voter</button>
									</form>
								</div>							
							</div>
						<hr>
						<?php
						}

						if($userIsVoter) {
						?>
							<div class="row">								
								<div class="col-12 col-sm-12">
								<h2>Voting (<?php echo $votesRemaining;?> votes remaining)</h2>
									<form>
										<div class="form-group">
											<label for="quantityDelay">Quantity</label>
											<input placeholder="quantity" class="form-control" id="quantityDelay" type="number" name="quantity">
										</div>
										<div class="form-group">
											<label for="minDelay">Minimum delay</label>
											<div class="input-group">
												<input placeholder="min interval" class="form-control" id="minDelay" value="<?php echo $config['minInterval'];?>" type="number" name="min">
												<span class="input-group-addon">seconds</span>
											</div>
										</div>
										<div class="form-group">
											<label for="maxDelay">Maximum delay</label>
											<div class="input-group">
												<input placeholder="max interval" class="form-control" id="maxDelay" value="<?php echo $config['maxInterval'];?>" type="number" name="max">
												<span class="input-group-addon">seconds</span>
											</div>
										</div>
										<div class="form-group">
											<label for="postIdDelay">Post Id</label>
											<input placeholder="postId" class="form-control" id="postIdDelay" value="<?php if(isset($_GET['postId'])) echo $_GET['postId'];?>" type="text" name="postId">
										</div>
										<div class="row">
											<div class="col-6 col-xs-6"><button type="button" name="vote" value="up" class="half" onclick="voteWithAjax('up');">Upvote</button></div>
											<div class="col-6 col-xs-6"><button type="button" name="vote" value="down" class="half" onclick="voteWithAjax('down');">Downvote</button></div>
										</div>
									</form>
									<progress id="progressDelay" value="0" max="100"></progress>
									<div id="ResponseMessage"></div>
									<div id="ResponseCaptcha"></div>
								</div>
							</div>
						<?php } ?>
					</content>
				</article>
			</div>
		</div>
		
		
		<!-- jQuery, Tether, Bootstrap JS and own-->
		<script
			  src="https://code.jquery.com/jquery-3.1.1.min.js"
			  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
			  crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	    <script src="<?php echo $baseUrl;?>js/jQueryEmoji.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.1.1/ekko-lightbox.min.js" integrity="sha256-1odJPEl+KoMUaA1T7QNMGSSU/r5LCKCRC6SL8P0r2gY=" crossorigin="anonymous"></script>

		<script>
			//delayed voting
			var rekData;
			function voteWithAjax(type)
			{
				var id = $("#postIdDelay").val();
				var quantity = parseInt($("#quantityDelay").val());
				var minTime = parseFloat($("#minDelay").val());
				var maxTime = parseFloat($("#maxDelay").val());	
				var data = {"vote": type,
						   "id":id,
							"i": 1,
						   "quantity":quantity,
						   "minTime":minTime,
						   "maxTime":maxTime};
				
				$("#progressDelay").attr("max", quantity);
				$("#progressDelay").val(0);
				if (minTime > maxTime)
				{
					$("#ResponseMessage").html("min interval is greater than max interval");
					
				}
				else if (id == "")
				{
					$("#ResponseMessage").html("please enter a postId");
				}
				else if (isNaN(quantity))
				{
					$("#ResponseMessage").html("please enter a valid quantity of votes");
				}
				else 
				{
					voteRek(data);
				}
			}
			
			function voteRek(data)
			{
				$.ajax({
				  type: "POST",
				  url: "<?php echo $baseUrl;?>vote-ajax.php",
				  data: {"vote" : data["vote"],
						 "postId" : data["id"]},
				  success: function(result){
					  $("#progressDelay").val(data["i"]);
					  var response;
					  try 
					  {
						response = JSON.parse(result);
					  } catch (e) {
						//voteRek(data);
					  }
					  if (response["success"] != true)
					  {
						  $("#ResponseMessage").html(response["message"]);
						  if (response["captcha"] != null) {
							  rekData = data;
							  $("#ResponseCaptcha").append( "<div id='captchaWrapper_" + data["i"] + "'><form><p>Check all images with Coons on it (Coons look like <img style=\"height: 1.0em; width: unset;\" src=\"img/coon.png\">).</p><img src='" + response["captcha"]["image_url"] + "' style='width:100%'><div class='captchaWrapper'><input id='box_0' type='checkbox'><input id='box_1' type='checkbox'><input id='box_2' type='checkbox'><input id='box_3' type='checkbox'><input id='box_4' type='checkbox'><input id='box_5' type='checkbox'><input id='box_6' type='checkbox'><input id='box_7' type='checkbox'><input id='box_8' type='checkbox'></div><button type=\"button\" onclick=\"verifyAccount(" + data["i"] + ", '" + response["captcha"]["key"] + "' , '" + response["deviceUid"] + "');\">Verify</button></form></div>");
							  //verifyAccount(data["i"], response["captcha"]["key"], response["deviceUid"]);
						  }
					  }
					  else if (data["i"] < data["quantity"])
					  {
						  $("#ResponseMessage").html(data["i"] + " of " + data["quantity"]);
						  data["i"] += 1;
						  setTimeout(function(){voteRek(data)}, getRandomFloat(data["minTime"],data["maxTime"])*1000);
					  } else {
						  $("#ResponseMessage").html(data["quantity"] + " votes completed");
					  }
				  }
				});
			}
			
			function verifyAccount(id, key, deviceUid)
			{
				var solution = "";
				for (i=0; i<9; i++) {
					var box = $("#box_"+i);
					if (box.is(':checked') == true)
					{
						if (solution != "")
						{
							solution += "-" + i;
						}
						else 
						{
							solution = i;
						}

					}
				}
				console.log(solution);
				$.ajax({
				  type: "POST",
				  url: "<?php echo $baseUrl;?>vote-ajax.php?solution=" + solution + "&key="+key,
				  data: {"deviceUid" : deviceUid},
				  success: function(result){
					  var response = JSON.parse(result);
					  console.log("Verification = "+response["success"])
					  $("#captchaWrapper_"+id).remove();
					  voteRek(rekData);
				  }
				});
			}
			
			function getRandomFloat(min, max)
			{
			  return Math.floor(Math.random() * (max - min)) + min;
			}

		</script>
	</body>
</html>