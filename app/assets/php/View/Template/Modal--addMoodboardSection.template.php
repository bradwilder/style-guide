<form data-url="/moodboardSection" data-action="add" method="post" role="form">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="name-add-section">A name for the section</label>
			<input type="text" class="form-control iv_inputValidator" name="name" id="name-add-section" placeholder="Section Name" maxlength="50" required autocomplete="off"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="desc-add-section">A description for the section</label>
			<textarea class="form-control" name="desc" id="desc-add-section" cols="10" rows="10" placeholder="Section Description" maxlength="200"></textarea>
		</div>
		<div class="form-group">
			<label class="sr-only" for="mode">The mode for the section</label>
			<select name="mode" id="mode"></select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>