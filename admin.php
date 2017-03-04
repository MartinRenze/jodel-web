<?php

include 'php/jodel-web.php';

if((!isset($_GET['pw']) || $config['pw'] != $_GET['pw']) && !isUserAdmin())
{
	error_log($_SERVER['REMOTE_ADDR']  . ' used a wrong password on admin.php');
	die();
}
else if (isset($_GET['pw']))
{
	setcookie('JodelAdminPassword', $_GET['pw'], time()+60*60*24*365*10);
	error_log('admin password saved for [' . $_SERVER ['HTTP_USER_AGENT'] . ']');
}



if(isset($_POST['createAccount']) && $_POST['createAccount'])
{
	$newJodelAccount = new JodelAccount();
}


//Vote
if(isset($_POST['vote']) && isset($_POST['postId']) && isset($_POST['quantity']))
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
		echo "Error: 0 results";
	}
}


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
						<a href="./" class="spinnable">
						
						JodelBlue <i class="fa fa-refresh fa-1x"></i></a>
					</h1>					
				</div>
			</nav>
		</header>
		
		<div class="mainContent container">		
			<div class="content row">
				<article class="topContent col-sm-8">

					<content id="posts" class="adminpanel">
						<h2>account management</h2>
						<form method="post">
							<div>
							<?php
								$result = $db->query("SELECT COUNT(*) FROM accounts");
								echo $result->fetch_row()[0];
							?>
							accounts in the database</div>
							<button type="submit" name="createAccount" value="TRUE">Create new Account</button>
						</form>
						<hr>
						<h2>Voting</h2>
							<input placeholder="quantity" id="quantityDelay" type="number" name="quantity"><br>
							<input placeholder="min interval" id="minDelay" value="<?php echo $config["minInterval"]?>" type="text" name="min"><br>
							<input placeholder="max interval" id="maxDelay" value="<?php echo $config["maxInterval"]?>" type="text" name="max"><br>
							<input placeholder="postId" id="postIdDelay" value="<?php echo $_GET["postId"]?>" type="text" name="postId"><br>
							<button name="vote" value="up" class="half" onClick="vote('up');">Upvote</button>
							<button name="vote" value="down" class="half" onClick="vote('down');">Downvote</button><br>
							<progress id="progressDelay" value="0" max="100"></progress>
							<div id="ResponseMessage"></div>
							<div id="ResponseCaptcha"></div>	
					</content>
				</article>
			</div>
		</div>
		
		
		<!-- jQuery, Tether, Bootstrap JS and own-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>

		<script>
			//delayed voting
			var rekData;
			function vote(type)
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
				  url: "<?php echo $baseUrl;?>vote-ajax.php?pw=<?php echo $_GET["pw"]?>",
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
							  $("#ResponseCaptcha").append( "<div id='captchaWrapper_" + data["i"] + "'><img src='" + response["captcha"]["image_url"] + "' style='width:100%'><div class='captchaWrapper'><input id='box_0' type='checkbox'><input id='box_1' type='checkbox'><input id='box_2' type='checkbox'><input id='box_3' type='checkbox'><input id='box_4' type='checkbox'><input id='box_5' type='checkbox'><input id='box_6' type='checkbox'><input id='box_7' type='checkbox'><input id='box_8' type='checkbox'></div><button onClick=\"verifyAccount(" + data["i"] + ", '" + response["captcha"]["key"] + "' , '" + response["deviceUid"] + "');\">Verify</button></div>");
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
				  url: "<?php echo $baseUrl;?>vote-ajax.php?pw=<?php echo $_GET["pw"]?>&solution=" + solution + "&key="+key,
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