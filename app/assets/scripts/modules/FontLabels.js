class FontLabels
{
	constructor()
	{
		this.fontListings = $('.font-listing');
		this.labelFonts();
		this.stripeFonts();
	}
	
	labelFonts()
	{
		this.fontListings.each(function()
		{
			var example = $(this).find(".font-listing__example > p");
			
			var font = example.css("font-family");
			var matches = font.match(/".*"/g);
			font = matches[0].replace(/"/g, '');
			
			var addFontWeight = true;
			var fontWeight = example.css("font-weight");
			if (fontWeight == 400 || fontWeight == "normal")
			{
				addFontWeight = false;
				font = font + " - Normal";
			}
			else if (fontWeight == 700 || fontWeight == "bold")
			{
				addFontWeight = false;
				font = font + " - Bold";
			}
			
			var fontSize = example.css("font-size");
			var fontSizeNoPx = parseFloat(fontSize.replace('px', ''));
			var fontSizeEm = (fontSizeNoPx / 16).toFixed(3);
			fontSizeEm = fontSizeEm + 'em';
			
			var lineHeight = example.css("line-height");
			lineHeight = parseFloat(lineHeight.replace('px', ''));
			lineHeight = (lineHeight / fontSizeNoPx).toFixed(1);
			
			var listingData = fontSize + ' (' + fontSizeEm + ') / ' + lineHeight;
			
			$(this).find(".font-listing__typeface").text(font);
			$(this).find(".font-listing__data").text(listingData);
			
			if (addFontWeight)
			{
				$(this).find(".font-listing__label").append("<p class='font-listing__item font-listing__font-weight'></p>");
				$(this).find(".font-listing__font-weight").text('font-weight: ' + fontWeight);
			}
			
			// TODO: put these into an array; simplify!!!
			var fontStyle = example.css("font-style");
			if (fontStyle != 'normal')
			{
				$(this).find(".font-listing__label").append("<p class='font-listing__item font-listing__font-style'></p>");
				$(this).find(".font-listing__font-style").text('font-style: ' + fontStyle);
			}
			
			var letterSpacing = example.css("letter-spacing");
			if (letterSpacing != 0)
			{
				$(this).find(".font-listing__label").append("<p class='font-listing__item font-listing__letter-spacing'></p>");
				$(this).find(".font-listing__letter-spacing").text('letter-spacing: ' + letterSpacing);
			}
			
			var textDecoration = example.css("text-decoration");
			textDecoration = textDecoration.substring(0, textDecoration.indexOf(" "));
			if (textDecoration != 'none')
			{
				$(this).find(".font-listing__label").append("<p class='font-listing__item font-listing__text-decoration'></p>");
				$(this).find(".font-listing__text-decoration").text('text-decoration: ' + textDecoration);
			}
			
			var textTransform = example.css("text-transform");
			if (textTransform != 'none')
			{
				$(this).find(".font-listing__label").append("<p class='font-listing__item font-listing__text-transform'></p>");
				$(this).find(".font-listing__text-transform").text('text-transform: ' + textTransform);
			}
		});
	}
	
	stripeFonts()
	{
		this.fontListings.each(function(index)
		{
			if (index % 2 == 0)
			{
				$(this).addClass("font-listing--stripe-even");
			}
		});
	}
}

export default FontLabels;