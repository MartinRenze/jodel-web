<?php
	set_include_path('../' . get_include_path());
	include('php/jodel-web.php');
	$captcha = $jodelAccountForView->getCaptcha();
?>
<div id="captchaWrapper">
	<p>Check all images with Coons on it (Coons look like <img style="height: 1.0em; width: unset;" src="img/coon.png">).</p>
	<img src="<?php echo $captcha['image_url'];?>">
	<div class='captchaWrapper'>
		<input id='box_0' type='checkbox'>
		<input id='box_1' type='checkbox'>
		<input id='box_2' type='checkbox'>
		<input id='box_3' type='checkbox'>
		<input id='box_4' type='checkbox'>
		<input id='box_5' type='checkbox'>
		<input id='box_6' type='checkbox'>
		<input id='box_7' type='checkbox'>
		<input id='box_8' type='checkbox'>
	</div>
	<button onClick="verifyAccount('<?php echo $captcha['key'];?>','<?php echo $jodelAccountForView->deviceUid?>')">Verify</button>
</div>