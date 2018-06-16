<form data-url="/item" data-action="add" method="post" role="form">
	<input type="text" name="subsection_id" style="display: none;">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="name-add-item">The item&#x27;s name</label>
			<input type="text" class="form-control required iv_inputValidator" name="name" id="name-add-item" placeholder="Name" maxlength="50" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="type">The item&#x27;s type</label>
			<select type="text" class="form-control required" name="type" id="type" required></select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>