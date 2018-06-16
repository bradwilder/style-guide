class Draggable
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		var dragSrcEl = null;
		
		$('.draggables__container').on('dragstart', function(e)
		{
			if ($(e.target).hasClass('draggable'))
			{
				var $draggableElement = $(e.target);
				$draggableElement.addClass("moving");
				
				$draggableElement.closest('.draggables__container').addClass("moving");
				
				dragSrcEl = e.target;
				
				e.dataTransfer = e.originalEvent.dataTransfer;
				e.dataTransfer.effectAllowed = 'move';
				e.dataTransfer.setData('text/html', e.target.innerHTML);
			}
		});

		$('.draggables__container').on('dragover', function(e)
		{
			if ($(e.target).hasClass('draggable'))
			{
				e.preventDefault();
				e.dataTransfer = e.originalEvent.dataTransfer;
				e.dataTransfer.dropEffect = 'move';
				return false;
			}
		});

		$('.draggables__container').on('dragenter', function(e)
		{
			if ($(e.target).hasClass('draggable'))
			{
				var $container = $(dragSrcEl).closest('.draggable__container');
				var $prevPlaceholder = $container.find('.draggable__placeholder');
				var $nextContainer = $container.next('.draggable__container');
				var $nextPlaceholder = $nextContainer.length > 0 ? $nextContainer.find('.draggable__placeholder') : $container.next('.draggable__placeholder');
				
				if (dragSrcEl !== e.target && $prevPlaceholder[0] !== e.target && $nextPlaceholder[0] !== e.target)
				{
					var $draggableHoverTarget = $(e.target);
					$draggableHoverTarget.addClass("over");
				}
			}
		});

		$('.draggables__container').on('dragleave', function(e)
		{
			if ($(e.target).hasClass('draggable'))
			{
				var $container = $(dragSrcEl).closest('.draggable__container');
				var $prevPlaceholder = $container.find('.draggable__placeholder');
				var $nextContainer = $container.next('.draggable__container');
				var $nextPlaceholder = $nextContainer.length > 0 ? $nextContainer.find('.draggable__placeholder') : $container.next('.draggable__placeholder');
				
				if (dragSrcEl !== e.target && $prevPlaceholder[0] !== e.target && $nextPlaceholder[0] !== e.target)
				{
					var $draggableHoverTarget = $(e.target);
					$draggableHoverTarget.removeClass("over");
				}
			}
		});

		$('.draggables__container').on('drop', function(e)
		{
			if ($(e.target).hasClass('draggable'))
			{
				e.stopPropagation();
				
				if (dragSrcEl !== e.target && $(dragSrcEl).prev('.draggable__placeholder')[0] !== e.target && $(dragSrcEl).next('.draggable__placeholder')[0] !== e.target)
				{
					if ($(e.target).hasClass('draggable__placeholder'))
					{
						var $container = $(dragSrcEl).closest('.draggable__container').detach();
						var $targetContainer = $(e.target).closest('.draggable__container');
						var $target = $targetContainer.length > 0 ? $targetContainer : $(e.target);
						$container.insertBefore($target);
					}
					else
					{
						dragSrcEl.innerHTML = e.target.innerHTML;
						e.target.innerHTML = e.originalEvent.dataTransfer.getData('text/html');
						
						var srcID = $(dragSrcEl).attr('data-id');
						var targetID = $(e.target).attr('data-id');
						
						$(dragSrcEl).attr('data-id', targetID);
						$(e.target).attr('data-id', srcID);
					}
				}
				
				return false;
			}
		});

		$('.draggables__container').on('dragend', function(e)
		{
			if ($(e.target).hasClass('draggable'))
			{
				var $container = $(e.target).closest('.draggables__container');

				$container.removeClass("moving");

				$container.find('.draggable').removeClass("over");
				$container.find('.draggable').removeClass("moving");
			}
		});
	}
}

export default Draggable;