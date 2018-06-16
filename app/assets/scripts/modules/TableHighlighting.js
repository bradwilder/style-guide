class TableHighlighting
{
	constructor()
	{
		this.tables = $('.tables--selectable');
		this.editInputs = $('.tables__edit-button');
		this.init();
	}
	
	init()
	{
		var _this = this;
		
		this.tables.each(function()
		{
			$(this).tablehighlighter({highlightLeadRowCallback: _this._highlightLeadCB, unhighlightLeadRowCallback: _this._unhighlightLeadCB, supportDelete: false, supportMultiSelect: false, hasFixedRowHeights: false, hasStickyHeader: false, upDownOffscreenStyle: 'single'});
		});
		
		this.editInputs.addClass('tables__edit-button--hide');
	}
	
	_highlightLeadCB(row)
	{
		row.find('.tables__edit-button').removeClass('tables__edit-button--hide');
		row.find('.tables__display-button').removeClass('tables__edit-button--hide');
		
		row.find('.tables__display-button').addClass('tables__edit-button--hide');
	}
	
	_unhighlightLeadCB(row)
	{
		row.find('.tables__edit-button').removeClass('tables__edit-button--hide');
		row.find('.tables__display-button').removeClass('tables__edit-button--hide');
		
		row.find('.tables__edit-button').addClass('tables__edit-button--hide');
	}
}

export default TableHighlighting;