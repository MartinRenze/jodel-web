<?php
	$config = parse_ini_file(realpath('/config/config.ini.php'));

	$baseUrl = $config['Url'];


	$title = '404 Not Found - JodelBlue Web-App and Browser-Client';
	$description = 'JodelBlue is a WebClient for the Jodel App. No registration required! Browse Jodels all over the world. Send your own Jodels or upvote others.';
	$backButton = $baseUrl;
	include '../templates/header.php';
?>		
<div class="mainContent container">		
	<div class="content row">
		<article class="topContent col-sm-12">

			<content id="posts">
				<article id="aboutUs" class="jodel" style="background-color: #5682a3;">
					<content>
						Error: Not Found<br />
						<br />
						The requested URL was not found on this server.
					</content>
					<aside>
						<a href="index.php">
							<i class="fa fa-angle-up fa-3x"></i>
						</a>	
							<br />
						404<br />
						<a href="index.php">
							<i class="fa fa-angle-down fa-3x"></i>
						</a>
					</aside>
					<footer>
						<table>
							<tr>
								<td class="time">
									<span data-tooltip="Time">
										<i class="fa fa-clock-o"></i>
										404s
									</span> 
								</td>
								<td class="comments">
									
								</td>
								<td class="distance">


									<span data-tooltip="Author">
										<i class="fa fa-user-o"></i> JodelBlue |
									</span>
											



									<span data-tooltip="Distance">
										<i class="fa fa-map-marker"></i>
										404 km
									</span>
								</td>
							</tr>
						</table>
					</footer>
				</article>

			</content>
		</article>
	</div>
</div>
<?php include '../templates/footer.php';