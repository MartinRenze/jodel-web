<?php

include 'php/jodel-web.php';

if(isset($_GET['postId']) && $_GET['vote'])
{
	header('Content-Type: application/json');
	$voteResult = $jodelAccountForKarma->votePostId($_GET['postId'], $_GET['vote']);
    echo json_encode($voteResult);
    die();
}

if(isset($_GET['solution']) && isset($_POST['deviceUid']))
{
	$jodelAccount = new JodelAccount($_POST['deviceUid']);
	$response = array("success" => $jodelAccount->verifyCaptcha());
	echo json_encode($response);
	die();
}
$userIsAdmin = isUserAdmin();
if(!$userIsAdmin)
{
	$userIsVoter = isUserVoter();
}
else
{
	$userIsVoter = false;
}

if(!$userIsVoter && !$userIsAdmin)
{
	error_log($_SERVER['REMOTE_ADDR']  . ' used a wrong password on vote-ajax.php');
	$response = array("message" => $_SERVER['REMOTE_ADDR']  . ' used a wrong password on vote-ajax.php',"success" => false);
	echo json_encode($response);
	die();
}
else
{
	if($userIsVoter)
	{
		$result = $db->query("SELECT user_token, remaining_votes FROM users WHERE user_token = '" . $_COOKIE['JodelVoterPassword'] . "'");
		if($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$remaining_votes = $row['remaining_votes'];
		}
		if($remaining_votes <= 0)
		{
			$message = 'This voter account run out of votes. For more information please contact info@jodelblue.com';
			$success = false;

			$response = array("success" => $success, "message" => $message);
			echo json_encode($response);
			die();
		}
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
					$message = "This account is not verified. Please verify this account first.";
					$captcha = $jodelAccount->getCaptcha();	

					$_GET['key'] = $captcha["key"];
					$_GET['deviceUid'] = $deviceUid;

					$success = false;
				}
				else
				{
					if($userIsVoter)
					{
						$remaining_votes = $remaining_votes - 1;
						$result = $db->query("UPDATE users 
	                                SET remaining_votes='" . $remaining_votes . "'
	                                WHERE user_token='" . $_COOKIE['JodelVoterPassword'] . "'");
						if($result === false)
						{
	               			error_log("Update remaining votes failed: (" . $db->errno . ") " . $db->error);
	               		}
               			$db->close();
               		}
					$jodelAccount->votePostId($_POST['postId'], $_POST['vote']);
					//Feedback
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