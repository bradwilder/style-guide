import Session from './Session';
import Cookie from './Cookie';

class Logout
{
	constructor()
	{
		this.logouts = $('.logout__submit');
		
		this.init();
		this.events();
	}
	
	init()
	{
		let cookie = new Cookie();
		let session = new Session(cookie);
		session.setClientOffset();
		checkSession(session);
	}
	
	events()
	{
		this.logouts.click(function()
		{
			logoutSubmit();
		});
	}
}

const checkSession = (session) =>
{
	var diff = session.sessionTimeRemaining();
	
	if (diff <= 0)
	{
		logoutSubmit();
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
		
		setTimeout(checkSession.bind(null, session), 10000);
	}
}

const logoutSubmit = () =>
{
	$.ajax
	({
		url: '/user',
		type: 'POST',
		data: 'action=logout',
		success: function()
		{
			window.location="/login?message=" + encodeURIComponent('You have been logged out successfully.');
		}
	});
}

export default Logout;
