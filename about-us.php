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
						<article>
							<h2>About us</h2>

							<p>This page was not created by "The Jodel Venture GmbH", the official developers of the Jodel app.</p>
							<p>All content is from the official Jodel app, all rights remain with the respective owners. We do not store any data or spread them.</p>

							<p>If you find bugs or want to help me developing the project, feel free to contact me:</p>
							<p>info@jodelblue.com</p>

							<p><a style="color: #fff; text-decoration: underline;" href="https://github.com/mmainstreet/jodel-web">JodelBlue on GitHub</a></p>
						</article>
						<hr>
						<article>
							<div>
								<h2>Donate to JodelBlue</h2>
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
<?php include 'templates/footer.php';?>