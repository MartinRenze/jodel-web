<?php
error_reporting(-1);
include 'php/DatabaseConnect.php';
include 'php/Requests/AbstractRequest.php';
include 'php/Requests/CreateUser.php';
include 'php/AccountData.php';
include 'php/Location.php';
include 'php/Requests/GetPosts.php';
include 'php/Requests/GetKarma.php';
include 'php/Requests/UpdateLocation.php';
include 'php/Requests/Upvote.php';
include 'php/Requests/Downvote.php';
include 'php/Requests/GetPostDetails.php';
include 'php/Requests/SendJodel.php';

require_once 'php/Requests/libary/Requests.php';
Requests::register_autoloader();

$lastPostId = '';

function isTokenFresh(Location $location) {
	$db = new DatabaseConnect();  
	$result = $db->query("SELECT * FROM accounts WHERE id='1'");
	
	if ($result->num_rows > 0)
	{
			// output data of each row
			while($row = $result->fetch_assoc()) {
					//$access_token = $row["access_token"];
					$expiration_date = $row["expiration_date"];
					$deviceUid = $row["device_uid"];
					$access_token = $row["access_token"];
			}
	}
	else
	{
			echo '0 results';
	}

	if($expiration_date <= time()) {
		$accountCreator = new CreateUser();
		$accountCreator->setAccessToken($access_token);//$accountData->getAccessToken());
		$accountCreator->setDeviceUid($deviceUid);
		$accountCreator->setLocation($location);
		$data = $accountCreator->execute();

		$access_token = (string)$data[0]['access_token'];
		$expiration_date = $data[0]['expiration_date'];
		$device_uid = (string)$data[1];
		
		$db = new DatabaseConnect();  
		$result = $db->query("UPDATE accounts 
								SET access_token='" . $access_token . "',
									expiration_date='" . $expiration_date . "'
								WHERE device_uid='" . $device_uid . "'");

		if($result === false){
				echo "Adding account failed: (" . $db->errno . ") " . $db->error;
		}	
	


	}

	
	return TRUE;
}

function getKarma($accessToken)
{
	$accountCreator = new GetKarma();
	$accountCreator->setAccessToken($accessToken);
	$data = $accountCreator->execute();
	
	return $data["karma"];
}

function registerAccount(Location $location) {
	$accountCreator = new CreateUser();
	$accountCreator->setLocation($location);
	$data = $accountCreator->execute();
	
	$access_token = (string)$data[0]['access_token'];
	$refresh_token = (string)$data[0]['refresh_token'];
	$token_type = (string)$data[0]['token_type'];
	$expires_in = $data[0]['expires_in'];
	$expiration_date = $data[0]['expiration_date'];
	$distinct_id = (string)$data[0]['distinct_id'];
	$device_uid = (string)$data[1];
	
	$db = new DatabaseConnect();  
	$result = $db->query("INSERT INTO accounts (access_token, refresh_token, token_type,
					expires_in, expiration_date, distinct_id, device_uid)
					VALUES ('" . $access_token . "','" . $refresh_token . "','" . $token_type .
					"','" .  $expires_in . "','" . $expiration_date . "','" . $distinct_id .
					"','" . $device_uid . "') ");

	$success = TRUE;
	if($result === false){
			$error = db_error();
			echo $error;
			echo "Adding account failed: (" . $result->errno . ") " . $result->error;
			$success = FALSE;
	}	
	
	return $success;
}

function getPosts($lastPostId, $accessToken, $url)
{	
	$accountCreator = new GetPosts();
	$accountCreator->setLastPostId($lastPostId);
	$accountCreator->setAccessToken($accessToken);
	$accountCreator->setUrl($url);
	$data = $accountCreator->execute();
	
	return $data;
}

function createAccount() {
	$location = new Location();
	$location->setLat(50.690399);
	$location->setLng(10.918175);
	$location->setCityName('Ilmenau');

	$account = registerAccount($location);
}
