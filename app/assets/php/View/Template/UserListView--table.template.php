<div class="admin-table__wrapper">
	<table class="tables tables--striped tables--selectable tables--sortable {sortList: [[0, 0]], widgets: ['zebra']}">
		<thead class="type__title type__title--darker">
			<tr>
				<th class="sortable">ID<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Email<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Phone<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Name<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Group<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Sessions<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Requests<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Status<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="{sorter: false}"><i class="fa fa-cog"></i></th>
			</tr>
		</thead>
		<tbody class="type__label">
			<?php foreach ($data as $userListItem) {
				include(__ASSETS_PATH . '/php/View/Template/UserListView--table-row.template.php');
			} ?>
		</tbody>
	</table>
</div>