<div data-id="<?=$section->id?>" class="arrange-section arrange-section__section <?php if ($isDraggable) {echo 'draggable';} ?>" <?php if ($isDraggable) {echo 'draggable="true"';} ?>>
	<div class="<?php if ($isDraggable) {echo 'draggable__inner';} ?>">
		<?php include(__ASSETS_PATH . '/php/View/Template/DraggableSection-inner.template.php'); ?>
	</div>
</div>