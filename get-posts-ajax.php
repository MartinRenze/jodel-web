<?php
include 'php/jodel-web.php';

if(isset($_GET['lastPostId']))
{
	$view->lastPostId = htmlspecialchars($_GET['lastPostId']);
	
	$posts = $view->getPosts($jodelAccountForView);
	?>
	<div class="nextPosts">
	<?php
	if(!(gettype($posts) == 'boolean'))
	{
		foreach($posts as $post)
		{
			$view->lastPostId = $post['post_id'];
			$view->jodelToHtml($post);	
		}
	}
	?>
	</div>
	<div class="lastPostId">
	<?php echo $view->lastPostId; ?>
	</div>
	<?php
}
