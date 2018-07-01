<?php include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php'); ?>

<section class="page-section">
	<?php
		$model = new PageSectionHeaderModel('The page was not found');
		$view = new PageSectionHeaderView($model, $currentUser);
		echo $view->output();
	?>
</section>