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

		if(array_key_exists('error', $data))
		{
			echo $data['error'];
			exit();
			renderTemplate('The post has been blocked');
			exit();
		}

		if(array_key_exists('status_code', $data) && $data->status_code == 404)
		{
			header('HTTP/1.1 410 Gone');
			include 'error-pages/410.php';
			exit();
		}

		$posts[0] = $data;
		$ojHandle = $posts[0]['user_handle'];

		if(array_key_exists('children', $data))
		{
			foreach($data['children'] as $key => $child)
			{
				//is Comment from OJ?
				if($ojHandle == $child['user_handle'])
				{
					$data['children'][$key]['user_handle'] = 'OJ';
				}
				else
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
					<div id="errorMsg"></div>

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
								<?php if(!$jodelAccountForView->isAccountVerified())
								{
								?>
								<h2>New Jodel</h2>
								
								<button href="templates/captcha.php" data-title="Verify Captcha" data-width="1200" data-toggle="lightbox" data-gallery="remoteload">I am not a robot</button>

								<?php 
								}
								else
								{
									if(isset($_GET['postId']) && isset($_GET['getPostDetails'])) { ?>
									<h2>Comment on Jodel</h2>
									<form enctype="multipart/form-data" method="POST">				
											<input type="hidden" name="ancestor" value="<?php echo htmlspecialchars($_GET['postId']);?>" />
											<textarea id="message" name="message" placeholder="Send a comment on a Jodel to all students within 10km" required></textarea>
											<input type="hidden" name="MAX_FILE_SIZE" value="999990000" />
											<input name="image" type="file" />
										<input type="submit" value="SEND" /> 
									</form>
										<?php } else { ?>
									<h2>New Jodel</h2>
									<form enctype="multipart/form-data" method="POST">
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
										<input type="hidden" name="MAX_FILE_SIZE" value="999990000" />
										<input name="image" type="file" />
										<input type="submit" value="SEND" /> 
									</form>
								<?php } ?>
							<?php } ?>
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