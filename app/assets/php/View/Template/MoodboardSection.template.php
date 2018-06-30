<section class="page-section <?php if (in_array('Edit', $currentUser->roles)) {echo 'editable-section';} ?>" id="page-section-<?=$section->id?>">
	<div class="page-section__header editable-section__display">
		<?php if (in_array('Edit', $currentUser->roles)) { ?>
			<div class="page-section__edit-button editable-section__button-container">
				<a tabindex="-1" role="button" class="editable-section__button editable-section__button-edit fa fa-pencil" data-popover-trigger="#editSectionPopover" data-popover-source-id="<?=$section->id?>" tabindex="-1"></a>
			</div>
		<?php } ?>
		
		<h1 class="page-section__title <?php if (in_array('Edit', $currentUser->roles)) {echo 'editable-section__display';} ?> type__title" data-editable="name"><?=htmlentities($section->name)?></h1>
	</div>
	
	<?php if ($section->description != '') { ?>
		<p class="page-section__desc type__desc editable-section__display" data-editable="desc"><?=htmlentities($section->description)?></p>
	<?php } ?>
	
	<form data-url="/moodboardSection" data-action="edit" method="post" role="form" class="editable-section__form">
		<div class="page-section__edit-button editable-section__edit-button-container">
			<a tabindex="-1" role="button" class="editable-section__button editable-section__button-done edit-section__done fa fa-check"></a>
		</div>
		
		<input class="type__title type__title--no-upper editable-section__input editable-section__input--large editable-section__input--center editable-section__input--full-width iv_inputValidator" type="text" name="name" data-except-self="<?=$section->id?>" data-initially-valid maxlength="50">
		
		<input type="text" class="type__desc editable-section__input editable-section__input--medium editable-section__input--center editable-section__input--full-width" name="desc" maxlength="200" placeholder="Description">
	</form>
	
	<?php if (count($section->images) > 0) { ?>
		<div class="page-section__content moodboard__section editable-section__display <?php if ($section->modeName == 'Row') {echo 'row';} else if ($section->modeName == 'Column') {echo 'masonry';} ?>">
			<?php foreach ($section->images as $image) {
				$imageID = $image['id'];
				$imageName = $image['name'];
				$imageDesc = $image['description'];
				$sectionImageID = $image['section_image_id'];
				
				if ($section->modeName == 'Row')
				{
					include(__ASSETS_PATH . '/php/View/Template/MoodboardSection__row-item.template.php');
				}
				else if ($section->modeName == 'Column')
				{
					include(__ASSETS_PATH . '/php/View/Template/MoodboardSection__item.template.php');
				}
			} ?>
		</div>
	<?php } ?>
</section>