<form data-url="/moodboardImage" data-action="add" method="post" role="form" enctype="multipart/form-data" data-refresh="false">
	<div class="modal-body">
		<div class="form-group">
			<label class="sr-only" for="file">The image file</label>
			<input type="file" class="form-control" name="file" id="file" accept="image/*" required/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="name-add-image">A label for the image</label>
			<input type="text" class="form-control iv_inputValidator" name="name" id="name-add-image" placeholder="Image Label" maxlength="50" required autocomplete="off"/>
		</div>
		<div class="form-group">
			<label class="sr-only" for="desc">A description for the image</label>
			<textarea class="form-control" name="desc" id="desc" cols="10" rows="10" placeholder="Image Description" maxlength="200" required></textarea>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
	</div>
</form>