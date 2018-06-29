class Modal_Font
{
	constructor()
	{
		this.addFontModal = $('#addFontModal');
		this.editFontmodal = $('#editFontModal');
		this.viewFontModal = $('#viewFontModal');
		this.events();
	}
	
	events()
	{
		var _this = this;
		this.addFontModal.on("modal-init", function(e)
		{
			var $modal = $(e.target);
			_this._getAlphabets($modal, '0');
			_this._getFontTypes($modal, '0');
		});
		
		this.addFontModal.find('[name=type]').change(function()
		{
			_this._changeFontType(this);
		});
		
		this.addFontModal.find('[name=upload]').change(function()
		{
			_this._changeUpload(this);
		});
		
		this.editFontmodal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var selectedID = $(trigger).closest('tr').attr('data-id');
			
			$modal.find('[name=font_id]').val(selectedID);
			
			function getFontSuccess(data)
			{
				var $nameInput = $modal.find('[name=name]');
				$nameInput.val(data.name);
				$nameInput.attr('data-except-self', selectedID);
				$nameInput.attr('data-original-value', data.name);
				
				if (data.type.code == 'web')
				{
					var $importURL = $modal.find('[name=importUrl]');
					$importURL.val(data.importURL).attr('data-original-value', data.importURL);
					
					var $website = $modal.find('[name=website]');
					$website.val(data.website).attr('data-original-value', data.website);
					
					var $webInput = $modal.find('.web-font-input');
					$webInput.show();
					$webInput.find('.required').prop('required', true);
					
					var $cssInput = $modal.find('.css-font-input');
					$cssInput.hide();
					$cssInput.find('.required').prop('required', false);
				}
				else if (data.type.code == 'css')
				{
					var $webInput = $modal.find('.web-font-input');
					$webInput.hide();
					$webInput.find('.required').prop('required', false);
					
					var $cssInput = $modal.find('.css-font-input');
					$cssInput.show();
					$cssInput.find('.required').prop('required', true);
					
					var $fileInput = $modal.find('.css-font-input--file');
					$fileInput.hide();
					$fileInput.find('.required').prop('required', false);
				}
				
				var selectedAlphabetID = 0;
				if (data.alphabet)
				{
					selectedAlphabetID = data.alphabet.id;
				}
				_this._getAlphabets($modal, selectedAlphabetID);
				
				var $alphabetSelect = $modal.find('[name=alphabet]');
				$alphabetSelect.attr('data-original-value', selectedAlphabetID);
			}
			
			_this._getFont(selectedID, getFontSuccess);
		});
		
		this.editFontmodal.find('[name=upload]').change(function()
		{
			_this._changeUpload(this);
		});
		
		this.viewFontModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var selectedID = $(trigger).closest('tr').attr('data-id');
			
			function getFontSuccess(data)
			{
				$modal.find('.view-font--name').text(data.name);
				
				if (data.alphabet)
				{
					$modal.find('.view-font--alphabet').text(data.alphabet.name);
				}
				
				$modal.find('.font-type-specific').hide();
				
				if (data.type.code == 'web')
				{
					$modal.find('.web-font').show();
					
					$modal.find('.view-font--url').text(data.importURL);
					$modal.find('.view-font--website').text(data.website).attr('href', data.website);
				}
				else if (data.type.code == 'css')
				{
					$modal.find('.css-font').show();
					
					$modal.find('.view-font--cssFile').text(data.cssFile);
					
					var $directoryDiv = $modal.find('.css-font--directory');
					if (data.directory)
					{
						$directoryDiv.show();
						$modal.find('.view-font--directory').text(data.directory);
					}
					else
					{
						$directoryDiv.hide();
					}
				}
			}
			
			_this._getFont(selectedID, getFontSuccess);
		});
	}
	
	_changeFontType(typeInput)
	{
		var value = typeInput.value;
		
		var $webInput = this.addFontModal.find('.web-font-input');
		var $cssInput = this.addFontModal.find('.css-font-input');
		
		if (value == '0')
		{
			$webInput.hide();
			$webInput.find('.required').prop('required', false);
			
			$cssInput.hide();
			$cssInput.find('.required').prop('required', false);
		}
		else if (value == 'css')
		{
			$webInput.hide();
			$webInput.find('.required').prop('required', false);
			
			$cssInput.show();
			$cssInput.find('.required').prop('required', true);
		}
		else if (value == 'web')
		{
			$webInput.show();
			$webInput.find('.required').prop('required', true);
			
			$cssInput.hide();
			$cssInput.find('.required').prop('required', false);
		}
	}
	
	_changeUpload(fileInput)
	{
		var file;
		if (fileInput.files)
		{
			file = fileInput.files[0];
		}
		var $fileFormGroup = $(fileInput).closest('.css-font-input').find('.css-font-input--file');
		
		if (file && file.type != 'text/css')
		{
			$fileFormGroup.show();
			$fileFormGroup.find('.required').prop('required', true);
		}
		else
		{
			$fileFormGroup.hide();
			$fileFormGroup.find('.required').prop('required', false);
		}
	}
	
	_getAlphabets($modal, selectedValue)
	{
		var $alphabetSelect = $modal.find('[name=alphabet]');
		$alphabetSelect.empty();
		
		$.ajax
		({
			url: '/fonts',
			type: 'GET',
			data: 'action=alphabets',
			dataType: 'json',
			success: function(alphabets)
			{
				// Add empty option
				$alphabetSelect.append('<option value="0"></option>');
				
				for (var i = 0; i < alphabets.length; i++)
				{
					var row = alphabets[i];
					$alphabetSelect.append('<option value="' + row["id"] + '">' + row["name"] + '</option>');
				}
				
				$alphabetSelect.val(selectedValue);
			}
		});
	}
	
	_getFontTypes($modal, selectedValue)
	{
		var _this = this;
		var $typeSelect = $modal.find('[name=type]');
		$typeSelect.empty();
		
		$.ajax
		({
			url: '/fonts',
			type: 'GET',
			data: 'action=fontTypes',
			dataType: 'json',
			success: function(fontTypes)
			{
				// Add empty option
				$typeSelect.append('<option value="0"></option>');
				
				for (var i = 0; i < fontTypes.length; i++)
				{
					var row = fontTypes[i];
					$typeSelect.append('<option value="' + row["code"] + '">' + row["description"] + '</option>');
				}
				
				$typeSelect.val(selectedValue);
				_this._changeFontType($modal.find('[name=type]')[0]);
				_this._changeUpload($modal.find('[name=upload]')[0]);
			}
		});
	}
	
	_getFont(fontID, successCallback)
	{
		$.ajax
		({
			url: '/fonts',
			type: 'GET',
			data: 'font_id=' + fontID + '&action=get',
			dataType: 'json',
			success: function(data)
			{
				successCallback(data);
			}
		});
	}
}

export default Modal_Font;