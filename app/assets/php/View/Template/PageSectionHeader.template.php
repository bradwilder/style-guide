<div class="page-section__header <?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles) && $editableOptions->submittable) {echo 'editable-section__display';} ?>">
	<?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles)) { ?>
		<div class="page-section__edit-button editable-section__button-container">
			<?php foreach ($buttons as $button) { ?>
				<a tabindex="-1" role="button" class="editable-section__button <?=$button->classes?>" <?=$button->attributes?>></a>
			<?php } ?>
		</div>
	<?php } ?>
	
	<h1 class="page-section__title type__title <?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles) && $editableOptions->submittable) {echo 'editable-section__display';} ?>" <?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles) && $editableOptions->submittable) {echo 'data-editable="name"';} ?>><?=$title?></h1>
</div>