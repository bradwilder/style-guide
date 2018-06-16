<tr class="font-listing">
	<td class="font-listing__example">
		<p class="font-listing__item"
		<?php if (count($listing->cssArray) > 0) {
			echo 'style="';
			
			foreach ($listing->cssArray as $css)
			{
				echo $css;
			}
			
			echo '"';
		} ?>
		><?=$listing->text?></p>
	</td>
	<td class="font-listing__label type__label">
		<p class="font-listing__item font-listing__typeface"></p>
		<p class="font-listing__item font-listing__data"></p>
	</td>
</tr>