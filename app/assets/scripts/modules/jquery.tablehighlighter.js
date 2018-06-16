(function($)
{
	function getScrollbarHeight(divEl)
	{
		// Allow a tolerance of 1 in the difference because these values may be rounded
		if (Math.abs(divEl.scrollWidth - divEl.clientWidth) <= 1)
		{
			return 0;
		}
		
	    var outer = document.createElement("div");
	    outer.style.visibility = "hidden";
	    outer.style.height = "100px";
	    divEl.appendChild(outer);
	    
	    var heightNoScroll = outer.offsetHeight;
	    // force scrollbars
	    outer.style.overflow = "scroll";
	    
	    var inner = document.createElement("div");
	    inner.style.height = "100%";
	    outer.appendChild(inner);        
	    
	    var heightWithScroll = inner.offsetHeight;
	    
	    outer.parentNode.removeChild(outer);
	    
	    return heightNoScroll - heightWithScroll;
	}
	
	function getBounds(element)
	{
		var bounds = element.offset();
        bounds.right = bounds.left + element.outerWidth();
        bounds.bottom = bounds.top + element.outerHeight();
        return bounds;
	}
	
    $.fn.isPartiallyOnScreenVertically = function(div, topOffset)
    {
    	var win = ((typeof div !== 'undefined') ? div : $(window));
    	var winTopOffset = ((typeof topOffset !== 'undefined') ? topOffset : 0);
    	var bounds = getBounds(this);
        
        return !(bounds.bottom < (win.offset().top + winTopOffset) || bounds.top > (win.offset().top + win.height() - getScrollbarHeight(win.get(0))));
    };
    
    $.fn.isFullyOnScreenVertically = function(div, topOffset)
    {
    	var win = ((typeof div !== 'undefined') ? div : $(window));
    	var winTopOffset = ((typeof topOffset !== 'undefined') ? topOffset : 0);
	    var bounds = getBounds(this);
        
        return !(bounds.top < (win.offset().top + winTopOffset) || bounds.bottom > (win.offset().top + win.height() - getScrollbarHeight(win.get(0))));
    };
    
    $.extend
    ({
        tablehighlighter: new function()
        {
        	var classPrefix = "th_";
        	var highlightClass = classPrefix + "highlight";
        	var highlightLastClass = classPrefix + "highlightLead";
        	
            this.defaults =
            {
                highlightRowCallback: null,
                unhighlightRowCallback: null,
                highlightLeadRowCallback: null,
                unhighlightLeadRowCallback: null,
                supportDelete: false,
                deleteRowCallback: null,
                highlightColor: "#dda0dd",
                highlightLastBorder: "1px double #000000",
                hoverEnabled: true,
                hoverColor: "#EEEEEE",
                hoverHighlightedOpacity: .7,
                supportMultiSelect: false,
                upDownOffscreenStyle: "jump",
                hasFixedRowHeights: false,
                hasStickyHeader: false
            };
            
            function highlightRow(row)
            {
            	row.addClass(highlightClass);
            }
            
            function unhighlightRow(row)
            {
            	row.removeClass(highlightClass);
            }
            
            function highlightLeadRow(row)
            {
            	row.addClass(highlightLastClass);
            }
            
            function unhighlightLeadRow(row)
            {
            	row.removeClass(highlightLastClass);
            }
            
            function deselectRow(row, tableHighlighter)
            {
            	if (isRowHighlighted(row))
                {
            		unhighlightRow(row);
            		
            		var tableConfig = tableHighlighter.get(0).config;
            		
                	if (tableConfig.unhighlightRowCallback)
               	    {
                		tableConfig.unhighlightRowCallback(row);
               	    }
                	
                	if (isRowHighlightedLead(row))
                	{
                		unhighlightLeadRow(row);
                    	if (tableConfig.unhighlightLeadRowCallback)
                   	    {
                    		tableConfig.unhighlightLeadRowCallback(row);
                   	    }
                	}
                }
            }
            this.deselectRow = deselectRow;
            
            function isRowHighlighted(row)
            {
            	return row.hasClass(highlightClass);
            }
            
            function isRowHighlightedLead(row)
            {
            	return row.hasClass(highlightLastClass);
            }
            
            function findHighlighted(tableHighlighter)
            {
            	return tableHighlighter.find("." + highlightClass);
            }
            
            function findLeadHighlighted(tableHighlighter)
            {
            	return tableHighlighter.find("." + highlightLastClass);
            }
            
            function getHighlighted(tableHighlighter)
            {
            	return tableHighlighter.find("tbody tr").filter("." + highlightClass);
            }
            this.getHighlighted = getHighlighted;
            
            function getLeadHighlighted(tableHighlighter)
            {
            	return tableHighlighter.find("tbody tr").filter("." + highlightLastClass);
            }
            this.getLeadHighlighted = getLeadHighlighted;
            
            function removeLeadHighlightedProperty(rows)
            {
            	rows.removeClass(highlightLastClass);
            }
            
            function deleteRowsSuccessCallback(highlightedRows, tableHighlighter)
            {
            	highlightedRows.each(function()
            	{
            		deselectRow($(this), tableHighlighter)
            	});
            	
                highlightedRows.remove();
            }
            this.deleteRowsSuccessCallback = deleteRowsSuccessCallback;
            
            var upDownJumpOffsetFactor = 3/4;
            function jumpUp(tableDiv, prev, config)
            {
            	var headerHeight = tableDiv.find('thead').outerHeight();
            	
            	var topOffset = 0;
            	if (config.hasStickyHeader)
            	{
            		topOffset = headerHeight;
            	}
            	
            	if (!prev.isFullyOnScreenVertically(tableDiv, topOffset))
            	{
            		var newScrollTop = 0;
            		if (!config.hasFixedRowHeights)
            		{
            			prev.prevAll(":visible").each(function()
                        {
                        	newScrollTop += $(this).outerHeight();
                        });
                        newScrollTop = newScrollTop - (upDownJumpOffsetFactor * tableDiv.outerHeight() - .5 * prev.outerHeight()) + headerHeight;
            		}
            		else
            		{
            			var rowHeight = prev.outerHeight();
            			var rowsPerWindow = tableDiv.outerHeight() / rowHeight;
            			newScrollTop = ((prev.index() - prev.prevAll(":hidden").length) * rowHeight) - (((rowsPerWindow * upDownJumpOffsetFactor) - 1) * rowHeight) + headerHeight;
            		}
            		
            		if (newScrollTop <= headerHeight)
            		{
            			newScrollTop = 0;
            		}
            		
            		if (config.hasStickyHeader)
            		{
            			newScrollTop = newScrollTop - headerHeight;
            		}
            		
    	            tableDiv.scrollTop(newScrollTop);
            	}
            }
            
            function jumpDown(tableDiv, next, config)
            {
            	if (!next.isFullyOnScreenVertically(tableDiv))
            	{
            		var headerHeight = tableDiv.find('thead').outerHeight();
            		var horizontalScrollbarHeight = getScrollbarHeight(tableDiv.get(0));
            		
            		var newScrollTop = 0;
            		if (!config.hasFixedRowHeights)
            		{
            			next.prevAll(":visible").each(function()
            			{
            				newScrollTop += $(this).outerHeight();
            			});
            			newScrollTop = newScrollTop - ((1 - upDownJumpOffsetFactor) * tableDiv.outerHeight() - .5 * next.outerHeight()) + headerHeight + horizontalScrollbarHeight;
            		}
            		else
            		{
            			var rowHeight = next.outerHeight();
            			var rowsPerWindow = tableDiv.outerHeight() / rowHeight;
            			newScrollTop = ((next.index() - next.prevAll(":hidden").length) * rowHeight) - ((rowsPerWindow * (1 - upDownJumpOffsetFactor)) * rowHeight) + headerHeight + horizontalScrollbarHeight;// + 1;
            		}
            		
    	            tableDiv.scrollTop(newScrollTop);
            	}
            }
            
            function singleUp(tableDiv, prev, config)
            {
            	var headerHeight = tableDiv.find('thead').outerHeight();
            	
            	var topOffset = 0;
            	if (config.hasStickyHeader)
            	{
            		topOffset = headerHeight;
            	}
            	
            	if (!prev.isFullyOnScreenVertically(tableDiv, topOffset))
            	{
            		var newScrollTop = 0;
            		if (!config.hasFixedRowHeights)
            		{
            			prev.prevAll(":visible").each(function()
            			{
            				newScrollTop += $(this).outerHeight();
            			});
            			newScrollTop += headerHeight;
            		}
            		else
            		{
            			var rowHeight = prev.outerHeight();
            			newScrollTop = (prev.index() - prev.prevAll(":hidden").length) * rowHeight + headerHeight;
            		}
            		
            		if (newScrollTop <= headerHeight)
            		{
            			newScrollTop = 0;
            		}
            		
            		if (config.hasStickyHeader)
            		{
            			newScrollTop = newScrollTop - headerHeight;
            		}
            		
    	            tableDiv.scrollTop(newScrollTop);
            	}
            }
            
            function singleDown(tableDiv, next, config)
            {
            	if (!next.isFullyOnScreenVertically(tableDiv))
            	{
            		var headerHeight = tableDiv.find('thead').outerHeight();
            		var horizontalScrollbarHeight = getScrollbarHeight(tableDiv.get(0));
            		
            		var newScrollTop = 0;
            		if (!config.hasFixedRowHeights)
            		{
            			next.prevAll(":visible").each(function()
            			{
            				newScrollTop += $(this).outerHeight();
            			});
            			newScrollTop = newScrollTop - tableDiv.outerHeight() + next.outerHeight() + headerHeight + horizontalScrollbarHeight;
            		}
            		else
            		{
            			var rowHeight = next.outerHeight();
            			var rowsPerWindow = tableDiv.outerHeight() / rowHeight;
            			newScrollTop = ((next.index() - next.prevAll(":hidden").length) * rowHeight) - ((rowsPerWindow - 1) * rowHeight) + headerHeight + horizontalScrollbarHeight;// + 1;
            		}
                    
                    tableDiv.scrollTop(newScrollTop);
            	}
            }
            
            this.construct = function(settings)
            {
                return this.each(function()
                {
                    if (this.config == null)
                    {
                    	this.config = {};
                    }
                    var config = $.extend(this.config, $.tablehighlighter.defaults, settings);
                    
                    switch (config.upDownOffscreenStyle)
                	{
                		case "jump":
                			this.config.upDownOffscreen = {up: jumpUp, down: jumpDown};
                			break;
                		case "single":
                			this.config.upDownOffscreen = {up: singleUp, down: singleDown};
                			break;
                	}
					
                    var tableSelector = $(this).attr('id');
                    $('body').globalcss('#' + tableSelector + ' tr.' + highlightClass, "background", this.config.highlightColor);
                    $('body').globalcss('#' + tableSelector + ' tr.' + highlightLastClass, "border", this.config.highlightLastBorder);
                    
                    if (this.config.hoverEnabled)
                    {
                        $('body').globalcss('#' + tableSelector + ' tbody tr:hover:not(.' + highlightClass + ')', "background-color", this.config.hoverColor);
                        $('body').globalcss('#' + tableSelector + ' tbody tr:hover.' + highlightClass, "opacity", this.config.hoverHighlightedOpacity);
                    }
                    else
                    {
                    	$('body').globalcssDelete('#' + tableSelector + ' tbody tr:hover:not(.' + highlightClass + ')');
                        $('body').globalcssDelete('#' + tableSelector + ' tbody tr:hover.' + highlightClass);
                    }
                    
                    if (this.tabIndex < 0)
                    {
                    	this.tabIndex = 0;
                    }
                    
                    // Handle row clicks
                    $(this).on("click", "tbody tr", function(e)
                    {
                    	var row = $(this);
                    	var table = row.closest('table');
                    	
                    	var isHighlighted = isRowHighlighted(row);
                    	var isLeadHighlighted = isRowHighlightedLead(row);
                    	
                        if ((e.ctrlKey || e.metaKey) && (config.supportMultiSelect || isHighlighted))
                        {
                            if (isHighlighted)
                            {
                            	unhighlightRow(row);
                            	if (config.unhighlightRowCallback)
                           	    {
                           	        config.unhighlightRowCallback(row);
                           	    }
                            	
                            	if (isLeadHighlighted)
                            	{
                            		unhighlightLeadRow(row);
                                	if (config.unhighlightLeadRowCallback)
                               	    {
                               	        config.unhighlightLeadRowCallback(row);
                               	    }
                            	}
                            }
                            else
                            {
                            	var leadHighlightedRow = findLeadHighlighted(table);
                            	unhighlightLeadRow(leadHighlightedRow);
                            	if (config.unhighlightLeadRowCallback)
                           	    {
                           	        config.unhighlightLeadRowCallback(leadHighlightedRow);
                           	    }
                            	
                            	highlightRow(row);
                            	if (config.highlightRowCallback)
                           	    {
                           	        config.highlightRowCallback(row);
                           	    }
                            	
                            	highlightLeadRow(row);
                            	if (config.highlightLeadRowCallback)
                           	    {
                           	        config.highlightLeadRowCallback(row);
                           	    }
                            }
                        }
                        else
                        {
                        	var highlightedRows = findHighlighted(table).not(row);
                        	unhighlightRow(highlightedRows);
                        	if (config.unhighlightRowCallback && highlightedRows.length > 0)
                       	    {
                       	        config.unhighlightRowCallback(highlightedRows);
                       	    }
                        	
                        	var leadHighlightedRow = findLeadHighlighted(table).not(row);
                        	unhighlightLeadRow(leadHighlightedRow);
                        	if (config.unhighlightLeadRowCallback && leadHighlightedRow.length > 0)
                       	    {
                       	        config.unhighlightLeadRowCallback(leadHighlightedRow);
                       	    }
                        	
                        	if (!isHighlighted)
                        	{
                        		highlightRow(row);
                                if (config.highlightRowCallback)
                           	    {
                           	        config.highlightRowCallback(row);
                           	    }
                        	}
                        	
                        	if (!isLeadHighlighted)
                        	{
                        		highlightLeadRow(row);
                            	if (config.highlightLeadRowCallback)
                           	    {
                           	        config.highlightLeadRowCallback(row);
                           	    }
                        	}
                        }
                    });
                    
                    // Up/down/delete key mapping
                    $(this).on('keydown', function(e)
                    {
                    	var table = $(this).closest('table');
                    	var allHighlighted = findHighlighted(table);
                    	if (allHighlighted.length > 0)
                    	{
                    		var lastHighlighted = findLeadHighlighted(table);
                    		var tableDiv = $(this).closest('div');
                    		
                            switch (e.which)
                    	    {
                                case 38:
                                	// Up-arrow
                                	var prev;
                                	if (lastHighlighted.length > 0)
                                	{
                                		prev = lastHighlighted.prevAll(':visible:first');
                                	}
                                	else
                                	{
                                		prev = allHighlighted.first().prevAll(':visible:first');
                                	}
                                	
                                	if (prev.length > 0)
                                	{
                                		this.config.upDownOffscreen.up(tableDiv, prev, this.config);
                                		
                                		prev.trigger('click');
                                	}
                                	e.preventDefault();
                                	break;
                                case 40:
                                	// Down arrow
                                	var next;
                                	if (lastHighlighted.length > 0)
                                	{
                                		next = lastHighlighted.nextAll(':visible:first');
                                	}
                                	else
                                	{
                                		next = allHighlighted.last().nextAll(':visible:first');
                                	}
                                	
                                	if (next.length > 0)
                                	{
                                		this.config.upDownOffscreen.down(tableDiv, next, this.config);
                                		
                                		next.trigger('click');
                                	}
                                	e.preventDefault();
                                	break;
                                case 8:
                                	// Delete key
                                	if (e.metaKey && config.supportDelete)
                                	{
                                		// TODO: use modal instead of confirm
                                		var shouldDelete = confirm('Are you sure you want to delete the selection?');
                                		if (shouldDelete)
                                		{
                                		    if (config.deleteRowCallback)
                                		    {
                                		        config.deleteRowCallback(allHighlighted);
                                		    }
                                		}
                                	}
                                	break;
                            }
                        }
                    });
                });
            };
        }
    });
    
    // Extend plugin scope
    $.fn.extend({tablehighlighter: $.tablehighlighter.construct});
    
    $.fn.deselectRow = function(row)
    {
    	$.tablehighlighter.deselectRow(row, this);
    };
    
    $.fn.deleteRowsSuccessCallback = function(rows)
    {
    	$.tablehighlighter.deleteRowsSuccessCallback(rows, this);
    };
    
    $.fn.getHighlighted = function()
    {
    	return $.tablehighlighter.getHighlighted(this);
    };
    
    $.fn.getLeadHighlighted = function()
    {
    	return $.tablehighlighter.getLeadHighlighted(this);
    };
} (jQuery));
