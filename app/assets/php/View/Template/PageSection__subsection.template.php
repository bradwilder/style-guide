<div class="page-section__sub">
	<h2 class="page-section__sub__title type__title type__title--wide"><?=$subsection->title?></h2>
	
	<?php if ($subsection->description) { ?>
		<p class="page-section__sub__desc type__desc"><?=$subsection->description?></p>
	<?php } ?>
	
	<?php if ($subsection->content != '') { ?>
		<div class="page-section__sub__content row"><?=$subsection->content?></div>
	<?php } ?>
	
	<div>
		<?php foreach ($subsection->subSubsections as $subSubection) {
			include(__ASSETS_PATH . '/php/View/Template/PageSection__sub-subsection.template.php');
		} ?>
	</div>
</div>