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

function getPosts($lastPostId, $url) {
	$db = new DatabaseConnect();
	if ($db->connect_errno) {
		echo 'Sorry, die Verbindung zu unserem superfetten endgeilen 
					Server ist hops gegangen. Wegen '. $db -> connect_error;
	}
	
	$result = $db->query("SELECT * FROM accounts WHERE id='1'");
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$access_token = $row["access_token"];
		}
	}
	else
	{
		echo "0 results";
	}
	
	$accountCreator = new GetPosts();
	$accountCreator->setLastPostID($lastPostId);
	$accountCreator->setUrl($url);
	$accountCreator->setAccessToken($access_token);
	$data = $accountCreator->execute();

	return $data;
}
	$posts;

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

	if(isset($_GET['lastPostId'])) {
	
		$lastPostId = htmlspecialchars($_GET['lastPostId']);
		
		$posts = getPosts($lastPostId, $url)['posts'];
		$loops = 29;
		$showCommentIcon = TRUE;
		?>
		<div class="nextPosts">
		<?php
		for($i = 0; $i<$loops; $i++) {
		
			if(isset($posts[$i])) {
				$lastPostId = $posts[$i]['post_id'];

				
				$now = new DateTime();
				$d = new DateTime($posts[$i]["created_at"]);
				
				
				//Time to time difference
							$timediff = $now->diff($d);

							$timediff_inSeconds = (string)$timediff->format('%s');
							$timediff_inMinutes = (string)$timediff->format('%i');
							$timediff_inHours = (string)$timediff->format('%h');
							$timediff_inDays = (string)$timediff->format('%d');
							$timediff_inMonth = (string)$timediff->format('%m');
							if($timediff_inMonth!=0) {
									$timediff = $timediff_inMonth . "m";
							}
							else
							{
								if($timediff_inDays!=0)
								{
									$timediff = $timediff_inDays . "d";
								}
								else
								{
									if($timediff_inHours!=0)
									{
										$timediff = $timediff_inHours . "h";
									}
									else
									{
										if($timediff_inMinutes!=0)
										{
											$timediff = $timediff_inMinutes . "m";
										}
										else
										{
											$timediff = $timediff_inSeconds . "s";
										}
									}
								}
							}
						?>

				<article id ="postId-<?php echo $posts[$i]["post_id"]; ?>" class="jodel" style="background-color: #<?php echo $posts[$i]["color"];?>;">
					<content>
						<?php 
						if(isset($posts[$i]["image_url"])) {
							echo '<img src="' . $posts[$i]["image_url"] . '">';
						}
						else {
							echo str_replace('  ', ' &nbsp;', nl2br(htmlspecialchars($posts[$i]["message"])));
						}
						?>
					</content>
					<aside>
						<a href="index.php?vote=up&postID=<?php echo $posts[$i]["post_id"];?>">
							<i class="fa fa-angle-up fa-3x"></i>
						</a>	
							<br />
						<?php echo $posts[$i]["vote_count"];?><br />
						<a href="index.php?vote=down&postID=<?php echo $posts[$i]["post_id"];?>">
							<i class="fa fa-angle-down fa-3x"></i>
						</a>
					</aside>

					<footer>
						<table>
							<tr>
								<td class="time">
									<span data-tooltip="Time">
										<i class="fa fa-clock-o"></i>
										<?php echo $timediff;?>
									</span> 
								</td>
								<td class="comments">
									<?php if($showCommentIcon) {?>
									<span data-tooltip="Comments">
										<a href="index.php?getPostDetails=true&view=<?php echo $view;?>&postID=<?php echo $posts[$i]["post_id"];?>">
											<i class="fa fa-commenting-o"></i>
											<?php if(array_key_exists("child_count", $posts[$i])) {
														echo $posts[$i]["child_count"];
													} else echo "0";
											?>
											</a>
									</span>
									<?php } ?>
								</td>
								<td class="distance">
									<span data-tooltip="Distance">
										<i class="fa fa-map-marker"></i>
										<?php echo $posts[$i]["distance"];?> km
									</span>
								</td>
							</tr>
						</table>
					</footer>
				</article>



				<?php 
			}
		}
		?>
		</div>
		<div class="lastPostId">
		<?php echo $lastPostId; ?>
		</div>
		<?php
	}
