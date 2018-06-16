class ResfreshSession
{
	constructor()
	{
		this.buttons = $('.refresh-session');
		this.events();
	}
	
	events()
	{
		this.buttons.click(function(e)
		{
			$.ajax
			({
				url: '/session',
				type: 'POST',
				data: 'action=refresh'
			});
		});
	}
}

export default ResfreshSession;
