<section class="page-section <?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles)) {echo 'editable-section';} ?>" <?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles) && $editableOptions->submittable) {echo 'id="page-section-'. $editableOptions->submittable->id . '"';} ?>>
	<?php
		$model = new PageSectionHeaderModel($title, $editableOptions, $headerButtons);
		$view = new PageSectionHeaderView($model, $currentUser);
		echo $view->output();
	?>
	
	<?php if ($editableOptions->submittable) { ?>
		<form data-url="<?=$editableOptions->submittable->url?>" data-action="edit" method="post" role="form" class="editable-section__form">
			<div class="page-section__edit-button editable-section__edit-button-container">
				<button type="submit" tabindex="-1" role="button" class="editable-section__button editable-section__button-done fa fa-check"></button>
			</div>
			
			<input type="text" name="section_id" value="<?=$editableOptions->submittable->id?>" style="display: none;">
			<input type="text" class="type__title type__title--no-upper editable-section__input editable-section__input--large editable-section__input--center editable-section__input--full-width iv_inputValidator" name="name" data-except-self="<?=$editableOptions->submittable->id?>" data-initially-valid maxlength="50">
			<input type="text" class="type__desc editable-section__input editable-section__input--medium editable-section__input--center editable-section__input--full-width" name="desc" maxlength="200" placeholder="Description">
		</form>
	<?php } ?>
	
	<?php if ($description) { ?>
		<p class="page-section__desc type__desc <?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles) && $editableOptions->submittable) {echo 'editable-section__display';} ?>" <?php if ($editableOptions && in_array($editableOptions->role, $currentUser->roles) && $editableOptions->submittable) {echo 'data-editable="desc"';} ?>><?=$description?></p>
	<?php } ?>
	
	<?php if ($content) {echo $content;} ?>
	
	<div>
		<?php foreach ($subsections as $subsection) {
			include(__ASSETS_PATH . '/php/View/Template/PageSection__subsection.template.php');
		} ?>
	</div>
</section>