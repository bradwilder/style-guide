import Vue from 'vue';

Vue.component('columns',
{
	template: '#columns-template',
	props:
	{
		model: Object,
		itemId: Number
	}
});

Vue.component('columns-row',
{
	template: '#columns-row-template',
	props:
	{
		model: Object,
		index: Number
	}
});
