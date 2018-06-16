<form data-url="/iconTableItem" data-action="addListing" method="post" role="form">
	<input type="text" name="item_id" style="display: none;">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="listing-add-icon">The HTML</label>
			<input type="text" class="form-control required" name="listing" id="listing-add-icon" placeholder="HTNL" maxlength="200" required/>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>