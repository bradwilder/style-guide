<form data-url="/user" data-action="edit" method="post" role="form">
	<div class="modal-body">
		<input type="text" name="id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="email">The user&#x27;s email address</label>
			<input type="text" class="form-control iv_inputValidator" name="email" id="email" placeholder="Email" maxlength="50" data-initially-valid required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="phone">The user&#x27;s phone number for SMS</label>
			<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone (e.g., +15553031212)" maxlength="20" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="displayName">A display name for the user</label>
			<input type="text" class="form-control" name="displayName" id="displayName" placeholder="Display Name" maxlength="200"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="group">The group for the user</label>
			<select name="group" id="group"></select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>