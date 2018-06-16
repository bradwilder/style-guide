<form data-url="/section" data-action="add" method="post" role="form">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="name-add-section">The section&#x27;s name</label>
			<input type="text" class="form-control required iv_inputValidator" name="name" id="name-add-section" placeholder="Name" maxlength="50" required/>
		</div>
		<div class="form-group">
			<label for="enabled">Enabled
				<input type="checkbox" name="enabled" id="enabled" placeholder="Enabled" checked/>
			</label>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>