class Session
{
	constructor(cookie)
	{
		this.cookie = cookie;
	}
	
	setClientOffset()
	{
		var serverTime = Math.abs(this.cookie.getCookie('serverTime'));
		var clientTimeOffset = (new Date()).getTime() - serverTime;
		this.cookie.setCookie('clientTimeOffset', clientTimeOffset);
	}
	
	sessionTimeRemaining()
	{
		var sessionExpiry = Math.abs(this.cookie.getCookie('sessionExpiry'));
		var timeOffset = Math.abs(this.cookie.getCookie('clientTimeOffset'));
		var localTime = (new Date()).getTime();
		
		// 3 extra seconds to make sure
		return (sessionExpiry + 3000) - (localTime - timeOffset);
	}
}

export default Session;
