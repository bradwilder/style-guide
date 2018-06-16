import Draggable from './Draggable';

const Modal_Arrange = (settings) =>
{
	const modal = $(settings.modalID);
	
	const saveOnClick = () =>
	{
		var sections = [];
		modal.find('.arrange-section__section').each(function(index)
		{
			var sectionStr = $(this).attr('data-id') + ':' + (index + 1); 
			sections.push(sectionStr);
		});
		var sectionData = sections.join();
		
		var idData = '';
		if (settings.setID)
		{
			idData = '&id=' + modal.find('[name=id]').val();
		}
		
		$.ajax
		({
			url: settings.url,
			type: 'POST',
			data: 'sections=' + sectionData + idData + '&action=arrange',
			success: function(data)
			{
				location.reload();
			}
		});
	}
	
	modal.on("modal-init", function(e, trigger)
	{
		modal.find('.draggable__container').remove();
		modal.find('.draggable__placeholder').remove();
		
		var ID = $(trigger).attr('data-id');
		
		var data = '';
		if (settings.getID)
		{
			data = 'id=' + ID;
		}
		
		if (settings.setID)
		{
			modal.find('[name=id]').val(ID);
		}
		
		$.ajax
		({
			url: settings.url,
			type: 'GET',
			data: data,
			success: function(data)
			{
				modal.find('.modal-body').append(data);
				
				var draggable = new Draggable();
				modal.find('.arrangeSectionsSave').click(saveOnClick);
			}
		});
	});
}

export default Modal_Arrange;
