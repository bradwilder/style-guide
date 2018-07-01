<section class="page-section">
	<?php
		$model = new PageSectionHeaderModel($title);
		$view = new PageSectionHeaderView($model, $currentUser);
		echo $view->output();
	?>
	
	<?php if ($description) { ?>
		<p class="page-section__desc type__desc"><?=$description?></p>
	<?php } ?>
	
	<?php if ($content) {echo $content;} ?>
	
	<div>
		<?php foreach ($subsections as $subsection) {
			include(__ASSETS_PATH . '/php/View/Template/PageSection__subsection.template.php');
		} ?>
	</div>
</section>