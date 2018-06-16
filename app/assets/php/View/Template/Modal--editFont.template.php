<form data-url="/fonts" data-action="edit" method="post" role="form" enctype="multipart/form-data">
	<div class="modal-body">
		<input type="text" name="font_id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="name-edit-font">The font&#x27;s name</label>
			<input type="text" class="form-control required iv_inputValidator" name="name" id="name-edit-font" placeholder="Name" maxlength="50" data-initially-valid required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="alphabet">The font&#x27;s alphabet</label>
			<select name="alphabet" id="alphabet"></select>
		</div>
		<div class="web-font-input">
			<div class="form-group">
				<label class="sr-only" for="importUrl">The font&#x27;s import URL</label>
				<input type="text" class="form-control required" name="importUrl" id="importUrl" placeholder="Import URL" maxlength="200" required/>
			</div>
			<div class="form-group">
				<label class="sr-only" for="website">The font&#x27;s website URL</label>
				<input type="text" class="form-control" name="website" id="website" placeholder="Website URL" maxlength="200"/>
			</div>
		</div>
		<div class="css-font-input">
			<div class="form-group">
				<label class="sr-only" for="upload-edit-font">The font&#x27;s upload</label>
				<input type="file" class="form-control" name="upload" id="upload-edit-font" accept="application/zip,application/x-zip,application/x-zip-compressed,application/octet-stream,text/css"/>
			</div>
			<div class="css-font-input--file form-group">
				<label class="sr-only" for="cssFile">The font&#x27;s CSS file</label>
				<input type="text" class="form-control required" name="cssFile" id="cssFile" placeholder="CSS File (e.g., font-awesome-4.7.0/css/font-awesome.min.css)" maxlength="200" required/>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>