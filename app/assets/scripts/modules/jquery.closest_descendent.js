(function($)
{
	$.fn.closest_descendent = function(filter)
	{
		var $found = $();
		var $currentSet = this;
		while ($currentSet.length)
		{
			$found = $currentSet.filter(filter);
			if ($found.length)
			{
				break;
			}
			$currentSet = $currentSet.children();
		}
		return $found.first();
	}
})(jQuery);