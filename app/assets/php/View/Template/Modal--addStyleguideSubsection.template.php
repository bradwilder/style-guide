<form data-url="/subsection" data-action="add" method="post" role="form">
	<div class="modal-body">
		<input type="text" name="section_id" style="display: none;">
		<input type="text" name="parent_subsection_id" style="display: none;">
		<div class="form-group">
			<label class="sr-only" for="name-add-subsection">The subsection&#x27;s name</label>
			<input type="text" class="form-control required iv_inputValidator" name="name" id="name-add-subsection" placeholder="Name" maxlength="50" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="desc-add-subsection">The subsection&#x27;s description</label>
			<input type="text" class="form-control" name="desc" id="desc-add-subsection" placeholder="Description" maxlength="200"/>
		</div>
		<div class="form-group">
			<label for="enabled-add-subsection">Enabled
				<input type="checkbox" name="enabled" id="enabled-add-subsection" placeholder="Enabled" checked/>
			</label>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>