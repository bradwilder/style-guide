<div class="comments">
	<?=$commentsContentInner?>
</div>

<?php if (in_array('Comment', $currentUser->roles)) { ?>
	<div class="comment-submit">
		<h3 class="type__desc">Leave a comment</h3>
		<form method="post" role="form">
			<input type="text" name="action" value="submit" style="display: none;">
			<div class="form-group">
				<label class="sr-only" for="comment">Comment</label>
				<textarea class="form-control type__desc" name="comment" id="comment" cols="45" rows="8" placeholder="Comment" required maxlength="2000"></textarea>
			</div>
			<button type="button" class="comment-submit__button btn btn-primary">Submit</button>
		</form>
	</div>
<?php } ?>