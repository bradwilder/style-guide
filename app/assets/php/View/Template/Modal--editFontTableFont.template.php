<form data-url="/fontTableItem" data-action="editListingFont" method="post" role="form">
	<input type="text" name="listing_id" style="display: none;">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="font_id">The font</label>
			<select name="font_id" id="font_id"></select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>