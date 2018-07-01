import EditableSection from './EditableSection';

class EditSection
{
	constructor()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('edit-section__trigger'))
			{
				var sectionID = $(e.target).closest('.popover').attr('data-ID');
				var $section = $('#page-section-' + sectionID);
				
				let section = new EditableSection($section);
				section.edit();
				
				e.preventDefault();
			}
		});
	}
}

export default EditSection;
