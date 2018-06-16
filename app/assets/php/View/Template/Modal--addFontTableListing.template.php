<form data-url="/fontTableItem" data-action="addListing" method="post" role="form">
	<input type="text" name="item_id" style="display: none;">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="listing-add-font">The Sample Text</label>
			<input type="text" class="form-control required" name="listing" id="listing-add-font" placeholder="Sample text" maxlength="200" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="font_id-add-listing">The font</label>
			<select name="font_id" id="font_id-add-listing"></select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>