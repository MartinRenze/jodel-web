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
//What is dude doing with my Server?
if($_SERVER['REMOTE_ADDR'] == '94.231.103.52')
{
	echo('You are flooting my Server! Pls enable Cookies in your script and contact me: info@jodelblue.com');
	die();
}

function user_log($msg)
{
    $log  = $msg . PHP_EOL;
    file_put_contents(realpath(__DIR__ . '/..') . '/logs/user_log-' . date("j.n.Y") . '.txt', $log, FILE_APPEND);
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
function isUserAdmin() {
	global $config;
	if (isset($_COOKIE['JodelAdminPassword']) && configPropertyExists($config, 'pw') && $config['pw'] == $_COOKIE['JodelAdminPassword'])
	{
		return TRUE;
	}
	else
	{
		return FALSE;
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
		user_log('Spider or Bot checked in!');
		
		$jodelAccountForView = new JodelAccount($config['botDeviceUid'], TRUE);
	}
	else
	{
		if(!isset($_COOKIE['JodelDeviceId']) || !isDeviceUidInDatabase($_COOKIE['JodelDeviceId']))
		{
			$jodelAccountForView = new JodelAccount();
			setcookie('JodelDeviceId', $jodelAccountForView->deviceUid, time()+60*60*24*365*10);
			user_log('Created account with JodelDeviceId:' . $jodelAccountForView->deviceUid .  ' for [' . $_SERVER ['HTTP_USER_AGENT'] . ']');
			
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

	/*
	 * Cunstruct View
	 */

	$hashtag = '';

	if(isset($_GET['search']))
	{

		user_log('User with JodelDeviceId:' . $jodelAccountForView->deviceUid .  ' [' . $_SERVER['REMOTE_ADDR'] . '][' . $_SERVER ['HTTP_USER_AGENT'] . '] searched for ' . $_GET['search']);

		if(substr($_GET['search'], 0, 1) === "#")
		{
			if(strrpos($_GET['search'], ' ') == NULL)
			{
				$hashtag = substr($_GET['search'], 1);
			}
			else
			{
				$hashtag = substr($_GET['search'], 1, strrpos($_GET['search'], ' '));

				$city = substr($_GET['search'],
						strrpos($_GET['search'], ' ') + 1,
						strlen($_GET['search']));

				if($city != NULL && $city != '')
				{
					$_GET['city'] = $city;
				}
			}
		}
		else
		{
			$_GET['city'] = $_GET['search'];
		}
	}
	$hashtag = trim($hashtag);

	if($hashtag == '')
	{
		if(isset($_GET['hashtag']))
		{
			$hashtag = $_GET['hashtag'];
		}
		else
		{
			$hashtag = '#all';
		}
	}
	
	//Set Location
	if(isset($_GET['city']) && !$jodelAccountForView->locationEquals($_GET['city']))
	{
		$cityName = $jodelAccountForView->setLocation();
	}
	else
	{
		$cityName = $jodelAccountForView->location->getCityName();
	}


	
	if(isset($_GET['view']))
	{
		switch ($_GET['view']) {
			case 'discussed':
				$view = 'discussed';
				break;
			
			case 'popular':
				$view = 'popular';
				break;

			default:
				$view = 'combo';
				break;
		}
	}
	else
	{
		$view = 'combo';
	}

	if(isset($_GET['postId']))
	{
		$view = new View($baseUrl, 'DE', $cityName, $hashtag, $view, $_GET['postId']);
	}
	else
	{
		$view = new View($baseUrl, 'DE', $cityName, $hashtag, $view);
	}
	
	$newPositionStatus = '';
	if($hashtag != '#all')
	{
		$newPositionStatus = '#' . $hashtag . ' ';
	}
	$newPositionStatus .= $cityName;

	//Verify Account
	if(isset($_GET['solution']) && isset($_GET['key']) && isset($_GET['deviceUid']))
	{
		$jodelAccountForVerify = new JodelAccount($_GET['deviceUid']);
		$jodelAccountForVerify->verifyCaptcha();
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
		$jodelAccountForKarma->sendJodel($jodelAccountForView->location, $view);
	}
?>