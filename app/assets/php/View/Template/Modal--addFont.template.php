<form data-url="/fonts" data-action="add" method="post" role="form" enctype="multipart/form-data">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="name-add-font">The font&#x27;s name</label>
			<input type="text" class="form-control required iv_inputValidator" name="name" id="name-add-font" placeholder="Name" maxlength="50" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="alphabet-add-font">The font&#x27;s supported alphabet</label>
			<select name="alphabet" id="alphabet-add-font"></select>
		</div>
		<div class="form-group">
			<label class="sr-only" for="type-add-font">The font&#x27;s type</label>
			<select name="type" id="type-add-font"></select>
		</div>
		<div class="web-font-input">
			<div class="form-group">
				<label class="sr-only" for="importUrl-add-font">The font&#x27;s import URL</label>
				<input type="text" class="form-control required" name="importUrl" id="importUrl-add-font" placeholder="Import URL" maxlength="200" required/>
			</div>
			<div class="form-group">
				<label class="sr-only" for="website-add-font">The font&#x27;s website URL</label>
				<input type="text" class="form-control" name="website" id="website-add-font" placeholder="Website URL" maxlength="200"/>
			</div>
		</div>
		<div class="css-font-input">
			<div class="form-group">
				<label class="sr-only" for="upload-add-font">The font&#x27;s upload</label>
				<input type="file" class="form-control required" name="upload" id="upload-add-font" accept="application/zip,application/x-zip,application/x-zip-compressed,application/octet-stream,text/css" required/>
			</div>
			<div class="css-font-input--file form-group">
				<label class="sr-only" for="cssFile-add-font">The font&#x27;s CSS file</label>
				<input type="text" class="form-control required" name="cssFile" id="cssFile-add-font" placeholder="CSS File (e.g., font-awesome-4.7.0/css/font-awesome.min.css)" maxlength="200" required/>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>