<section class="page-section">
	<div class="page-section__header">
		<h1 class="page-section__title type__title"><?=$title?></h1>
	</div>
	
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