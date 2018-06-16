<div class="page-section__sub__sub">
	<h3 class="page-section__sub__sub__title type__title type__title--wide"><?=$subSubection->title?></h3>
	
	<?php if ($subSubection->description) { ?>
		<p class="page-section__sub__sub__desc type__desc"><?=$subSubection->description?></p>
	<?php } ?>
	
	<?php if ($subSubection->content != '') { ?>
		<div class="row"><?=$subSubection->content?></div>
	<?php } ?>
</div>