<div class="font-family card card--corners" style="font-family: <?=$data->name?>;">
	<div class="font-family__title">
		<?php if ($data->typeCode == 'web') { ?>
			<a href="<?=$data->url?>" target="_blank"><?=$data->name?></a>
		<?php } else { ?>
			<p><?=$data->name?></p>
		<?php } ?>
	</div>
	
	<?php if ($data->alphabet) { ?>
		<div class="font-family__alphabet"><?=htmlentities($data->alphabet)?></div>
	<?php } ?>
	
	<div class="font-family__footer">
		<?php if ($data->typeCode == 'web') { ?>
			<p class="font-family__link-text type__label">&#x3C;link href=&#x22;<?=htmlentities($data->import)?>&#x22; rel=&#x22;stylesheet&#x22;&#x3E;</p>
		<?php } elseif ($data->typeCode == 'css') { ?>
			<p class="font-family__link-text type__label">&#x3C;link href=&#x22;<?=htmlentities($data->cssFile)?>&#x22; rel=&#x22;stylesheet&#x22;&#x3E;</p>
		<?php } ?>
	</div>
</div>