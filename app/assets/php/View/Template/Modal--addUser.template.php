<form data-url="/user" data-action="add" method="post" role="form">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="email-add-user">The user's email address</label>
			<input type="text" class="form-control iv_inputValidator" name="email" id="email-add-user" placeholder="Email" maxlength="50" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="phone-add-user">The user's phone number for SMS</label>
			<input type="text" class="form-control" name="phone" id="phone-add-user" placeholder="Phone (e.g., +15553031212)" maxlength="20" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="displayName-add-user">A display name for the user</label>
			<input type="text" class="form-control" name="displayName" id="displayName-add-user" placeholder="Display Name" maxlength="200"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="group-add-user">The group for the user</label>
			<select name="group" id="group-add-user"></select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>