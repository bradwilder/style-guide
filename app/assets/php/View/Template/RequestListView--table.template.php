<table class="tables">
	<thead class="type__title">
		<tr>
			<th>Expiration</th>
			<th>Type</th>
			<th><i class="fa fa-cog"></i></th>
		</tr>
	</thead>
	<tbody class="type__label">
		<?php foreach ($data as $request) {
			include(__ASSETS_PATH . '/php/View/Template/RequestListView--table-row.template.php');
		} ?>
	</tbody>
</table>