<?php include 'php/jodel-web.php';?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $viewTest::getTitle($posts[0], $view, $isDetailedView);?></title>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		
		<meta name="description" content="<?php echo $viewTest::getMetaDescription($posts[0], $view, $isDetailedView);?>">
		<meta name="keywords" content="jodelblue, jodel, blue, webclient, web, client, web-app, browser, app">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="style.css" type="text/css">
		
		<link rel="shortcut icon" type="image/x-icon" href="./img/favicon/favicon.ico">
		<link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
		<link rel="icon" type="image/gif" href="./img/favicon/favicon.gif">
		<link rel="icon" type="image/png" href="./img/favicon/favicon.png">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon.png">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-57x57.png" sizes="57x57">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-60x60.png" sizes="60x60">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-72x72.png" sizes="72x72">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-76x76.png" sizes="76x76">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-114x114.png" sizes="114x114">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-120x120.png" sizes="120x120">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-128x128.png" sizes="128x128">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-144x144.png" sizes="144x144">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-152x152.png" sizes="152x152">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-180x180.png" sizes="180x180">
		<link rel="apple-touch-icon" href="./img/favicon/apple-touch-icon-precomposed.png">
		<link rel="icon" type="image/png" href="./img/favicon/favicon-16x16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="./img/favicon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="./img/favicon/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="./img/favicon/favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="./img/favicon/favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="./img/favicon/favicon-196x196.png" sizes="196x196">
		<meta name="msapplication-TileImage" content="./img/favicon/win8-tile-144x144.png"> 
		<meta name="msapplication-TileColor" content="#5682a3"> 
		<meta name="msapplication-navbutton-color" content="#5682a3"> 
		<meta name="application-name" content="JodelBlue"/> 
		<meta name="msapplication-tooltip" content="JodelBlue"/> 
		<meta name="apple-mobile-web-app-title" content="JodelBlue"/> 
		<meta name="msapplication-square70x70logo" content="./img/favicon/win8-tile-70x70.png"> 
		<meta name="msapplication-square144x144logo" content="./img/favicon/win8-tile-144x144.png"> 
		<meta name="msapplication-square150x150logo" content="./img/favicon/win8-tile-150x150.png"> 
		<meta name="msapplication-wide310x150logo" content="./img/favicon/win8-tile-310x150.png"> 
		<meta name="msapplication-square310x310logo" content="./img/favicon/win8-tile-310x310.png"> 
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
								echo '<a href="index.php?getPostDetails=' . htmlspecialchars($_GET['getPostDetails']) . '&postID=' . htmlspecialchars($_GET['postID']) . '" class="spinnable hidden-xs-down">';
							}
							else
							{
								echo '<h1>';	
								echo '<a href="./" class="spinnable">';
							}
						?>
						JodelBlue <i class="fa fa-refresh fa-1x"></i></a>
					</h1>

					<div id="location_mobile" class="hidden-sm-up">
						<form method="get">
							<input type="text" id="city_mobile" name="city" placeholder="<?php if(isset($newPositionStatus)) echo $newPositionStatus; ?>" required>

							<input type="submit" id="submit_mobile" class="fa" value="&#xf0ac;" />
						</form>
					</div>
				</div>
			</nav>
		</header>
		
		<div class="mainContent container">		
			<div class="content row">
				<article class="topContent col-sm-8">

					<content id="posts">
						<?php
							for($i = 0; $i<$loops; $i++)
							{
								if(array_key_exists($i, $posts) && array_key_exists('post_id', $posts[$i]) && isset($posts[$i]['post_id']))
								{
									$lastPostId = $posts[$i]['post_id'];

									$viewTest::jodelToHtml($posts[$i], $view, $isDetailedView);
								}
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
								<h2>Position / Hashtag</h2>
								<form method="get">
									<input type="text" id="city" name="city" placeholder="<?php if(isset($newPositionStatus)) echo $newPositionStatus; ?>" required>
									<label>try: #jhj</label><br>
									<input type="submit" value="Set Location" /> 
								</form>
							</div>
						</article>

						<article>
							<div>
								<h2>Karma</h2>
								<?php echo $jodelAccountForKarma->getKarma(); ?>
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
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-3">
							<a href="index.php" <?php if($view=='time') echo 'class="active"';?>><i class="fa fa-clock-o fa-3x"></i></a>
						</div>
						<div class="col-xs-3">
							<a href="index.php?view=comment" <?php if($view=='comment') echo 'class="active"';?>><i class="fa fa-commenting-o fa-3x"></i></a>
						</div>
						<div class="col-xs-3">
							<a href="index.php?view=upVote" <?php if($view=='upVote') echo 'class="active"';?>><i class="fa fa-angle-up fa-3x"></i></a>
						</div>
						<div class="col-xs-3">
							<nav>
								<a href="./about-us.html">about us</a>
							</nav>
						</div>
					</div>
				</div>	
			</div>
		</div>
		
		
		<!-- jQuery, Tether, Bootstrap JS and own-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
    	<script src="js/jQueryEmoji.js"></script>

		<script>
			//BackButton
			function goBack()
			{
				window.history.back();
			}

			$(document).ready(function()
			{
				//Transform UTF-8 Emoji to img
				$('.jodel > content').Emoji();

				$('a').on('click', function(){
				    $('a').removeClass('selected');
				    $(this).addClass('selected');
				});

				function scrollToAnchor(aid){
				    var aTag = $("article[id='"+ aid +"']");
				    $('html,body').animate({scrollTop: aTag.offset().top-90},'slow');
				}

				<?php if(!isset($_GET['postID']) && !isset($_GET['getPostDetails'])) { ?>

				



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

								$('.jodel > content').Emoji();
							}
							
						}
						scrollToAnchor(hash);

					}						
				}

				// Each time the user scrolls
				win.scroll(function() {


					// End of the document reached?
					if ($(window).scrollTop() + $(window).height() > $(document).height() - 100 && morePostsAvailable)
					{
						$('#loading').show();

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
								if(lastPostId == old_lastPostId)
								{
									
									//morePostsAvailable = false;
								}
								else
								{
									//alert(elements[3].textContent);
									$('#posts').append(elements[1].innerHTML);
								}
								$('#loading').hide();
							}
						});

						$('.jodel > content').Emoji();
					}
				});
			<?php } ?>
			});	

		</script>

		<?php  
			if(is_file('./piwik-script.html'))
			{
			    require_once('./piwik-script.html');
			}
		?>

	</body>
</html>

