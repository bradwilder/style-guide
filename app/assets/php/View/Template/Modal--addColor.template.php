<form data-url="/colors" data-action="add" method="post" role="form">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="name-add-color">The color&#x27;s name</label>
			<input type="text" class="form-control iv_inputValidator" name="name" id="name-add-color" placeholder="Name" maxlength="50" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="hex-add-color">The color&#x27;s hex value</label>
			<input type="text" class="form-control" name="hex" id="hex-add-color" placeholder="Hex (e.g., CCCCCC)" maxlength="6" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="var1-add-color">The color&#x27;s first variant hex value</label>
			<input type="text" class="form-control" name="var1" id="var1-add-color" placeholder="Variant 1 Hex (e.g., CCCCCC)" maxlength="6"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="var2-add-color">The color&#x27;s second variant hex value</label>
			<input type="text" class="form-control" name="var2" id="var2-add-color" placeholder="Variant 2 Hex (e.g., CCCCCC)" maxlength="6"/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>