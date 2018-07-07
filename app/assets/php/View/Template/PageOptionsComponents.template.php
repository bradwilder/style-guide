<script type="text/x-template" id="page-options-table-template">
	<div class="admin-table__wrapper">
		<table class="tables tables--striped tables--selectable tables--sortable {sortList: [[0, 0]], widgets: ['zebra']}">
			<thead class="type__title type__title--darker">
				<tr>
					<?php
						$model = new TableHeaderColumnModel('Page Code', null, true, true);
						$view = new TableHeaderColumnView($model, $currentUser);
						echo $view->output();
						
						$model = new TableHeaderColumnModel('Show TOC', null, true, false);
						$view = new TableHeaderColumnView($model, $currentUser);
						echo $view->output();
					?>
				</tr>
			</thead>
			<tbody class="type__label">
				<tr is="page-options-table-row" v-for="row in tableData" :model="row"></tr>
			</tbody>
		</table>
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
