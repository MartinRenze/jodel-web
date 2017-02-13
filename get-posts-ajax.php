<?php

include 'php/jodel-web.php';

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
		
		$posts = $viewTest->getPosts($lastPostId, $jodelAccountForView->accessToken, $url)['posts'];
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
				$viewTest->jodelToHtml($posts[$i], $view);	
			}
		}
		?>
		</div>
		<div class="lastPostId">
		<?php echo $lastPostId; ?>
		</div>
		<?php
	}
