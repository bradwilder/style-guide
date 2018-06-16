<input type="text" name="section_id" value="<?=$sectionID?>" style="display: none;">

<?php foreach ($images as $image) {
	include(__ASSETS_PATH . '/php/View/Template/MoodboardSectionImages-section.template.php');
} ?>