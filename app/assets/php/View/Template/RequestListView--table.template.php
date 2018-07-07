<?php
	$tableModel = new TableModel(null, false, false, 'RequestListView--table-row.template.php', $data);
	
	$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Expiration'));
	$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Type'));
	$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
	
	$view = new TableView($tableModel, $currentUser);
	echo $view->output();
?>