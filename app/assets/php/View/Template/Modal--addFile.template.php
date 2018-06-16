<form data-url="/upload" data-action="uploadFile" method="post" role="form" enctype="multipart/form-data">
	<div class="modal-body">
		<input type="text" name="folder_id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="upload-add-file">The upload file</label>
			<input type="file" class="form-control required" name="upload" id="upload-add-file" accept="image/*" required/>
		</div>
		<div class="dependent-fields">
			<div class="form-group">
				<label class="sr-only" for="name-add-file">The file name</label>
				<input type="text" class="form-control required" name="name" id="name-add-file" placeholder="File Name" maxlength="50" required/>
			</div>
			<div class="form-group">
				<label class="sr-only" for="shortName-add-file">A short display name</label>
				<input type="text" class="form-control required" name="shortName" id="shortName-add-file" placeholder="Short Display Name" maxlength="200" required/>
			</div>
			<div class="form-group">
				<label class="sr-only" for="longName-add-file">A descriptive display name</label>
				<input type="text" class="form-control required" name="longName" id="longName-add-file" placeholder="More Descriptive Display Name" maxlength="200" required/>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>