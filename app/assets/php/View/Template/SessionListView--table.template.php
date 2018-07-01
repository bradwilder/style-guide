<table class="tables">
	<thead class="type__title">
		<tr>
			<th>IP</th>
			<th>Expiration</th>
			<th><i class="fa fa-cog"></i></th>
		</tr>
	</thead>
	<tbody class="type__label">
		<?php foreach ($data as $session) {
			include(__ASSETS_PATH . '/php/View/Template/SessionListView--table-row.template.php');
		} ?>
	</tbody>
</table>