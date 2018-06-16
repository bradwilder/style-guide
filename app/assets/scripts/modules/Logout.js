import Session from './Session';

class Logout
{
	constructor()
	{
		this.logouts = $('.logout__submit');
		this.session = new Session();
		
		this.init();
		this.events();
	}
	
	init()
	{
		this.session.setClientOffset();
		this.checkSession();
	}
	
	events()
	{
		var _this = this;
		this.logouts.click(function()
		{
			_this.logoutSubmit().bind(_this);
		});
	}
	
	checkSession()
	{
		var diff = this.session.sessionTimeRemaining();
		
		if (diff <= 0)
		{
			this.logoutSubmit();
		}
		else
		{
			var secondsTotal = Math.ceil(diff / 1000);
			
			//var hours = Math.floor(secondsTotal / 3600);
			//var minutes = Math.floor((secondsTotal % 3600) / 60);
			//var seconds = secondsTotal % 60;
			
			//var time = (hours > 0 ? hours + ":" : "") + (minutes >= 10 ? minutes : "0" + minutes) + ":" + (seconds >= 10 ? seconds : "0" + seconds) 
			//console.log(time);
			
			var timeEst;
			if (secondsTotal >= 3600)
			{
				timeEst = Math.floor(secondsTotal / 3600) + "h";
			}
			else if (secondsTotal >= 60)
			{
				timeEst = Math.floor(secondsTotal / 60) + "m";
			}
			else if (secondsTotal > 45)
			{
				timeEst = "60s";
			}
			else if (secondsTotal > 30)
			{
				timeEst = "45s";
			}
			else if (secondsTotal > 15)
			{
				timeEst = "30s";
			}
			else
			{
				timeEst = "15s";
			}
			$(".user-session-remaining").text("[" + timeEst + "]");
			
			setTimeout(this.checkSession.bind(this), 10000);
		}
	}
	
	logoutSubmit()
	{
		$.ajax
		({
			url: '/user',
			type: 'POST',
			data: 'action=logout',
			success: function(data)
			{
				window.location="/login?message=" + encodeURIComponent('You have been logged out successfully.');
			}
		});
	}
}

export default Logout;
