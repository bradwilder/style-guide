class Modal_Uploads
{
	constructor()
	{
		this.addFileModal = $('#addFileModal');
		this.editFileModal = $('#editFileModal');
		this.viewFileModal = $('#viewFileModal');
		this.addFolderModal = $('#addFolderModal');
		this.editFolderModal = $('#editFolderModal');
		this.events();
	}
	
	events()
	{
		var _this = this;
		this.addFileModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var folderID = $(trigger).attr('data-id');
			$modal.find('[name="folder_id"]').val(folderID);
			
			_this._changeUpload($modal.find('[name=upload]'));
		});
		
		this.addFileModal.find('[name=upload]').change(function()
		{
			_this._changeUpload(this);
		});
		
		this.editFileModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var uploadID = $(trigger).attr('data-id');
			$modal.find('[name="upload_id"]').val(uploadID);
			
			$.ajax
			({
				url: '/upload',
				type: 'GET',
				data: 'upload_id=' + uploadID + '&action=get',
				dataType: 'json',
				success: function(data)
				{
					var $nameInput = $modal.find('[name=name]');
					$nameInput.val(data.filePath);
					$nameInput.attr('data-original-value', data.filePath);
					
					var $shortNameInput = $modal.find('[name=shortName]');
					$shortNameInput.val(data.shortName);
					$shortNameInput.attr('data-original-value', data.shortName);
					
					var $fullNameInput = $modal.find('[name=longName]');
					$fullNameInput.val(data.fullName);
					$fullNameInput.attr('data-original-value', data.fullName);
				}
			});
		});
		
		this.editFileModal.find('[name=upload]').change(function()
		{
			_this._addUpload(this);
		});
		
		this.viewFileModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var uploadID = $(trigger).attr('data-id');
			
			$.ajax
			({
				url: '/upload',
				type: 'GET',
				data: 'upload_id=' + uploadID + '&action=get',
				dataType: 'json',
				success: function(data)
				{
					$modal.find('.view-upload--name').text(data.filePath);
					$modal.find('.view-upload--short-name').text(data.shortName);
					$modal.find('.view-upload--full-name').text(data.fullName);
				}
			});
		});
		
		this.addFolderModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var folderID = $(trigger).attr('data-id');
			$modal.find('[name="folder_id"]').val(folderID);
		});
		
		this.editFolderModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var folderID = $(trigger).attr('data-id');
			$modal.find('[name="folder_id"]').val(folderID);
			
			var name = $(trigger).attr('data-name');
			var $nameInput = $modal.find('[name=name]');
			$nameInput.val(name);
			$nameInput.attr('data-original-value', name);
		});
	}
	
	_changeUpload(fileInput)
	{
		var file;
		if (fileInput.files)
		{
			file = fileInput.files[0];
		}
		var $dependentFields = $(fileInput).closest('.modal-body').find('.dependent-fields');
		
		if (file)
		{
			$dependentFields.show();
			$dependentFields.find('.required').prop('required', true);
			
			$dependentFields.find('[name=name]').val(file.name);
		}
		else
		{
			$dependentFields.hide();
			$dependentFields.find('.required').prop('required', false);
		}
	}
	
	_addUpload(fileInput)
	{
		var file;
		if (fileInput.files)
		{
			file = fileInput.files[0];
		}
		var $modalBody = $(fileInput).closest('.modal-body');
		
		if (file)
		{
			$modalBody.find('[name=name]').val(file.name);
			$modalBody.find('[name=shortName]').val('');
			$modalBody.find('[name=longName]').val('');
		}
		else
		{
			$modalBody.find('[name=name]').val('');
			$modalBody.find('[name=shortName]').val('');
			$modalBody.find('[name=longName]').val('');
		}
	}
}

export default Modal_Uploads;