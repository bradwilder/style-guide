import Draggable from './Draggable';

class Modal_ArrangeMoodboardSectionImages
{
	constructor()
	{
		this.modals = $('#arrangeMoodboardSectionImagesModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			$modal.find('.draggable__container').remove();
			$modal.find('.draggable__placeholder').remove();
			
			var ID = $(trigger).closest('.popover').attr('data-id');
			
			$modal.find('[name=id]').val(ID);
			
			$.ajax
			({
				url: '/moodboardSectionImagesDraggable',
				type: 'GET',
				data: 'id=' + ID,
				success: function(data)
				{
					$modal.find('.modal-body').append(data);
					
					var draggable = new Draggable();
					$modal.find('.arrangeSectionsSave').click(saveOnClick);
				}
			});
		});
	}
}

function saveOnClick()
{
	var sections = [];
	$(this).closest('.modal').find('.arrange-section__section').each(function(index)
	{
		var sectionStr = $(this).attr('data-id') + ':' + (index + 1); 
		sections.push(sectionStr);
	});
	var sectionData = sections.join();
	
	var id = $(this).closest('.modal').find('[name=id]').val();
	
	$.ajax
	({
		url: '/moodboardSectionImagesDraggable',
		type: 'POST',
		data: 'sections=' + sectionData + '&id=' + id + '&action=arrange',
		success: function(data)
		{
			location.reload();
		}
	});
}

export default Modal_ArrangeMoodboardSectionImages;


/*
// TODO: use this once I figure out how to get the ID from trigger instead of the surrounding .popover
import Modal_Arrange from './Modal_Arrange';

var settings =
{
	url: '/moodboardSectionImagesDraggable',
	getID: true,
	setID: true,
	modalID: '#arrangeMoodboardSectionImagesModal'
}

const Modal_ArrangeMoodboardSectionImages = Modal_Arrange(settings);

export default Modal_ArrangeMoodboardSectionImages;
*/