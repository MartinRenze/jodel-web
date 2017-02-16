<?php include 'php/jodel-web.php';
$title = 'About us - JodelBlue Web-App and Browser-Client';
$description = 'We try to get the best Jodel experience on your Browser. This allows you to use jodel on your desktop PC or Windows Phone. I am pleased if you participate in the development on GitHub.';
$backButton = '';
include 'templates/header.php';
?>	
<div class="mainContent container">		
	<div class="content row">
		<article class="topContent col-sm-12">

			<content id="posts">
				<article id="aboutUs" class="jodel" style="background-color: #5682a3;">
					<content>
						<h2>About us</h2>

						<p>This page was not created by "The Jodel Venture GmbH", the official developers of the Jodel app.</p>
						<p>All content is from the official Jodel app, all rights remain with the respective owners. We do not store any data or spread them.</p>
						<p>info@jodelblue.com</p>
						<p><a style="color: #fff; text-decoration: underline;" href="https://github.com/mmainstreet/jodel-web">On Github</a></p>
					</content>

					<footer>
						<table>
							<tr>
								<td class="time">
									<span data-tooltip="Time">
										<i class="fa fa-clock-o"></i>
										0s
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
										0 km
									</span>
								</td>
							</tr>
						</table>
					</footer>
				</article>

			</content>
		</article>
	</div>
	<?php include 'templates/nav-bottom.php';?>
</div>
<?php include 'templatess/footer.php';?>