class Popover
{
	constructor()
	{
		this.buttons = $('[data-popover-trigger]');
		this.events();
	}
	
	events()
	{
		this.buttons.each(function()
		{
			var ID = $(this).attr('data-popover-source-id');
			var targetSelector = $(this).attr('data-popover-trigger');
			$(this).popover
			({
				container: 'body',
				html: true,
				content: function()
				{
					return $(targetSelector).html();
				},
				placement: "left",
				trigger: "focus",
				template: '<div class="popover" data-id="' + ID +  '" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content type__title popover-list"></div></div>'
			});
		});
	}
}

export default Popover;