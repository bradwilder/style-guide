<form data-url="/subsection" data-action="edit" method="post" role="form">
	<div class="modal-body">
		<input type="text" name="subsection_id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="name-edit-subsection">The subsection&#x27;s name</label>
			<input type="text" class="form-control required iv_inputValidator" name="name" id="name-edit-subsection" placeholder="Name" maxlength="50" data-initially-valid required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="desc">The subsection&#x27;s description</label>
			<input type="text" class="form-control" name="desc" id="desc" placeholder="Description" maxlength="200"/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>