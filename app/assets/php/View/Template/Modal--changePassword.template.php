<form data-url="/user" data-action="changePassword" method="post" role="form">
	<div class="modal-body">
		<input type="text" name="id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="old">Old password</label>
			<input type="password" class="form-control" name="old" id="old" placeholder="Old password" maxlength="50" required autocomplete="off"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="new">New password</label>
			<input type="password" class="form-control" name="new" id="new" placeholder="New password" maxlength="50" required autocomplete="off"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="new-confirm">New password confirm</label>
			<input type="password" class="form-control" name="new-confirm" id="new-confirm" placeholder="New password confirm" maxlength="50" required autocomplete="off"/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>