<?php
error_reporting(-1);

include 'php/jodel-web.php';

$config = parse_ini_file('config/config.ini.php');

$location = new Location();
$location->setLat($config['default_lat']);
$location->setLng($config['default_lng']);
$location->setCityName($config['default_location']);

$accessToken;
$accessToken_forId1;
$deviceUid;


if(!isset($_COOKIE["JodelDeviceId"]))
{
	$deviceUid = createAccount();
	setcookie("JodelDeviceId", $deviceUid, time()+60*60*24*365*10);
	
}
else
{
	$deviceUid = $db->real_escape_string($_COOKIE["JodelDeviceId"]);
}

$location = getLocationByDeviceUid($deviceUid);
$newPositionStatus = $location->getCityName();
$accessToken = isTokenFreshByDeviceUid($location, $deviceUid);
//Acc is fresh. token and location is set

$accessToken_forId1 = isTokenFresh($location);



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
			$url = "/v2/posts/location/";
		}
	}

	if(isset($_GET['lastPostId']))
	{
		$lastPostId = htmlspecialchars($_GET['lastPostId']);
		
		$posts = getPosts($lastPostId, $accessToken, $url)['posts'];
		$loops = 29;
		$showCommentIcon = TRUE;
		?>
		<div class="nextPosts">
		<?php
		for($i = 0; $i<$loops; $i++)
		{
			if(isset($posts[$i]))
			{
				$lastPostId = $posts[$i]['post_id'];
				jodelToHtml($posts[$i], $view);	
			}
		}
		?>
		</div>
		<div class="lastPostId">
		<?php echo $lastPostId; ?>
		</div>
		<?php
	}
