import EditableSection from './EditableSection';

class EditSection
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		var _this = this;
		
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('edit-section__trigger'))
			{
				var sectionID = $(e.target).closest('.popover').attr('data-ID');
				var $section = $('#section-' + sectionID);
				
				_this.editableSection = new EditableSection($section);
				_this.editableSection.edit();
				
				e.preventDefault();
			}
			
			if ($(e.target).hasClass('edit-section__done'))
			{
				if (_this.editableSection)
				{
					var $section = _this.editableSection.section();
					
					_this.editableSection.endEdit();
					_this.editableSection = null;
					
					var sectionID = $section.find('[data-popover-source-id]').attr("data-popover-source-id");
					var name = $section.find('input[name="name"]').val();
					var desc = $section.find('input[name="desc"]').val();
					
					$.ajax
					({
						url: '/moodboardSection',
						type: 'POST',
						data: 'section_id=' + sectionID + '&name=' + encodeURIComponent(name) + '&desc=' + encodeURIComponent(desc) + '&action=edit',
						success: function(data)
						{
							location.reload();
						}
					});
				}
			}
		});
	}
}

export default EditSection;
