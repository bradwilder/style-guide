class Cookie
{
	getCookie(name)
	{
		var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
		if (match)
		{
			return match[2];
		}
	}
	
	setCookie(name, value, days)
	{
		var expires = "";
		if (days)
		{
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + value + expires + "; path=/";
	}
	
	deleteCookie(name)
	{
		this.setCookie(name, '', -1);
	}
}

export default Cookie;