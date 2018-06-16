<form data-url="/fontTableItem" data-action="addListingCSS" method="post" role="form">
	<input type="text" name="listing_id" style="display: none;">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="css-add-listing">The CSS</label>
			<input type="text" class="form-control required" name="css" id="css-add-listing" placeholder="CSS" maxlength="200" required/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>