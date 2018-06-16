class EditableSection
{
	constructor($section)
	{
		this.$section = $section;
		this.overlay = $('.editable-section__overlay');
		this.events();
	}
	
	edit()
	{
		var _this = this;
		if (_this.$section)
		{
			_this.$section.find('.editable-section__button-edit').removeClass('editable-section__button--show');
			_this.$section.find('.editable-section__button-done').addClass('editable-section__button--show');
			_this.$section.find('.editable-section__hide').hide();
			
			_this.$section.addClass('editable-section--editing');
			
			_this.$section.find('input.editable-section__input[type="text"]').each(function()
			{
				var $display = _this.$section.find('.editable-section__display[data-editable="' + $(this).attr('name') + '"]');
				$(this).val($display.html());
			});
			
			_this.$section.find('textarea.editable-section__input').each(function()
			{
				var $display = _this.$section.find('.editable-section__display[data-editable="' + $(this).attr('name') + '"]');
				$(this).html($display.html());
			});
			
			_this.$section.find('.editable-section__input').first().focus();
			_this.overlay.css('height', $('body')[0].scrollHeight).fadeIn(300);
		}
	}
	
	endEdit()
	{
		if (this.$section)
		{
			this.$section.find('.editable-section__button-edit').addClass('editable-section__button--show');
			this.$section.find('.editable-section__button-done').removeClass('editable-section__button--show');
			this.$section.find('.editable-section__hide').show();
			
			this.$section.removeClass('editable-section--editing');
			this.overlay.fadeOut(300);
		}
		
		this.$section = null;
	}
	
	events()
	{
		var _this = this;
		
		_this.overlay.click(function(e)
		{
			_this.endEdit();
		});
	}
	
	section()
	{
		return this.$section;
	}
}

export default EditableSection;
