<div class="admin-table__wrapper">
	<table class="tables tables--sortable {sortList: [[0, 0]], widgets: ['zebra']} tables--striped tables--selectable" id="user-table">
		<thead class="type__title">
			<tr>
				<th class="sortable">ID<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Email<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Phone<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Name<span class="sortIcon"><i class="fa"></i></span></th>
				<th style="display:none;">GroupID</th>
				<th class="sortable">Group<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Sessions<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Requests<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="sortable">Status<span class="sortIcon"><i class="fa"></i></span></th>
				<th class="{sorter: false}">Options</th>
			</tr>
		</thead>
		<tbody class="type__label">
			<?php foreach ($userListItems as $userListItem) {
				include(__ASSETS_PATH . '/php/View/Template/UserListView--table-row.template.php');
			} ?>
		</tbody>
	</table>
</div>