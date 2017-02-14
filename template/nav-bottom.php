<div id="sortJodelBy" class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-3">
				<a href="<?php echo $baseUrl;?>index.php" <?php if($view=='time') echo 'class="active"';?>><i class="fa fa-clock-o fa-3x"></i></a>
			</div>
			<div class="col-xs-3">
				<a href="<?php echo $baseUrl;?>index.php?view=comment" <?php if($view=='comment') echo 'class="active"';?>><i class="fa fa-commenting-o fa-3x"></i></a>
			</div>
			<div class="col-xs-3">
				<a href="<?php echo $baseUrl;?>index.php?view=upVote" <?php if($view=='upVote') echo 'class="active"';?>><i class="fa fa-angle-up fa-3x"></i></a>
			</div>
			<div class="col-xs-3">
				<nav>
					<a href="<?php echo $baseUrl;?>about-us.php">about us</a>
				</nav>
			</div>
		</div>
	</div>	
</div>
</div>