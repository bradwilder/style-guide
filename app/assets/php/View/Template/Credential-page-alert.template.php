<?php if ($_GET['error']) { ?>
	<div class="alert-danger login__info type__desc"><?=$_GET['error']?></div>
<?php } else if ($_GET['message']) { ?>
	<div class="alert-info login__info type__desc"><?=$_GET['message']?></div>
<?php } ?>