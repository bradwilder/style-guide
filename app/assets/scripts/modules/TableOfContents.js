class TableOfContents
{
	constructor()
	{
		this.TOCs = $('.table-of-contents');
		var _this = this;
		$(document).ready(function()
		{
			_this.events();
		});
	}
	
	events()
	{
		this.TOCs.find('li a').click(function(event)
		{
			event.preventDefault();
			$($(this).attr('href'))[0].scrollIntoView();
			
			var offset = 49; // Height of header (minus 1)
			scrollBy(0, -offset);
		});
	}
}

export default TableOfContents;