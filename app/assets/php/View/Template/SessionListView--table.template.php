<table class="tables">
	<thead class="type__title type__title--darker">
		<tr>
			<?php
				$model = new TableHeaderColumnModel('IP');
				$view = new TableHeaderColumnView($model, $currentUser);
				echo $view->output();
				
				$model = new TableHeaderColumnModel('Expiration');
				$view = new TableHeaderColumnView($model, $currentUser);
				echo $view->output();
				
				$model = new TableHeaderColumnModel(null, null, false, false, true);
				$view = new TableHeaderColumnView($model, $currentUser);
				echo $view->output();
			?>
		</tr>
	</thead>
	<tbody class="type__label">
		<?php foreach ($data as $session) {
			include(__ASSETS_PATH . '/php/View/Template/SessionListView--table-row.template.php');
		} ?>
	</tbody>
</table>