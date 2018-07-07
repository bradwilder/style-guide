<?php
	$tableModel = new TableModel(null, false, false, 'SessionListView--table-row.template.php', $data);
	
	$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('IP'));
	$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Expiration'));
	$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
	
	$view = new TableView($tableModel, $currentUser);
	echo $view->output();
?>