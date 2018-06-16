import Vue from 'vue';
import $ from 'jquery';

Vue.component('config-item',
{
	template: '#config-item-template',
	data: function()
	{
		return {
			selectedItemComponent: null
		};
	},
	props:
	{
		initialData: Object,
		selectedCode: String
	},
	methods:
	{
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to delete this item?"))
			{
				$.ajax
				({
					url: '/item',
					type: 'POST',
					data: 'item_id=' + _this.initialData.id + '&action=delete',
					success: function()
					{
						location.reload();
					}
				});
			}
		}
	},
	mounted: function()
	{
		switch (this.selectedCode)
		{
			case 'color-var-desc':
			case 'color-var':
			case 'color-desc':
			case 'color':
			case 'color-pal':
				this.selectedItemComponent = 'colors-item';
				break;
			case 'font-fmy':
				this.selectedItemComponent = 'font-family-item';
				break;
			case 'font-tbl':
				this.selectedItemComponent = 'font-table-item';
				break;
			case 'icons-css':
				this.selectedItemComponent = 'icon-listing-item';
				break;
			case 'elem-seg':
				this.selectedItemComponent = 'segmented-element-item';
				break;
		}
	}
});
