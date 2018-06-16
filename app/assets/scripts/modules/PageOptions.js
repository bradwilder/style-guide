import Vue from 'vue';
import $ from 'jquery';
import TableSorting from './TableSorting';
import TableHighlighting from './TableHighlighting';

class PageOptions
{
	constructor()
	{
		$.ajax
		({
			url: '/pageOption',
			type: 'GET',
			data: 'action=getShowTOCList',
			dataType: 'json',
			success: function(data)
			{
				new Vue
				({
					el: '.page-options-container',
					data:
					{
						tableData: data
					},
					mounted: function()
					{
						new TableSorting();
						new TableHighlighting();
					}
				});
			}
		});
	}
}

export default PageOptions;

Vue.component('page-options-table',
{
	template: '#page-options-table-template',
	props:
	{
		tableData: Array
	}
});

Vue.component('page-options-table-row',
{
	template: '#page-options-table-row-template',
	data: () =>
	{
		return {
			myModel: null
		};
	},
	props:
	{
		model: Object
	},
	created: function()
	{
		this.myModel = this.model;
	},
	methods:
	{
		clickTOC: function(event)
		{
			var _this = this;
			
			var oldValue = !event.target.checked;
			
			if (confirm('Are you sure you want to toggle the table of contents for this page?'))
			{
				$.ajax
				({
					url: '/pageOption',
					type: 'POST',
					data: 'page_code=' + _this.myModel.code + '&show=' + (event.target.checked ? '1' : '0') + '&action=setShowTOC',
					success: function(data)
					{
						_this.myModel.value = event.target.checked;
					},
					error: function()
					{
						// TODO: Not sure why I had to do this
						$(event.target).prop('checked', oldValue);
					}
				});
			}
			else
			{
				event.preventDefault();
				return false;
			}
		}
	}
});
