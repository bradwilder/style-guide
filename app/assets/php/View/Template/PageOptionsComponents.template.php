<script type="text/x-template" id="page-options-table-template">
	<div class="admin-table__wrapper">
		<?php
			$tableSortingOptions = new TableSortingOptions();
			$tableModel = new TableModel($tableSortingOptions, true, true, '<tr is="page-options-table-row" v-for="row in tableData" :model="row"></tr>', false);
			
			$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Page Code', null, true, true));
			$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Show TOC', null, true, false));
			
			$view = new TableView($tableModel, $currentUser);
			echo $view->output();
		?>
	</div>
</script>

<script type="text/x-template" id="page-options-table-row-template">
	<tr>
		<td>{{ myModel.code }}</td>
		<td class="tables__checkbox">
			<i v-show="myModel.value == '1'" class="tables__display-button fa fa-check"></i>
			<input class="tables__edit-button" type="checkbox" :checked="myModel.value == '1'" @click="clickTOC">
		</td>
	</tr>
</script>
