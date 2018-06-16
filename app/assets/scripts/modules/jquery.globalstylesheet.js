(function($)
{
	var globalstylesheet = new function globalStylesheet()
	{
		if (!document.styleSheets)
		{
			alert("document.Stylesheets not found");
			return false;
		}
		
		var setrules = Array();
		
		// Set up a dummy node
		var cssNode = document.createElement('style');
		cssNode.type = 'text/css';
		cssNode.rel = 'stylesheet';
		cssNode.media = 'screen';
		cssNode.title = 'globalStyleSheet';
		document.getElementsByTagName("head")[0].appendChild(cssNode);
		
        // Find the new sheet
        var sheet = null;
		for (var i = 0; i < document.styleSheets.length; i++)
		{
			if (document.styleSheets[i].title == "globalStyleSheet")
			{
				sheet = document.styleSheets[i];
			}
		}
		
		this.setRule = function setRule(selector, ruleText)
		{
			if (setrules[selector] == undefined)
			{
				if (sheet.addRule)
				{
				    // IE
					sheet.addRule(selector, ruleText, 0);
				}
				else
				{
					sheet.insertRule(selector + '{' + ruleText + '}', 0);
				}
				setrules[selector] = this.getRule(selector);
			}
			return setrules[selector];
		}
		
		this.getRule = function getRule(selector)
		{
			if (setrules[selector] != undefined)
			{
				return setrules[selector];
			}
			else
			{
				var rules = allRules();
				for (var i = 0; i < rules.length; i++)
				{
					if (rules[i].selectorText == selector)
					{
						return rules[i];
					}
				}
			}
			return false;
		}
		
        this.deleteRule = function deleteRule(selector)
        {
            if (setrules[selector] != undefined)
            {
                var rules = allRules();
				for (var i = 0; i < rules.length; i++)
				{
					if (rules[i].selectorText == selector)
					{
                        if (sheet.deleteRule)
                        {
                            sheet.deleteRule(i);
                        }
                        else
                        {
                            // IE
                            sheet.removeRule(i);
                        }
                        
                        setrules[selector] = undefined;
                        return true;
					}
				}
            }
            return false;
        }
        
		function allRules()
		{
            return sheet.cssRules ? /* IE */ sheet.cssRules : sheet.rules;
		}
		
		this.print = function print()
		{
			var styleinfo = "";
			var rules = allRules();
			for (var i = 0; i < rules.length; i++)
			{
				styleinfo += rules[i].cssText + "\n";
			}
			return styleinfo;
		}
		
		// use jQuery's css selector function to set the style object
		this.css = function css(selector, key, value)
		{
			var rule = this.setRule(selector, key + ":" + value + ";");
			$(rule).css(key, value);
		}
	}
	
	$.fn.extend({globalcss: function globalcss(selector, key, value) {globalstylesheet.css(selector, key, value);}});
    
    $.fn.globalcssDelete = function(selector)
    {
    	globalstylesheet.deleteRule(selector);
    };
}(jQuery));
