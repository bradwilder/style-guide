<form data-url="/upload" data-action="editFolder" method="post" role="form">
	<div class="modal-body">
		<input type="text" name="folder_id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="name-edit-folder">The folder name</label>
			<input type="text" class="form-control required" name="name" id="name-edit-folder" placeholder="Folder Name" maxlength="50" required/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>