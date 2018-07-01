<?php if (count($data->images) > 0) { ?>
	<div class="page-section__content moodboard__section editable-section__display <?php if ($data->modeName == 'Row') {echo 'row';} else if ($data->modeName == 'Column') {echo 'masonry';} ?>">
		<?php foreach ($data->images as $image) {
			$imageID = $image['id'];
			$imageName = $image['name'];
			$imageDesc = $image['description'];
			$sectionImageID = $image['section_image_id'];
			
			if ($data->modeName == 'Row')
			{
				include(__ASSETS_PATH . '/php/View/Template/MoodboardSection__row-item.template.php');
			}
			else if ($data->modeName == 'Column')
			{
				include(__ASSETS_PATH . '/php/View/Template/MoodboardSection__item.template.php');
			}
		} ?>
	</div>
<?php } ?>