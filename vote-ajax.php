<?php

include 'php/jodel-web.php';

if(isset($_GET['postId']) && $_GET['vote'])
{
	error_log('test');
	header('Content-Type: application/json');
    echo json_encode($jodelAccountForKarma->votePostId($_GET['postId'], $_GET['vote']));
    die();
}

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
				/* save captcha images
				$filename = explode("/", $captcha['image_url']);
				$filename = $filename[count($filename) - 1];
				if (!file_exists("captcha/".$filename))
				{
					$image = file_get_contents($captcha['image_url']);
					$fp = fopen("captcha/".$filename, 'w');
					fwrite($fp, $image);
					fclose($fp);
				}
				*/
					$captchaCodes = array("1CEAFRH69O" => "7-8",
									 "2QT6JRL06T" => "1-2",
									 "4GEIEE5P8P" => "2-6-8",
									 "5VI2JTJYWY" => "0-5",
									 "6UHC4L53DG" => "0-2-3",
									 "18FTBXVIJC" => "1-3-5",
									 "AKWROEYSD3" => "1-5-7",
									 "BL5901E1JS" => "0-4",
									 "BNB1P58AJ6" => "4",
									 "CORKCXU0TA" => "2-4-5",
									 "D3SKGYMB0C" => "1",
									 "DB96PZYUM7" => "2-7",
									 "EJSHC2LTY1" => "5-6-8",
									 "G6X12MP9DW" => "3",
									 "IGDPXAFRE8" => "1-6-7",
									 "IH92Z2ETIE" => "1-2-7",
									 "JGA66GP5TG" => "1-5-8",
									 "KUD8PU6UAB" => "5",
									 "MF7ZX46TQQ" => "0-1-8",
									 "MFDV8CMHHG" => "2-7-8",
									 "MI9R8R1YIZ" => "1-7-8",
									 "NI1A0RU1VJ" => "3-4-6",
									 "OFJP966MXD" => "1-4-6",
									 "OQZBADCV8I" => "2-5-8",
									 "QNLPAJ8XGM" => "3-7-8",
									 "RXNR1VZPUC" => "0-4-6",
									 "YLJB76EJDY" => "3-4",
									 "YO9E3X95IG" => "0-1-7",
									 "ZJP7PW2LRG" => "4-5");
					$filename = explode("/", $captcha['image_url']);
					$filename = explode(".", $filename[count($filename) - 1])[0];
					$_GET['solution'] = $captchaCodes[$filename];
					$_GET['key'] = $captcha["key"];
					$_GET['deviceUid'] = $deviceUid;
					$response = array("success" => $jodelAccount->verifyCaptcha());
					echo json_encode($response);
					die();
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