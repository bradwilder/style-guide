<?php  
$color = $data->color;
$colorDescriptors = $data->descriptors;
// ColorTile.template.php needs the $colors as $data, even though this file also uses $data
$data = $color;
?>

<div class="row">
	<div class="col-xs-6">
		<?php include(__ASSETS_PATH . '/php/View/Template/ColorTile.template.php'); ?>
	</div>
	
	<div class="col-xs-6">
		<?php include(__ASSETS_PATH . '/php/View/Template/ColorDescriptorCard.template.php'); ?>
	</div>
</div>