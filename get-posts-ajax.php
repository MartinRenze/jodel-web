<?php
error_reporting(-1);

include 'php/jodel-web.php';

$location = new Location();
$location->setLat('52.5134288');
$location->setLng('13.2746394');
$location->setCityName('Berlin');

$accessToken;

if(!isset($_COOKIE["JodelId"]))
{
	$accessToken = createAccount();
	setcookie("JodelId", $accessToken);
}
else
{
	$accessToken = $db->real_escape_string($_COOKIE["JodelId"]);
}

$location = getLocationByAccessToken($accessToken);

isTokenFreshByAccessToken($location, $accessToken);

$result = $db->query("SELECT * FROM accounts WHERE access_token='" . $accessToken  . "'");

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
