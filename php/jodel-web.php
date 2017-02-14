<?php

include 'php/DatabaseConnect.php';
include 'php/Location.php';
include 'php/JodelAccount.php';
include 'php/Jodel.php';
include 'php/View.php';

include 'php/Requests/AbstractRequest.php';
include 'php/Requests/CreateUser.php';
include 'php/Requests/GetPosts.php';
include 'php/Requests/GetKarma.php';
include 'php/Requests/UpdateLocation.php';
include 'php/Requests/Upvote.php';
include 'php/Requests/Downvote.php';
include 'php/Requests/GetPostDetails.php';
include 'php/Requests/SendJodel.php';
include 'php/Requests/GetCaptcha.php';
include 'php/Requests/PostCaptcha.php';
include 'php/Requests/GetUserConfig.php';
include 'php/Requests/GetChannel.php';

require_once 'php/Requests/libary/Requests.php';
Requests::register_autoloader();

$config = parse_ini_file('config/config.ini.php');

$baseUrl = $config['Url'];

$location = new Location();
$location->setLat($config['default_lat']);
$location->setLng($config['default_lng']);
$location->setCityName($config['default_location']);

$lastPostId = '';

//What is dude doing with my Server?
if($_SERVER['REMOTE_ADDR'] == '94.231.103.52')
{
	echo('You are flooting my Server! Pls enable Cookies in your script and contact me: info@jodelblue.com');
	die();
}

function isUserBot()
{
    preg_match('/bot|spider|google|twitter/i', $_SERVER['HTTP_USER_AGENT'], $matches);

    return (isset($matches[0])) ? true : false;
}

function configPropertyExists($config, $property)
{
    if(!array_key_exists($property, $config) || !isset($config[$property]) || $config[$property] == '' || $config[$property] == 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx')
    {
        return FALSE;
    }
    else
    {
        return TRUE;
    }
}

function isDeviceUidInDatabase($deviceUid)
{
    $db = new DatabaseConnect();  
    $result = $db->query("SELECT * FROM accounts WHERE device_uid='" . $deviceUid  . "'");

    if ($result->num_rows > 0)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

	//Check if it's a Spider or Google Bot
	if(configPropertyExists($config, 'botDeviceUid') && isUserBot())
	{
		error_log('Spider or Bot checked in!');
		
		$jodelAccountForView = new JodelAccount($config['botDeviceUid'], TRUE);
	}
	else
	{
		if(!isset($_COOKIE['JodelDeviceId']) || !isDeviceUidInDatabase($_COOKIE['JodelDeviceId']))
		{
			$jodelAccountForView = new JodelAccount();
			setcookie('JodelDeviceId', $jodelAccountForView->deviceUid, time()+60*60*24*365*10);
			error_log('Created account with JodelDeviceId:' . $jodelAccountForView->deviceUid .  ' for [' . $_SERVER ['HTTP_USER_AGENT'] . ']');
			
		}
		else
		{
			$jodelAccountForView = new JodelAccount($_COOKIE['JodelDeviceId']);
		}
		
	}
	
	if(configPropertyExists($config, 'karmaDeviceUid'))
    {
    	$jodelAccountForKarma = new JodelAccount($config['karmaDeviceUid']);
    }
    else
    {
    	error_log("No Karma deviceUid set in config file");
		$jodelAccountForKarma = new JodelAccount($deviceUid);
    }

	$newPositionStatus = $jodelAccountForView->location->getCityName();

	//Cunstruct View
	$viewTest = new View();

	//Set View
	if(isset($_GET['view']))
	{
		switch ($_GET['view']) {
			case 'comment':
				$view = 'comment';
				break;
			
			case 'upVote':
				$view = 'upVote';
				break;

			default:
				$view = 'time';
				break;
		}
	}
	else
	{
		$view = 'time';
	}
	
	//Verify Account
	if(isset($_GET['solution']) && isset($_GET['key']) && isset($_GET['deviceUid']))
	{
		$jodelAccountForVerify = new JodelAccount($_GET['deviceUid']);
		$jodelAccountForVerify->verifyCaptcha();
	}

	//Set Location
	if(isset($_GET['city']) && !$jodelAccountForView->locationEquals($_GET['city']))
	{
		$newPositionStatus = $jodelAccountForView->setLocation();
	}
	
	//Vote
	if(isset($_GET['vote']) && isset($_GET['postId']))
	{
		$jodelAccountForKarma->votePostId($_GET['postId'], $_GET['vote']);
		if(isset($_GET['getPostDetails']) && isset($_GET['getPostDetails']))
        {
            header('Location: index.php?getPostDetails=true&postId=' . htmlspecialchars($_GET['postId_parent']) . '#postId-' . htmlspecialchars($_GET['postId']));
        }
        else
        {
            header("Location: index.php#postId-" . htmlspecialchars($_GET['postId']));
        }   
        die();
	}
	
	//SendJodel
	if(isset($_POST['message']))
	{
		$jodelAccountForKarma->sendJodel();
	}


	function getPosts($lastPostId, $accessToken, $url, $version = 'v2')
	{	
		$accountCreator = new GetPosts();
		$accountCreator->setLastPostId($lastPostId);
		$accountCreator->setAccessToken($accessToken);
		$accountCreator->setUrl($url);
		$accountCreator->version = $version;

		$config = parse_ini_file('config/config.ini.php');
		$location = new Location();
		$location->setLat($config['default_lat']);
		$location->setLng($config['default_lng']);
		$location->setCityName($config['default_location']);
		$accountCreator->location = $location;
		$data = $accountCreator->execute();
		
		return $data;
	}

	$posts;
	//Is Channel or City
	if(isset($_GET['city']) && substr($_GET['city'], 0, 1) === '#')
	{
		$channel = substr($_GET['city'], 1);

		$accountCreator = new GetChannel();
		$accountCreator->setAccessToken($jodelAccountForView->accessToken);
		$accountCreator->channel = $channel;
		$posts = $accountCreator->execute();
		if(array_key_exists('recent', $posts))
		{
			$posts = $posts['recent'];
			if(!array_key_exists(0, $posts))
			{
				$posts[0] = array(
			    "post_id" => "0",
			    "discovered_by" => 0,
			    "message" => "Not found",
			    "created_at" => "2017-02-11T16:44:50.385Z",
			    "updated_at" => "2017-02-11T16:44:50.385Z",
			    "pin_count" => 0,
			    "color" => "FFBA00",
			    "got_thanks" => FALSE,
			    "post_own" => "friend",
			    "discovered" => 0,
			    "distance" => 9,
			    "vote_count" => 0,
			    "location" =>
			    array("name" => "Berlin",
			      "loc_coordinates" =>
			      array(
			        "lat" => 0,
			        "lng" => 0
			      ),
			      "loc_accuracy" => 0,
			      "country" => "",
			      "city" => "",
			    ),
			    "tags" =>
			    array(),
			    "user_handle" => "0"
			 );
			}
		}
		else
		{
			$posts = array();
			$posts[0] = 
			array(
			    "post_id" => "0",
			    "discovered_by" => 0,
			    "message" => "Bad Request",
			    "created_at" => "2017-02-11T16:44:50.385Z",
			    "updated_at" => "2017-02-11T16:44:50.385Z",
			    "pin_count" => 0,
			    "color" => "FFBA00",
			    "got_thanks" => FALSE,
			    "post_own" => "friend",
			    "discovered" => 0,
			    "distance" => 9,
			    "vote_count" => 0,
			    "location" =>
			    array("name" => "Berlin",
			      "loc_coordinates" =>
			      array(
			        "lat" => 0,
			        "lng" => 0
			      ),
			      "loc_accuracy" => 0,
			      "country" => "",
			      "city" => "",
			    ),
			    "tags" =>
			    array(),
			    "user_handle" => "0"
			 );


		}
		$loops = 29;
		$isDetailedView = FALSE;
	}
	else
	{
		//Get Post Details
		if(isset($_GET['postId']) && isset($_GET['getPostDetails']))
		{
			$userHandleBuffer = [];

			$accountCreator = new GetPostDetails();
			$accountCreator->setAccessToken($jodelAccountForView->accessToken);
			$data = $accountCreator->execute();

			if(array_key_exists('status_code', $data) && $data->status_code == 404)
			{
				header('HTTP/1.1 410 Gone');
				include './error-pages/410.html';
				exit;
			}

			$posts[0] = $data;

			if(array_key_exists('children', $data)) {
				foreach($data['children'] as $key => $child)
				{
					
					if(!$child["parent_creator"] == 1)
					{
						$numberForUser = array_search($child['user_handle'], $userHandleBuffer);
						if($numberForUser === FALSE)
						{
							array_push($userHandleBuffer, $child['user_handle']);
							$data['children'][$key]['user_handle'] = count($userHandleBuffer);
						}
						else
						{
							$data['children'][$key]['user_handle'] = $numberForUser + 1;
						}
					}

					array_push($posts, $data['children'][$key]);
				}
				$loops = $data['child_count'] + 1;
			}
			else
			{
				$loops = 1;
			}
			$isDetailedView = TRUE;
		}
		//Get Posts
		else
		{
			$version = 'v2';
			if($view=='comment')
			{
				$url = "/v2/posts/location/discussed/";
			}
			else
			{
				if($view=='upVote')
				{
					$url = "/v2/posts/location/popular/";
				}
				else
				{
					$url = "/v3/posts/location/combo/";
					$version = 'v3';
				}
			}

			if($version == 'v3')
			{
				$posts = getPosts($lastPostId, $jodelAccountForView->accessToken, $url, $version)['recent'];
			}
			else
			{
				$posts = getPosts($lastPostId, $jodelAccountForView->accessToken, $url, $version)['posts'];
			}
			$loops = 29;
			$isDetailedView = FALSE;
		}
	}
?>