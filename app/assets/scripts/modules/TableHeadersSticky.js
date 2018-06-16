class TableHeadersSticky
{
	constructor()
	{
		this.tables = $('.tables--sticky-header');
		this.init();
	}
	
	init()
	{
		this.tables.each(function()
		{
			$(this).stickyTableHeaders({scrollableArea: $(this).closest('.tables--sticky-header__wrapper'), stickHeaders: true});
		});
	}
}

export default TableHeadersSticky;