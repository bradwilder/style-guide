class ColorLabels
{
	constructor()
	{
		this.colorTiles = $('.color-swatch__tile');
		this.BGs = $('.element-listing__bg');
		this.init();
		this.labelColors();
		this.changeBGOnClick();
	}
	
	init()
	{
		var _this = this;
		$.ajax
		({
			url: '/colors',
			type: 'GET',
			data: 'action=getDefaultColor',
			success: function(data)
			{
				_this._setBG('#' + data);
			}
		});
	}
	
	labelColors()
	{
		this.colorTiles.each(function()
		{
			var rgbStr = $(this).find(".color-swatch__color").css("background-color");
			var re = /\d+/g;
			var rgbArr = rgbStr.match(re);
			$(this).find(".card__label").text(rgbToHex(rgbArr));
		});
	}
	
	changeBGOnClick()
	{
		var BGs = this.BGs;
		this.colorTiles.each(function()
		{
			$(this).find(".color-swatch__color").click(function()
			{
				var rgbStr = $(this).css("background-color");
				BGs.css('background-color', rgbStr);
			});
		});
	}
	
	_setBG(rgbStr)
	{
		this.BGs.css('background-color', rgbStr);
	}
}

function componentToHex(c)
{
    var hex = c.toString(16).toLowerCase();
    return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(rgbArr)
{
	var r = parseInt(rgbArr[0]);
	var g = parseInt(rgbArr[1]);
	var b = parseInt(rgbArr[2]);
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

export default ColorLabels;