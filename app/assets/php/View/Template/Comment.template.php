<li class="comments__list__item comment <?php if ($hasPermission) {echo 'editable-section';} ?>">
	<div>
		<?php if ($hasPermission) { ?>
			<div class="comment__edit-button editable-section__button-container">
				<a tabindex="-1" role="button" class="editable-section__button editable-section__button-edit comment__reply-button fa fa-reply"></a>
			</div>
		<?php } ?>
		<h4 data-toc-skip class="comment__cite type__desc"><?=htmlentities($comment->userName)?> <small class="comment__date type__title">&bull; <?=date("M d, Y H:i:s", $comment->postTime)?></small></h4>
	</div>
	
	<input type="text" name="comment_id" value="<?=$comment->id?>" style="display: none;">
	<p class="comment__body type__desc"><?=str_replace("\n", '<br>', htmlentities($comment->text))?></p>
	
	<?php if ($childHTML != '') {
		include(__ASSETS_PATH . '/php/View/Template/Comment-list.template.php');
	} ?>
</li>