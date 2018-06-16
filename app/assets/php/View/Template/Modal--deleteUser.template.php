<form data-url="/user" data-action="delete" method="post" role="form">
	<div class="modal-body">
		<input type="text" name="user_id_delete" style="display: none;">
		<input type="text" name="user_id_current" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="password">Your password</label>
			<label class="type__desc" for="password">Enter your password to delete user <span class="delete-user-name"></span></label>
			<input type="password" class="form-control" name="password" id="password" placeholder="Your password" maxlength="50" required autocomplete="off"/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Delete"/>
	</div>
</form>