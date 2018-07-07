<table class="tables <?php if ($striped) {echo 'tables--striped ';} ?> <?php if ($selectable) {echo 'tables--selectable ';} ?> <?php if ($sortableOptions) {echo "tables--sortable {sortList: [[$sortableOptions->initialCol, $sortableOptions->initialOrder]]" . ($striped ? ", widgets: ['zebra']" : '') . "}";} ?>" <?=$attributes?>>
	<thead class="type__title type__title--darker">
		<tr>
			<?php foreach ($columns as $column) {
				$view = new TableHeaderColumnView($column, $currentUser);
				echo $view->output();
			} ?>
		</tr>
	</thead>
	<tbody class="type__label">
		<?php
			if ($data)
			{
				foreach ($data as $row)
				{
					include(__ASSETS_PATH . "/php/View/Template/$rowTemplate");
				}
			}
			else
			{
				echo $rowTemplate;
			}
		?>
	</tbody>
</table>