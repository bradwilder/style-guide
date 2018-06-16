<div class="edit-image type__title type__title--no-upper">
	<img class="edit-image__img" src="<?='/assets/img/uploads/moodboard/' . $image->name?>" alt="<?=$image->name?>">
	<div class="edit-image__detail">
		<p class="modals__text"><?=htmlentities($image->name)?></p>
		
		<div class="edit-image__buttons">
			<div class="checkbox-custom">
				<label class="checkbox-custom__bg">
					<input type="checkbox" name="add-images-check-<?=$image->id?>" class="checkbox-custom__input">
					<i class="checkbox-custom__check fa fa-check"></i>
				</label>
			</div>
		</div>
	</div>
</div>