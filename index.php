<?php
error_reporting(-1);
include 'php/jodel-web.php';

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
			$newPositionStatus = $row['name'];
		}
	}
	else
	{
		echo "Error: 0 results";
	}
	
	
	//createAccount();
	
	//Set Location
	if(isset($_GET['city'])) {
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . htmlspecialchars($_GET['city']) . '&key=AIzaSyCwhnja-or07012HqrhPW7prHEDuSvFT4w';
		$result = Requests::post($url);
		if(json_decode($result->body, true)['status'] == 'ZERO_RESULTS' || json_decode($result->body, true)['status'] == 'INVALID_REQUEST')
		{
			$newPositionStatus = "0 results";
		}
		else
		{
			$name = json_decode($result->body, true)['results']['0']['address_components']['0']['long_name'];
			$lat = json_decode($result->body, true)['results']['0']['geometry']['location']['lat'];
			$lng = json_decode($result->body, true)['results']['0']['geometry']['location']['lng'];

			$location = new Location();
			$location->setLat($lat);
			$location->setLng($lng);
			$location->setCityName($name);
			$accountCreator = new UpdateLocation();
			$accountCreator->setLocation($location);
			$accountCreator->setAccessToken($accessToken);
			$data = $accountCreator->execute();

			//safe location to db
			if($data == "Success")
			{
				$result = $db->query("UPDATE accounts 
						SET name='" . $name . "',
							lat='" . $lat . "',
							lng='" . $lng . "'
						WHERE id='1'");

				if($result === false)
				{
						echo "Updating location failed: (" . $db->errno . ") " . $db->error;
				}
				else
				{
					$newPositionStatus = $name;
				}
			}
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

		header("Location: index.php#postId-" . htmlspecialchars($_GET['postID']));
		die();
	}
	
	
	//SendJodel
	if(isset($_POST['message'])) {
		$accountCreator = new SendJodel();

		if(isset($_POST['ancestor']))
		{
			$ancestor = $_POST['ancestor'];
			$accountCreator->ancestor = $ancestor;
		}
		if(isset($_POST['color']))
		{
			$color = $_POST['color'];
			switch ($color) {
				case '8ABDB0':
					$color = '8ABDB0';
					break;
				case '9EC41C':
					$color = '9EC41C';
					break;
				case '06A3CB':
					$color = '06A3CB';
					break;
				case 'FFBA00':
					$color = 'FFBA00';
					break;
				case 'DD5F5F':
					$color = 'DD5F5F';
					break;
				case 'FF9908':
					$color = 'FF9908';
					break;
				
				default:
					$color = '8ABDB0';
					break;
			}
			$accountCreator->color = $color;
			echo "Setting color:" . $color;
		}
		
		$location = new Location();
		$location->setLat('0.1');
		$location->setLng('0.1');
		$location->setCityName('Munich');
		
		$accountCreator->location = $location;
		
		$accountCreator->setAccessToken($accessToken);
		$data = $accountCreator->execute();
	}
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<title>JodelBlue WebClient - </title>
		
		<meta charset="utf8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		
		<meta name="description" content=""/>
		<meta name="keywords" content=""/>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="style.css" type="text/css">
		
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">	
	</head>
	
	<body>
		<header>
			<nav class="navbar navbar-full navbar-dark navbar-fixed-top">
				<div class="container">					
						<?php
							if(isset($_GET['postID']) && isset($_GET['getPostDetails']))
							{
								echo '<a id="comment-back" href="index.php?view=' . $view . '#postId-' . htmlspecialchars($_GET['postID']) . '">';
								echo '<i class="fa fa-angle-left fa-3x"></i>';
								echo '</a>';
								echo '<h1>';
								echo '<a href="index.php?getPostDetails=' . htmlspecialchars($_GET['getPostDetails']) . '&postID=' . htmlspecialchars($_GET['postID']) . '" class="spinnable">';
							}
							else
							{
								echo '<h1>';	
								echo '<a href="index.php" class="spinnable">';
							}
						?>
						JodelBlue <i class="fa fa-refresh fa-1x"></i></a>
					</h1>					
				</div>
			</nav>
		</header>
		
		<div class="mainContent container">		
			<div class="content row">
				<article class="topContent col-sm-8">

					<content id="posts">
						<?php
							$posts;

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

							//Get Post Details
							if(isset($_GET['postID']) && isset($_GET['getPostDetails']))
							{
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
							else
							{
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

								$posts = getPosts($lastPostId, $accessToken, $url)['posts'];
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
						

						
						<?php }
						} ?>

					</content>
					
					<?php if(!isset($_GET['postID']) && !isset($_GET['getPostDetails'])) { ?>
						<p id="loading">
							Loading…
						</p>
					<?php } ?>
				</article>
			
				<aside class="topSidebar col-sm-4 sidebar-outer">
					<div class="fixed">
						<article>
							<div>
								<h2>Position</h2>
								<form method="get">
									<input type="text" id="city" name="city" placeholder="<?php if(isset($newPositionStatus)) echo $newPositionStatus; ?>" required>

									<input type="submit" value="Set Location" /> 
								</form>
							</div>
						</article>

						<article>
							<div>
								<h2>Karma</h2>
								<?php echo getKarma($accessToken); ?>
							</div>
						</article>

						<article>
							<div>
								<?php if(isset($_GET['postID']) && isset($_GET['getPostDetails'])) { ?>
								<h2>Comment on Jodel</h2>
								<form method="POST">				
										<input type="hidden" name="ancestor" value="<?php echo htmlspecialchars($_GET['postID']);?>" />
										<textarea id="message" name="message" placeholder="Send a comment on a Jodel to all students within 10km" required></textarea> 
									<br />
									<input type="submit" value="SEND" /> 
								</form>
									<?php } else { ?>
								<h2>New Jodel</h2>
								<form method="POST">
									<textarea id="message" name="message" placeholder="Send a Jodel to all students within 10km" required></textarea> 
									<br />
									<select id="postColorPicker" name="color">
										<option value="06A3CB">Blue</option>
										<option value="8ABDB0">Teal</option>
										<option value="9EC41C">Green</option>
										<option value="FFBA00">Yellow</option>
										<option value="DD5F5F">Red</option>
										<option value="FF9908">Orange</option>
									</select> 
									<br />
									<input type="submit" value="SEND" /> 
								</form>
								<?php } ?>
							</div>
						</article>
							
						<article>
							<div>
								<h2>Login</h2>
							</div>
						</article>
					</div>
				</aside>
			</div>
			<div id="sortJodelBy" class="row">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-3">
							<a href="index.php" <?php if($view=='time') echo 'class="active"';?>><i class="fa fa-clock-o fa-3x"></i></a>
						</div>
						<div class="col-sm-3">
							<a href="index.php?view=comment" <?php if($view=='comment') echo 'class="active"';?>><i class="fa fa-commenting-o fa-3x"></i></a>
						</div>
						<div class="col-sm-3">
							<a href="index.php?view=upVote" <?php if($view=='upVote') echo 'class="active"';?>><i class="fa fa-angle-up fa-3x"></i></a>
						</div>
						<div class="col-sm-3">
							<nav>
								<a href="./impressum.html">Impressum</a> | <a href="./datenschutz.html">Datenschutz</a>
							</nav>
						</div>
					</div>
				</div>	
			</div>
		</div>
		
		
		<!-- jQuery, Tether and Bootstrap JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>

		<script>


			$('a').on('click', function(){
			    $('a').removeClass('selected');
			    $(this).addClass('selected');
			});

			function scrollToAnchor(aid){
			    var aTag = $("article[id='"+ aid +"']");
			    $('html,body').animate({scrollTop: aTag.offset().top-90},'slow');
			}


			<?php if(!isset($_GET['postID']) && !isset($_GET['getPostDetails'])) { ?>
			$(document).ready(function() {
				var win = $(window);
				var lastPostId = "<?php echo $lastPostId; ?>";
				var view = "<?php echo $view; ?>"
				var old_lastPostId = "";
				var morePostsAvailable = true;

				if(window.location.hash)
				{
					var hash = window.location.hash.slice(1);

					if(!$("article[id='"+ hash +"']").length)
					{
						for (var i = 5; i >= 0; i--)
						{
							if(!$("article[id='"+ hash +"']").length)
							{
								$.ajax({
									url: 'get-posts-ajax.php?lastPostId=' + lastPostId + '&view=' + view,
									dataType: 'html',
									async: false,
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
											$('#posts').hide().show(0);
										}
										$('#loading').hide();
									}
								});
							}
							
						}
						scrollToAnchor(hash);

					}						
				}

				// Each time the user scrolls
				win.scroll(function() {
					// End of the document reached?
					if (($(document).height() - win.height() == win.scrollTop()) && morePostsAvailable) {
						$('#loading').show();

						
						
						$.ajax({
							url: 'get-posts-ajax.php?lastPostId=' + lastPostId + '&view=' + view,
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
		<?php } ?>
		</script>

	</body>
</html>

