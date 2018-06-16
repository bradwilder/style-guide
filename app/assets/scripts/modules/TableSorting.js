class TableSorting
{
	constructor()
	{
		this.tables = $('.tables--sortable');
		this.init();
	}
	
	init()
	{
		this.tables.each(function()
		{
			$(this).tablesorter();
		});
	}
}

export default TableSorting;