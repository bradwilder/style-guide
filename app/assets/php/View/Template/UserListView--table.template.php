<div class="admin-table__wrapper">
	<?php
		$tableSortingOptions = new TableSortingOptions();
		$tableModel = new TableModel($tableSortingOptions, true, true, 'UserListView--table-row.template.php', $data);
		
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('ID', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Email', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Phone', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Group', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Sessions', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Requests', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Status', null, true, true));
		$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, true, false, true));
		
		$view = new TableView($tableModel, $currentUser);
		echo $view->output();
	?>
</div>