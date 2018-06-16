import Vue from 'vue';
import $ from 'jquery';

Vue.component('font-family-item',
{
	template: '#font-family-item-template',
	data: () =>
	{
		return {
			data: null
		};
	},
	props:
	{
		initialData: Object
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
					data: 'item_id=' + _this.data.id + '&action=delete',
					success: function()
					{
						location.reload();
					}
				});
			}
		}
	},
	created: function()
	{
		this.data = this.initialData;
	}
});