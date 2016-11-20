<?php

include 'php/jodel-web.php';

	$location = new Location();
	$location->setLat('0.1');
	$location->setLng('0.1');
	$location->setCityName('Munich');

	isTokenFresh($location);

	$result = $db->query("SELECT * FROM accounts WHERE id='1'");
	
	$accessToken;
	$newPostionStatus;
	
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
	
	
	//createAccount();
	
	//Set Location
	if(isset($_GET['city'])) {
		
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $_GET['city'] . '&key=AIzaSyCwhnja-or07012HqrhPW7prHEDuSvFT4w';
		$result = Requests::post($url);
		if(json_decode($result->body, true)['status'] == 'ZERO_RESULTS')
		{
			$newPostionStatus = "0 results";
		}
		else
		{
			$location = new Location();
			$location->setLat(json_decode($result->body, true)['results']['0']['geometry']['location']['lat']);
			$location->setLng(json_decode($result->body, true)['results']['0']['geometry']['location']['lng']);
			$location->setCityName($_GET['city']);
			$accountCreator = new UpdateLocation();
			$accountCreator->setLocation($location);
			$accountCreator->setAccessToken($accessToken);
			$data = $accountCreator->execute();
		}
	}
	
	//Vote
	if(isset($_GET['vote']) && isset($_GET['postID'])) {
		if($_GET['vote'] == "up") {
			$accountCreator = new Upvote();
		}
		else if($_GET['vote'] == "down") {
			$accountCreator = new Downvote();
		}
		$accountCreator->setAccessToken($accessToken);
		$data = $accountCreator->execute();

		header("Location: index.php#postId-" . $_GET['postID']);
		die();
	}
	
	
	//SendJodel
	if(isset($_POST['message'])) {
		$ancestor;
		if(isset($_POST['ancestor']))
		{
			$ancestor = $_POST['ancestor'];
		}
		
		$location = new Location();
		$location->setLat('0.1');
		$location->setLng('0.1');
		$location->setCityName('Munich');
		$accountCreator = new SendJodel();
		$accountCreator->setLocation($location);
		$accountCreator->setAncestor($ancestor);
		$accountCreator->setAccessToken($accessToken);
		$data = $accountCreator->execute();
	}
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<title>Jodel WebClient - </title>
		
		<meta charset="utf8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<meta name="description" content=""/>
		<meta name="keywords" content=""/>
		
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link href="css/least.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="style.css" type="text/css" />	
		
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">
		
	</head>
	
	<body>
		
		<header class="mainHeader">
			<a href="index.php">
				<h1>Jodel WebClient</h1>
			</a>
			<div class="clear"></div>
		</header>
		
		<div class="mainContent">
			<div class="content">
				<article class="topContent">

					<content id="posts">
						<?php
							$posts;
							
							//Get Post Details
							if(isset($_GET['postID']) && isset($_GET['getPostDetails'])) {
								//Header Nav in Comment View
								?>
								<a href="index.php?postID=<?php echo $posts[$i]["post_id"];?>">
									<i class="fa fa-up-left fa-3x"></i>Test
								</a>
								<?php


								$accountCreator = new GetPostDetails();
								$accountCreator->setAccessToken($accessToken);
								$data = $accountCreator->execute();
								
								$posts[0] = $data;
								if(isset($data['children'])) {
									foreach($data['children'] as $child) {
										array_push($posts, $child);
									}
									$loops = $data['child_count'] + 1;
								}
								else $loops = 1;
								$showCommentIcon = FALSE;
							}
							//Get Posts
							else {
								$posts = getPosts($lastPostId, $accessToken)['posts'];
								$loops = 29;
								$showCommentIcon = TRUE;
							}
							

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
									echo nl2br($posts[$i]["message"]);
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
												<a href="index.php?getPostDetails=true&postID=<?php echo $posts[$i]["post_id"];?>">
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
						

						
						<?php }
						} ?>
					</content>
							
				</article>
				<?php if(!isset($_GET['postID']) && !isset($_GET['getPostDetails'])) { ?>
				<p id="loading">
					<img src="images/loading.gif" alt="Loading…" />
				</p>
				<?php } ?>
			</div>
			
			<aside class="topSidebar">
				<article>
					<h3>Position</h3>
					<form method="get">
						<input type="text" id="city" name="city" placeholder="<?php if(isset($newPositionStatus)) echo $newPositionStatus; else echo $posts[0]["location"]["name"]; ?>" required>

						<input type="submit" value="Set Location" /> 
					</form>
					
				</article>
				

			</aside>
				

			<aside class="topSidebar">
				<article>
					<h2>Karma</h2>
					<?php echo getKarma($accessToken); ?>
				</article>
			</aside>
			
			<aside class="topSidebar">
				<article>
					<?php if(isset($_GET['postID']) && isset($_GET['getPostDetails'])) { ?>
					<h2>Comment on Jodel</h2>
					<form method="POST">				
							<input type="hidden" name="ancestor" value="<?php echo $_GET['postID'];?>" />
							<textarea id="message" name="message" placeholder="Send a comment on a Jodel to all students within 10km" required></textarea> 
						<br />
						<input type="submit" value="SEND" /> 
					</form>
						<?php } else { ?>
					<h2>New Jodel</h2>
					<form method="POST">
						<textarea id="message" name="message" placeholder="Send a Jodel to all students within 10km" required></textarea> 
						<br />
						<input type="submit" value="SEND" /> 
					</form>
					<?php } ?>

				</article>
			</aside>
			
		</div>
		
		<footer class="mainFooter">
			<p>
				<span class="float-right-footer"><a href="./impressum.html">Impressum</a></span>
			</p>
		</footer>
		
		<?php if(!isset($_GET['postID']) && !isset($_GET['getPostDetails'])) { ?>
		<!-- jQuery library -->
		<script src="js/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			$(document).ready(function() {
				var win = $(window);
				var lastPostId = "<?php echo $lastPostId; ?>";
				var old_lastPostId = "";
				var morePostsAvailable = true;
				// Each time the user scrolls
				win.scroll(function() {
					// End of the document reached?
					if (($(document).height() - win.height() == win.scrollTop()) && morePostsAvailable) {
						$('#loading').show();

						
						
						$.ajax({
							url: 'get-posts-ajax.php?lastPostId=' + lastPostId,
							dataType: 'html',
							async: true,
							success: function(html) {
								var div = document.createElement('div');
								div.innerHTML = html;
								var elements = div.childNodes;
								old_lastPostId = lastPostId;
								lastPostId = elements[3].textContent;
								lastPostId = lastPostId.replace(/\s+/g, '');
								//alert('Neu: ' + lastPostId + " Alt: " + old_lastPostId);
								if(lastPostId == old_lastPostId) {
									
									//morePostsAvailable = false;
								}
								else {
									//alert(elements[3].textContent);
									$('#posts').append(elements[1].innerHTML);
								}
								$('#loading').hide();
							}
						});
					}
				});
			});	
		</script>
		<?php } ?>
	</body>
</html>

