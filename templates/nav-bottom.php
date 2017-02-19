<div id="sortJodelBy" class="row">
	<div class="col-3">
		<a <?php if($view->view == 'combo')echo 'class="active"';?> href="<?php echo $view->changeView('combo')->back()->toUrl();?>"><i class="fa fa-clock-o fa-3x"></i></a>
	</div>
	<div class="col-3">
		<a <?php if($view->view == 'discussed') echo 'class="active"';?> href="<?php echo $view->changeView('discussed')->back()->toUrl();?>"><i class="fa fa-commenting-o fa-3x"></i></a>
	</div>
	<div class="col-3">
		<a <?php if($view->view == 'popular') echo 'class="active"';?> href="<?php echo $view->changeView('popular')->back()->toUrl();?>"><i class="fa fa-angle-up fa-3x"></i></a>
	</div>
	<div class="col-3">
		<nav>
			<a href="<?php echo $baseUrl;?>about-us.php">about</a>
		</nav>
	</div>
</div>
