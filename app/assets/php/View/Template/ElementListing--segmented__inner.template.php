<div class="element-listing__bg">
	<table class="element-listing__table">
		<tbody>
			<tr class="element-listing__bg">
				<?php foreach ($elements as $element) { ?>
					<td class="element-listing__segment--<?=$elementCount?>"><img class="element-listing__item" src="/assets/img/uploads/style-guide/<?=$element->path . $element->file?>" alt="<?=$element->fullName?>"></td>
				<?php } ?>
			</tr>
		</tbody>
	</table>
</div>
<table class="element-listing__table">
	<tbody>
		<tr>
			<?php foreach ($elements as $element) { ?>
				<td class="card__label type__label type__label--upper element-listing__segment--<?=$elementCount?>"><?=$element->shortName?></td>
			<?php } ?>
		</tr>
	</tbody>
</table>