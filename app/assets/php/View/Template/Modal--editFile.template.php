<form data-url="/upload" data-action="editFile" method="post" role="form" enctype="multipart/form-data">
	<div class="modal-body">
		<input type="text" name="upload_id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="upload-edit-file">The upload file</label>
			<input type="file" class="form-control" name="upload" id="upload-edit-file" accept="image/*"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="name-edit-file">The file name</label>
			<input type="text" class="form-control required" name="name" id="name-edit-file" placeholder="File Name" maxlength="50" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="shortName">A short display name</label>
			<input type="text" class="form-control required" name="shortName" id="shortName" placeholder="Short Display Name" maxlength="200" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="longName">A descriptive display name</label>
			<input type="text" class="form-control required" name="longName" id="longName" placeholder="More Descriptive Display Name" maxlength="200" required/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>