<?php
$elements = $data;
$elementCount = count($elements);
?>

<div class="element-listing card card--shadow">
	<?php
	if ($elementCount == 1)
	{
		$element = $elements[0];
		include(__ASSETS_PATH . '/php/View/Template/ElementListing__inner.template.php');
	}
	else
	{
		include(__ASSETS_PATH . '/php/View/Template/ElementListing--segmented__inner.template.php');
	}
	?>
</div>