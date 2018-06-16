class Menu
{
	constructor()
	{
		this.menus = $('.menu');
		this.init();
	}
	
	init()
	{
		this.menus.each(function()
		{
			var $a = $(this).find('a[href="' + window.location.pathname + '"]');
			$a.parents('li').addClass('active');
			$a.html($a.html() + ' <span class="sr-only">(current)</span>');
		});
	}
}

export default Menu;
