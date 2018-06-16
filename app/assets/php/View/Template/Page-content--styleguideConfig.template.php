<div class="sg-config-pane">
	<section class="sg-config-pane__title title-section" id="title">
		<h1 class="title-section__title type__title" data-toc-skip>Style Guide Config</h1>
	</section>
	<div class="sg-config-pane__container container">
		<div class="sg-config-pane__row row">
			<div class="sg-config-pane__col sg-config-pane__tree col-md-5">
				<config-tree></config-tree>
			</div>
			<div class="sg-config-pane__col sg-config-pane__detail col-md-7">
				<button class="sg-config-pane__back-button btn"><i class="fa fa-arrow-circle-o-left"></i> Back to config tree</button>
				<component :is="selectedConfigComponent" :initial-data="selectedData" :selected-code="selectedItemCode"></component>
			</div>
		</div>

		<?php include(__ASSETS_PATH . '/php/View/Template/StyleguideConfigDetailComponents.template.php'); ?>
	</div>
</div>