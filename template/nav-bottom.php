<div id="sortJodelBy" class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-3">
				<a <?php if($view->view == 'combo')echo 'class="active"';?> href="<?php echo $view->changeView('combo')->toUrl();?>"><i class="fa fa-clock-o fa-3x"></i></a>
			</div>
			<div class="col-xs-3">
				<a <?php if($view->view == 'discussed') echo 'class="active"';?> href="<?php echo $view->changeView('discussed')->toUrl();?>"><i class="fa fa-commenting-o fa-3x"></i></a>
			</div>
			<div class="col-xs-3">
				<a <?php if($view->view == 'popular') echo 'class="active"';?> href="<?php echo $view->changeView('popular')->toUrl();?>"><i class="fa fa-angle-up fa-3x"></i></a>
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