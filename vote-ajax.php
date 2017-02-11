<?php

$config = parse_ini_file('config/config.ini.php');
if(!isset($_GET['pw']) || $config['pw'] != $_GET['pw'])
{
	error_log($_SERVER['REMOTE_ADDR']  . ' used a wrong password on vote-ajax.php');
	$respone = array("message" => $_SERVER['REMOTE_ADDR']  . ' used a wrong password on vote-ajax.php',"success" => false);
	echo json_encode($response);
	
	die();
}

include 'php/jodel-web.php';

if(isset($_GET['solution']) && isset($_GET['key']) && isset($_POST["accessToken"]))
{
	$response = array("success" => verifyCaptcha($_POST["accessToken"]));
	echo json_encode($response);
	die();
}

$message = "";
$success = true;
$token = "";
	if(isset($_POST['vote']) && isset($_POST['postId']))
	{
		$i = 0;
		$result = $db->query("SELECT access_token, device_uid FROM accounts WHERE device_uid NOT IN (SELECT device_uid FROM votes WHERE postId = '" . $_POST['postId'] . "')");

		if($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$accessToken = $row['access_token'];
			$deviceUid = $row['device_uid'];
			
			if(!isAccountVerified($accessToken))
			{
				$message = "This account is not verified. Please verify this account first.";
				$captcha = getCaptcha($accessToken);
				$token = $accessToken;
				$success = false;
			}
			else {
				
				$location = getLocationByAccessToken($accessToken);

				$accessToken = isTokenFreshByAccessToken($location, $accessToken);


				if($_POST['vote'] == "up") {
					$accountCreator = new Upvote();
				}
				else if($_POST['vote'] == "down") {
					$accountCreator = new Downvote();
				}

				$accountCreator->setAccessToken($accessToken);
				$accountCreator->postId = $_POST['postId'];
				$data = $accountCreator->execute();
				if(array_key_exists('post', $data))
				{
					addVoteWithPostIdAndTypeToDeviceUid($_POST['postId'], $_POST['vote'], $deviceUid);
				}
			}
		}
		else
		{
			$message = 'There is no account available for this jodel. Please create at least one new account to vote this jodel.';
			$success = false;
		}
	}

if (isset($captcha))
{
	$response = array("success" => $success, "message" => $message, "captcha" => $captcha, "accessToken" => $token);
}
else 
{
	$response = array("success" => $success, "message" => $message);
}
echo json_encode($response);
?>