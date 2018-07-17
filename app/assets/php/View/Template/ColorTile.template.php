<?php

$colorsCount = count($data->shades) + 1;

$colors = [];
if (count($data->shades) > 0)
{
	$colors []= (object) ['hex' => $data->shades[0], 'isMainColor' => false];
}
$colors []= (object) ['hex' => $data->hex, 'isMainColor' => true];
if (count($data->shades) > 1)
{
	$colors []= (object) ['hex' => $data->shades[1], 'isMainColor' => false];
}

?>

<div class="color-swatch color-swatch__card card card--shadow">
	<?php foreach ($colors as $index=>$color) {
		if ($index != 0)
		{
			echo '--><div class="color-swatch__tile color-swatch__tile--' . $colorsCount . '">';
		}
		else
		{
			echo '<div class="color-swatch__tile color-swatch__tile--' . $colorsCount . '">';
		}
		?>
		<div class="color-swatch__color" style="background-color: #<?php echo $color->hex; ?>"></div>
		<p class="card__label type__label <?php if ($colorsCount > 1 && $color->isMainColor) {echo 'type__label--emphasis';} ?>"></p>
		<?php
		if ($index != ($colorsCount - 1))
		{
			echo '</div><!-- Comments to remove the damn gap';
		}
		else
		{
			echo '</div>';
		}
	} ?>
</div>