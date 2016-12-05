<?php
error_reporting(-1);

include 'php/jodel-web.php';/*
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
Requests::register_autoloader();*/
$location = new Location();
$location->setLat('0.1');
$location->setLng('0.1');
$location->setCityName('Munich');

isTokenFresh($location);

$result = $db->query("SELECT * FROM accounts WHERE id='1'");

$accessToken;
$newPositionStatus;

if ($result->num_rows > 0)
{
	// output data of each row
	while($row = $result->fetch_assoc())
	{
		$accessToken = $row["access_token"];
	}
}
else
{
	echo "Error: 0 results";
}


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
