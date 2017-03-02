<?php
	include('php/jodel-web.php');
	$posts;
	
	//Get Post Details
	if(isset($_GET['postId']) && isset($_GET['getPostDetails']))
	{
		$userHandleBuffer = [];

		$accountCreator = new GetPostDetails();
		$accountCreator->setAccessToken($jodelAccountForView->accessToken);
		$data = $accountCreator->execute();

		if(array_key_exists('status_code', $data) && $data->status_code == 404)
		{
			header('HTTP/1.1 410 Gone');
			include 'error-pages/410.php';
			exit;
		}

		$posts[0] = $data;

		if(array_key_exists('children', $data)) {
			foreach($data['children'] as $key => $child)
			{
				
				if(!$child["parent_creator"] == 1)
				{
					$numberForUser = array_search($child['user_handle'], $userHandleBuffer);
					if($numberForUser === FALSE)
					{
						array_push($userHandleBuffer, $child['user_handle']);
						$data['children'][$key]['user_handle'] = count($userHandleBuffer);
					}
					else
					{
						$data['children'][$key]['user_handle'] = $numberForUser + 1;
					}
				}

				array_push($posts, $data['children'][$key]);
			}
		}
	}
	//Get Posts and Hashtags
	else
	{
		$posts = $view->getPosts($jodelAccountForView);
	}
	if(!isset($posts[0]))
	{
		error_log('Fehler: ' . print_r($posts, true) . print_r($posts['recent'], true) . print_r($posts['posts'], true));
		$title = $view->getTitle();
		$description = $view->getMetaDescription();
	}
	else
	{
		$title = $view->getTitle($posts[0]);
		$description = $view->getMetaDescription($posts[0]);
	}

	if($view->isDetailedView)
	{
		$backButton = $view->back()->toUrl() . '#postId-' . $view->postId;
	}
	else
	{
		$backButton = '';
	}
	include 'templates/header.php';
?>
		
		<div class="mainContent container">		
			<div class="content row">
				<article class="topContent col-sm-8">

					<content id="posts">
						<?php
							foreach($posts as $post)
							{
								$view->lastPostId = $post['post_id'];
								$view->jodelToHtml($post);
							} ?>
					</content>
					
					<?php if(!isset($_GET['postId']) && !isset($_GET['getPostDetails'])) { ?>
						<p id="loading">
							Loading…
						</p>
					<?php } ?>
				</article>
			
				<aside class="topSidebar col-sm-4 sidebar-outer">
					<div class="fixed<?php if(!$view->isDetailedView) echo(' hide-mobile');?>">
						<article>
							<div>
								<h2>Position / Hashtag</h2>
								<form action="index.php" method="get">
									<input type="text" id="city" name="search" placeholder="<?php if(isset($newPositionStatus)) echo $newPositionStatus; ?>" required>
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
								<?php if(isset($_GET['postId']) && isset($_GET['getPostDetails'])) { ?>
								<h2>Comment on Jodel</h2>
								<form method="POST">				
										<input type="hidden" name="ancestor" value="<?php echo htmlspecialchars($_GET['postId']);?>" />
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
								<h2>Donate to JodelBlue</h2>

								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="RR45538QV3VXE">
								<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
								</form>

								<p class="bitcoin-address">Bitcoin-address: <a href="img/bitcoin-address.png">1DzaUWm9Du6CUQLj6QTGC9kpxzKE3yZZHV</a></p>

								<progress max="3500" value="111"></progress>
								<p>
									My payments to keep this Project up so far:
								</p>
								<ul>
									<li>Webspace 15€ - goes till 01-03-2018</li>
									<li>Domain 20€ - goes till 06-12-2017</li>
								</ul>
							</div>
						</article>

						<article>
							<div>
								<h2>Beta - Votebot</h2>

								<p>The Beta for the vote bot feature is starting soon. You are cordially invited to test. Please contact us: info@jodelblue.com</p>
							</div>
						</article>
					</div>
				</aside>
			</div>
			<?php include 'templates/nav-bottom.php';?>
		</div>
		<?php
			$includeEmojiAndAjax = TRUE;
			include 'templates/footer.php';
		?>