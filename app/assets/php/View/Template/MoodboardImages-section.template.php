<div class="edit-image type__title type__title--no-upper">
	<img class="edit-image__img" src="<?='/assets/img/uploads/moodboard/' . $image->name?>" alt="<?=$image->name?>">
	<div class="edit-image__detail">
		<p class="modals__text"><?=htmlentities($image->name)?></p>
		
		<p class="modals__text">Sections Used In:</p>
		<?php if (count($image->sections) > 0) { ?>
			<ul class="edit-image__list">
				<?php foreach ($image->sections as $section) { ?>
					<li class="edit-image__list-item"><?=htmlentities($section['name'])?></li>
				<?php } ?>
			</ul>
		<?php } ?>
		
		<div class="edit-image__buttons">
			<form data-url="/moodboardImage" data-action="replace" method="post" role="form" enctype="multipart/form-data" class="edit-image__replace-form">
				<input type="text" name="image_id" value="<?=$image->id?>" style="display: none;">
				<label class="btn btn-default" for="edit-image__replace-input-<?=$image->id?>">
					Replace
					<input id="edit-image__replace-input-<?=$image->id?>" name="file" type="file" accept="image/*" class="edit-image__replace-input" style="display: none;">
				</label>
			</form>
			<button type="button" class="btn btn-default edit-image__delete">Delete</button>
		</div>
	</div>
</div>