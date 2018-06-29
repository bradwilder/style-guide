<?php $isDraggable = count($data) > 1; ?>

<div class="draggables__container">
	<?php foreach ($data as $section) { ?>
		<div class="draggable__container">
			<?php
			if ($isDraggable)
			{
				include(__ASSETS_PATH . '/php/View/Template/DraggableSection-placeholder.template.php');
			}
			
			include(__ASSETS_PATH . '/php/View/Template/DraggableSection.template.php');
			?>
		</div>
	<? } ?>
	
	<?php if ($isDraggable) {include(__ASSETS_PATH . '/php/View/Template/DraggableSection-placeholder.template.php');} ?>
</div>