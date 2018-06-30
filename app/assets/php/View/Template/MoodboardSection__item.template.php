<div class="moodboard-item">
	<img class="moodboard-item__image" src="<?='/assets/img/uploads/moodboard/' . $imageName?>" alt="<?=$imageName?>">
	<div class="moodboard-item__overlay <?php if (in_array('Edit', $currentUser->roles)) {echo 'editable-section';} ?>">
		<?php if (in_array('Edit', $currentUser->roles)) { ?>
			<div class="moodboard-item__overlay__edit-button editable-section__button-container">
				<a tabindex="-1" role="button" class="editable-section__button editable-section__button-edit fa fa-pencil" data-popover-trigger="#editImagePopover" data-popover-source-id="<?=$sectionImageID?>"></a>
			</div>
		<?php } ?>
		
		<div class="moodboard-item__overlay__text-container">
			<p class="moodboard-item__overlay__text moodboard-item__overlay__title type__title"><?=htmlentities($imageName)?></p>
			<p class="moodboard-item__overlay__text type__desc"><?=htmlentities($imageDesc)?></p>
		</div>
	</div>
</div>