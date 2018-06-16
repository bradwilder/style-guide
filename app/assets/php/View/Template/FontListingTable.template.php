<table class="font-listings">
	<tbody>
		<?php foreach ($data as $listing) {
			include(__ASSETS_PATH . '/php/View/Template/FontListingTable__row.template.php');
		} ?>
	</tbody>
</table>