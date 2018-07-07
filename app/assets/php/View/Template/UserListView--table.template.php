<div class="admin-table__wrapper">
	<table class="tables tables--striped tables--selectable tables--sortable {sortList: [[0, 0]], widgets: ['zebra']}">
		<thead class="type__title type__title--darker">
			<tr>
				<?php
					$model = new TableHeaderColumnModel('ID', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel('Email', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel('Phone', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel('Name', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel('Group', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel('Sessions', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel('Requests', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel('Status', null, true, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
					
					$model = new TableHeaderColumnModel(null, null, true, false, true);
					$view = new TableHeaderColumnView($model, $currentUser);
					echo $view->output();
				?>
			</tr>
		</thead>
		<tbody class="type__label">
			<?php foreach ($data as $userListItem) {
				include(__ASSETS_PATH . '/php/View/Template/UserListView--table-row.template.php');
			} ?>
		</tbody>
	</table>
</div>