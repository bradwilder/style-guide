require('./Cookie.js');

class Session
{
	setClientOffset()
	{
		var serverTime = Math.abs(getCookie('serverTime'));
		var clientTimeOffset = (new Date()).getTime() - serverTime;
		setCookie('clientTimeOffset', clientTimeOffset);
	}
	
	sessionTimeRemaining()
	{
		var sessionExpiry = Math.abs(getCookie('sessionExpiry'));
		var timeOffset = Math.abs(getCookie('clientTimeOffset'));
		var localTime = (new Date()).getTime();
		
		// 3 extra seconds to make sure
		return (sessionExpiry + 3000) - (localTime - timeOffset);
	}
}

export default Session;
