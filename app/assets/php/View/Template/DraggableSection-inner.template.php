<div class="arrange-section__text">
	<p class="type__title <?php if ($section->enabled) {echo 'type__title--darker';} ?>"><?=$section->position . ' - ' . htmlentities($section->name)?></p>
	<?php if ($section->description) { ?>
		<p class="type__desc"><?=$section->description?></p>
	<?php } ?>
</div>