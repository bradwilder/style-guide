(function($)
{
	$.extend
    ({
    	inputvalidator: new function()
        {
            this.defaults =
            {
            	initiallyValid: false
            };
            
            this.construct = function(settings)
            {
                return this.each(function()
                {
                	if (this.iv_config == null)
                    {
                    	this.iv_config = {};
                    }
                    
                    var iv_config = $.extend(this.iv_config, $.inputvalidator.defaults, settings);
                    
                    $(this).addClass(inputValidatorClass);
                    
                    if (this.iv_config.initiallyValid || this.hasAttribute('data-initially-valid'))
                    {
                    	$(this).valid();
                    }
                    else
                    {
                    	$(this).invalid();
                    }
                });
            };
        }
    });
    
    // Extend plugin scope
    $.fn.extend({inputvalidator: $.inputvalidator.construct});
    
    var classNamePrefix = "iv_";
    var inputValidatorClass = classNamePrefix + "inputValidator";
    var validClass = classNamePrefix + "valid";
    
    $('body').globalcss('.' + inputValidatorClass, "color", "red !important");
    
    $('body').globalcss('.' + inputValidatorClass + '.' + validClass, "color", "green !important");
    
    $.fn.invalid = function()
    {
    	this.removeClass(validClass);
    };
    
    $.fn.valid = function()
    {
    	this.addClass(validClass);
    };
    
    $.fn.isValid = function()
    {
    	return this.hasClass(validClass);
    };
} (jQuery));
