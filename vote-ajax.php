<?php
include 'php/jodel-web.php';

if((!isset($_GET['pw']) || $config['pw'] != $_GET['pw']) && !isUserAdmin())
{
	error_log($_SERVER['REMOTE_ADDR']  . ' used a wrong password on vote-ajax.php');
	$respone = array("message" => $_SERVER['REMOTE_ADDR']  . ' used a wrong password on vote-ajax.php',"success" => false);
	echo json_encode($response);
	die();
}
else
{

if(isset($_GET['solution']) && isset($_GET['key']) && isset($_POST['deviceUid']))
{
	$jodelAccount = new JodelAccount($_POST['deviceUid']);
	$response = array("success" => $jodelAccount->verifyCaptcha());
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
			
			$jodelAccount = new JodelAccount($deviceUid);

			if(!$jodelAccount->isAccountVerified())
			{
				$view = new View();
				$message = "This account is not verified. Please verify this account first.";
				$captcha = $view->getCaptcha($accessToken);
				$success = false;
			}
			else
			{
				$jodelAccount->votePostId($_POST['postId'], $_POST['vote']);
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
	$response = array("success" => $success, "message" => $message, "captcha" => $captcha, "deviceUid" => $deviceUid);
}
else 
{
	$response = array("success" => $success, "message" => $message);
}
}
echo json_encode($response);
?>