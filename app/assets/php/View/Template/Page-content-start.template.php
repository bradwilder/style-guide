<div class="page-content__container container-fluid">
	<div class="page-content__row row">
		<?php if ($useTOC) { ?>
			<div class="col-lg-2 col-md-3 hidden-sm hidden-xs table-of-contents__container">
				<nav class="table-of-contents type__title type__title--darker" id="toc" data-toggle="toc" data-spy="affix"></nav>
			</div>
		<?php }  ?>
		<div class="<?php if ($useTOC) { echo 'col-lg-10 col-md-9'; } else { echo 'col-md-12'; } ?> page-content__inner-container">
			