require('./jquery.metadata.js');

(function ($)
{
    $.extend
    ({
        tablesorter: new function()
        {
            var parsers = [];
            var widgets = [];
            
            this.defaults =
            {
                cssHeader: "header",
                cssAsc: "headerSortUp",
                cssDesc: "headerSortDown",
                cssChildRow: "expand-child",
                sortInitialOrder: "asc",
                sortMultiSortKey: "shiftKey",
                sortForce: null,
                sortAppend: null,
                sortLocaleCompare: true,
                textExtraction: "simple",
                parsers: {},
                widgets: [],
                widgetZebra: {css: ["even", "odd"]},
                headers: {},
                widthFixed: false,
                cancelSelection: true,
                sortList: [],
                dateFormat: "us",
                decimal: '/\.|\,/g',
                onRenderHeader: null,
                selectorHeaders: 'thead th',
                debug: false
            };
            
            // Debugging utils
            this.benchmark = function(msg, date)
            {
                log(msg + ", " + (new Date().getTime() - date.getTime()) + "ms");
            }
            
            function log(msg)
            {
                if (typeof console != "undefined" && typeof console.debug != "undefined")
                {
                    console.log(msg);
                }
                else
                {
                    alert(msg);
                }
            }
            
            // Parser utils
            function buildParserCache(table, $headers)
            {
                if (table.tBodies.length == 0)
                {
                    return;
                }
                
                if (table.config.debug)
                {
                    var parsersDebug = "";
                }
                
                var list = [];
                var rows = table.tBodies[0].rows;
                if (rows[0])
                {
                    for (var i = 0; i < rows[0].cells.length; i++)
                    {
                        var p = false;

                        if ($.metadata && ($($headers[i]).metadata() && $($headers[i]).metadata().sorter))
                        {
                            p = getById(parsers, $($headers[i]).metadata().sorter);
                        }
                        else if ((table.config.headers[i] && table.config.headers[i].sorter))
                        {
                            p = getById(parsers, table.config.headers[i].sorter);
                        }
                        
                        if (!p)
                        {
                            p = detectParserForColumn(table, rows, i);
                        }
                        
                        if (table.config.debug)
                        {
                            parsersDebug += "column:" + i + " parser:" + p.id + "\n";
                        }
                        
                        list.push(p);
                    }
                }
                
                if (table.config.debug)
                {
                    log(parsersDebug);
                }
                
                return list;
            }
            
            function detectParserForColumn(table, rows, cellIndex)
            {
                var node = false;
                var nodeValue = false;
                var keepLooking = true;
                var rowIndex = 0;
                while (nodeValue == '' && keepLooking)
                {
                    if (rows[rowIndex])
                    {
                        node = rows[rowIndex].cells[cellIndex];
                        nodeValue = $.trim(getElementText(table.config, node));
                        if (table.config.debug)
                        {
                            log('Checking if value was empty on row: ' + rowIndex);
                        }
                    }
                    else
                    {
                        keepLooking = false;
                    }
                    rowIndex++;
                }
                
                for (var i = 1; i < parsers.length; i++)
                {
                    if (parsers[i].is(nodeValue, table, node))
                    {
                        return parsers[i];
                    }
                }
                
                // 0 is always the generic parser (text)
                return parsers[0];
            }
            
            // Utils
            function buildCache(table)
            {
                if (table.config.debug)
                {
                    var cacheTime = new Date();
                }
                
                var cache = {row: [], normalized: []};
                
                var totalRows = (table.tBodies[0] && table.tBodies[0].rows.length) || 0;
                for (var i = 0; i < totalRows; ++i)
                {
                    // Add the table data to main data array
                    // if this is a child row, add it to the last row's children and continue to the next row
                    var c = $(table.tBodies[0].rows[i]);
                    if (c.hasClass(table.config.cssChildRow))
                    {
                        cache.row[cache.row.length - 1] = cache.row[cache.row.length - 1].add(c);
                        continue;
                    }
                    
                    cache.row.push(c);
                    
                    var cols = [];
                    var totalCells = (table.tBodies[0].rows[0] && table.tBodies[0].rows[0].cells.length) || 0;
                    for (var j = 0; j < totalCells; ++j)
                    {
                        cols.push(table.config.parsers[j].format(getElementText(table.config, c[0].cells[j]), table, c[0].cells[j]));
                    }
                    
                    cols.push(cache.normalized.length); // add position for rowCache
                    cache.normalized.push(cols);
                };
                
                if (table.config.debug)
                {
                    benchmark("Building cache for " + totalRows + " rows: ", cacheTime);
                }
                
                return cache;
            }

            function getElementText(config, node)
            {
                if (!node)
                {
                    return "";
                }
                
                if (!config.supportsTextContent)
                {
                    config.supportsTextContent = node.textContent || false;
                }
                
                var text = "";
                if (config.textExtraction == "simple")
                {
                    if (config.supportsTextContent)
                    {
                        text = node.textContent;
                    }
                    else
                    {
                        if (node.childNodes[0] && node.childNodes[0].hasChildNodes())
                        {
                            text = node.childNodes[0].innerHTML;
                        }
                        else
                        {
                            text = node.innerHTML;
                        }
                    }
                }
                else
                {
                    if (typeof(config.textExtraction) == "function")
                    {
                        text = config.textExtraction(node);
                    }
                    else
                    {
                        text = $(node).text();
                    }
                }
                return text;
            }
            
            function appendToTable(table, cache)
            {
                if (table.config.debug)
                {
                    var appendTime = new Date()
                }
                
                var checkCell = (cache.normalized[0].length - 1);
                for (var i = 0; i < cache.normalized.length; i++)
                {
                    var cacheRow = cache.row[cache.normalized[i][checkCell]];
                    
                    for (var j = 0; j < cacheRow.length; j++)
                    {
                        $(table.tBodies[0])[0].appendChild(cacheRow[j]);
                    }
                }
                
                if (table.config.debug)
                {
                    benchmark("Rebuilt table: ", appendTime);
                }
                
                applyWidgets(table);
                setTimeout(function() {$(table).trigger("sortEnd");}, 0);
            }
            
            function buildHeaders(table)
            {
                if (table.config.debug)
                {
                    var time = new Date();
                }
                
                var header_index = computeTableHeaderCellIndexes(table);
                
                var $tableHeaders = $(table.config.selectorHeaders, table).each(function(index)
                {
                    this.column = header_index[this.parentNode.rowIndex + "-" + this.cellIndex];
                    
                    if (checkHeaderDisabledMetadata(this) || checkHeaderDisabledOptions(table, index))
                    {
                        this.sortDisabled = true;
                    }
                    
                    var locked = checkHeaderOptionsSortingLocked(table, index);
					if (locked)
                    {
                        this.lockedOrder = locked;
                    }
                    
                    if (!this.sortDisabled)
                    {
                        $(this).addClass(table.config.cssHeader);
                        if (table.config.onRenderHeader)
                        {
                            table.config.onRenderHeader.apply($(this));
                        }
                    }
                });
                
                if (table.config.debug)
                {
                    benchmark("Built headers: ", time);
                    log($tableHeaders);
                }
                
                return $tableHeaders;
            }
            
            function computeTableHeaderCellIndexes(t)
            {
                var matrix = [];
                var lookup = {};
                var trs = t.getElementsByTagName('THEAD')[0].getElementsByTagName('TR');
                
                for (var i = 0; i < trs.length; i++)
                {
                    var cells = trs[i].cells;
                    for (var j = 0; j < cells.length; j++)
                    {
                        var cell = cells[j];
                        
                        var rowIndex = cell.parentNode.rowIndex;
                        var cellId = rowIndex + "-" + cell.cellIndex;
                        var rowSpan = cell.rowSpan || 1;
                        var colSpan = cell.colSpan || 1;
                        var firstAvailCol;
                        if (typeof(matrix[rowIndex]) == "undefined")
                        {
                            matrix[rowIndex] = [];
                        }
                        
                        // Find first available column in the first row
                        for (var k = 0; k < matrix[rowIndex].length + 1; k++)
                        {
                            if (typeof(matrix[rowIndex][k]) == "undefined")
                            {
                                firstAvailCol = k;
                                break;
                            }
                        }
                        lookup[cellId] = firstAvailCol;
                        for (var k = rowIndex; k < rowIndex + rowSpan; k++)
                        {
                            if (typeof(matrix[k]) == "undefined")
                            {
                                matrix[k] = [];
                            }
                            var matrixrow = matrix[k];
                            for (var l = firstAvailCol; l < firstAvailCol + colSpan; l++)
                            {
                                matrixrow[l] = "x";
                            }
                        }
                    }
                }
                return lookup;
            }
            
            function checkHeaderDisabledMetadata(cell)
            {
                return (($.metadata) && ($(cell).metadata().sorter === false));
            }

            function checkHeaderDisabledOptions(table, i)
            {
                return (table.config.headers[i] && (table.config.headers[i].sorter === false));
            }
			
            function checkHeaderOptionsSortingLocked(table, i)
            {
                return (table.config.headers[i] && table.config.headers[i].lockedOrder);
            }
            
            function applyWidgets(table)
            {
                for (var i = 0; i < table.config.widgets.length; i++)
                {
                    getById(widgets, table.config.widgets[i]).format(table);
                }
            }
            
            function getById(collection, name)
            {
                for (var i = 0; i < collection.length; i++)
                {
                    if (collection[i].id.toLowerCase() == name.toLowerCase())
                    {
                        return collection[i];
                    }
                }
                return false;
            }
            
            function formatSortingOrder(v)
            {
                if (typeof(v) != "Number")
                {
                    return (v.toLowerCase() == "desc") ? 1 : 0;
                }
                else
                {
                    return (v == 1) ? 1 : 0;
                }
            }
            
            function isValueInArray(v, a)
            {
                for (var i = 0; i < a.length; i++)
                {
                    if (a[i][0] == v)
                    {
                        return true;
                    }
                }
                return false;
            }
            
            function setHeadersCss(table, $headers)
            {
                // Remove all header information
                $headers.removeClass(table.config.cssDesc).removeClass(table.config.cssAsc);
                
                var h = [];
                $headers.each(function()
                {
                    if (!this.sortDisabled)
                    {
                        h[this.column] = $(this);
                    }
                });
                
                var sortList = table.config.sortList;
                for (var i = 0; i < sortList.length; i++)
                {
                    h[sortList[i][0]].addClass(sortList[i][1] == 0 ? table.config.cssDesc : table.config.cssAsc);
                }
            }
            
            function fixColumnWidth(table, $headers)
            {
                if (table.config.widthFixed)
                {
                    var colgroup = $('<colgroup>');
                    $("tr:first td", table.tBodies[0]).each(function()
                    {
                        colgroup.append($('<col>').css('width', $(this).width()));
                    });
                    $(table).prepend(colgroup);
                }
            }
            
            // Sorting methods
            function multisort(table, cache)
            {
                if (table.config.debug)
                {
                    var sortTime = new Date();
                }
                
                var sortList = table.config.sortList;
                
				var sortWrapper = null;
				
                var dynamicExp = "sortWrapper = function(a, b) {";
                
                for (var i = 0; i < sortList.length; i++)
                {
                    var c = sortList[i][0];
                    var order = sortList[i][1];
                    var s = (table.config.parsers[c].type == "text") ? ((order == 0) ? makeSortFunction("text", "asc", c) : makeSortFunction("text", "desc", c)) : ((order == 0) ? makeSortFunction("numeric", "asc", c) : makeSortFunction("numeric", "desc", c));
                    var e = "e" + i;
                    
                    dynamicExp += "var " + e + " = " + s;
                    dynamicExp += "if (" + e + ") {return " + e + ";} ";
                    dynamicExp += "else {";
                }
                
                // If value is the same keep original order
                var orgOrderCol = cache.normalized[0].length - 1;
                dynamicExp += "return a[" + orgOrderCol + "] - b[" + orgOrderCol + "];";
                
                for (var i = 0; i < sortList.length; i++)
                {
                    dynamicExp += "} ";
                }
                
                dynamicExp += "}";
                
                if (table.config.debug)
                {
                    var evalTime = new Date();
                }
                
                eval(dynamicExp);
                
                if (table.config.debug)
                {
                    benchmark("Evaling expression: " + dynamicExp, evalTime);
                }
                
                cache.normalized.sort(sortWrapper);
                
                if (table.config.debug)
                {
                    benchmark("Sorting on " + sortList.toString() + " and dir " + order + " time: ", sortTime);
                }
                
                return cache;
            }
            
            function makeSortFunction(type, direction, index)
            {
                var a = "a[" + index + "]";
                var b = "b[" + index + "]";
                if (type == 'text' && direction == 'asc')
                {
                    return "(" + a + " == " + b + " ? 0 : (" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : (" + a + " < " + b + ") ? -1 : 1 )));";
                }
                else if (type == 'text' && direction == 'desc')
                {
                    return "(" + a + " == " + b + " ? 0 : (" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : (" + b + " < " + a + ") ? -1 : 1 )));";
                }
                else if (type == 'numeric' && direction == 'asc')
                {
                    return "(" + a + " === null && " + b + " === null) ? 0 : (" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : " + a + " - " + b + "));";
                }
                else if (type == 'numeric' && direction == 'desc')
                {
                    return "(" + a + " === null && " + b + " === null) ? 0 : (" + a + " === null ? Number.POSITIVE_INFINITY : (" + b + " === null ? Number.NEGATIVE_INFINITY : " + b + " - " + a + "));";
                }
            }
            
            function init(element, headers)
            {
                element.config.parsers = buildParserCache(element, headers);
                return buildCache(element);
            }
            
            // Public methods
            this.construct = function(settings)
            {
                return this.each(function()
                {
                    if (!this.tHead || !this.tBodies)
                    {
                        return;
                    }
                    
                    this.config = {};
                    var config = $.extend(this.config, $.tablesorter.defaults, settings);
                    
                    var $this = $(this);
                    
                    // save the settings where they read
                    $.data(this, "tablesorter", config);
                    
                    var $headers = buildHeaders(this);
                    
                    var cache = init(this, $headers);
                    
                    // fixate columns if the users supplies the fixedWidth option
                    fixColumnWidth(this);
                    
                    $headers.click(function(e)
                    {
                        var totalRows = ($this[0].tBodies[0] && $this[0].tBodies[0].rows.length) || 0;
                        if (!this.sortDisabled && totalRows > 0)
                        {
                            var curColSort;
                            for (var i = 0; i < config.sortList.length; i++)
                            {
                                var s = config.sortList[i];
                                if (s[0] == this.column)
                                {
                                    curColSort = s;
                                }
                            }
                            
                            // Get column sort order
                            var order;
							if (this.lockedOrder)
                            {
                                order = this.lockedOrder;
                            }
                            else
                            {
                                if (curColSort)
                                {
                                    order = (curColSort[1] + 1) % 2;
                                }
                                else
                                {
                                    order = config.sortList.length > 0 ? config.sortList[config.sortList.length - 1][1] : formatSortingOrder(config.sortInitialOrder);
                                }
                            }
							
                            if (!e[config.sortMultiSortKey])
                            {
                                // Single-column sorting
                                config.sortList = [];
                                if (config.sortForce != null)
                                {
                                    var a = config.sortForce;
                                    for (var j = 0; j < a.length; j++)
                                    {
                                        if (a[j][0] != this.column)
                                        {
                                            config.sortList.push(a[j]);
                                        }
                                    }
                                }
                                config.sortList.push([this.column, order]);
                            }
                            else
                            {
                                // Multi-column sorting
                                if (curColSort)
                                {
                                    // The user has clicked on an already sorted column; update the sorting direction
                                    curColSort[1] = order;
                                }
                                else
                                {
                                    // Add column to sort list array
                                    config.sortList.push([this.column, order]);
                                }
                            }
                            
                            setHeadersCss($this[0], $headers);
                            $this.trigger("sortStart");
                            
                            setTimeout(function()
                            {
                                appendToTable($this[0], multisort($this[0], cache));
                            }, 1);
                            
                            // Stop normal event by returning false
                            return false;
                        }
                    }).mousedown(function()
                    {
                        // cancel selection
                        if (config.cancelSelection)
                        {
                            this.onselectstart = function() {return false;};
                            return false;
                        }
                    });
                    
                    $this.bind("update", function(e)
                    {
                    	cache = init(e.target, $headers);
                    }).bind("updateCell", function(e, cell)
                    {
                        // get position from the dom.
                        var pos = [(cell.parentNode.rowIndex - 1), cell.cellIndex];
                        
                        // update cache
                        cache.normalized[pos[0]][pos[1]] = this.config.parsers[pos[1]].format(getElementText(this.config, cell), cell);
                    }).bind("sorton", function(e, list)
                    {
                        if ($this.find('tbody tr').length > 0)
                        {
                            config.sortList = list;
                            setHeadersCss(this, $headers);
                            $(this).trigger("sortStart");
                            
                            var table = this;
                            
                            setTimeout(function()
                            {
                                appendToTable(table, multisort(table, cache));
                            }, 1);
                        }
                    }).bind("sort", function(e)
                    {
                        if ($this.find('tbody tr').length > 0)
                        {
                            setHeadersCss(this, $headers);
                            $(this).trigger("sortStart");
                            
                            var table = this;
                            
                            setTimeout(function()
                            {
                                appendToTable(table, multisort(table, cache));
                            }, 1);
                        }
                    }).bind("appendCache", function()
                    {
                        appendToTable(this, cache);
                    }).bind("applyWidgetId", function(e, id)
                    {
                        getById(widgets, id).format(this);
                    }).bind("applyWidgets", function()
                    {
                        applyWidgets(this);
                    });
                    
                    if ($.metadata && ($this.metadata() && $this.metadata().sortList))
                    {
                        config.sortList = $this.metadata().sortList;
                    }
                    
                    if ($.metadata && ($this.metadata() && $this.metadata().widgets))
                    {
                        config.widgets = $this.metadata().widgets;
                    }
                    
                    // User-supplied sort list
                    if (config.sortList.length > 0)
                    {
                        $this.trigger("sorton", [config.sortList]);
                    }
                    
                    applyWidgets(this);
                });
            };
            
            this.addParser = function(parser)
            {
                if (!getById(parsers, parser.id))
                {
                    parsers.push(parser);
                }
            };
            
            this.addWidget = function(widget)
            {
                if (!getById(widgets, widget.id))
                {
                    widgets.push(widget);
                }
            };
            
            this.formatFloat = function(s)
            {
                var i = parseFloat(s);
                return (isNaN(i) ? 0 : i);
            };
            
            this.formatInt = function(s)
            {
                var i = parseInt(s);
                return (isNaN(i) ? 0 : i);
            };
            
            this.isDigit = function(s)
            {
                // Replace all an wanted chars and match.
                return /^[-+]?\d*$/.test($.trim(s.replace(/[,.']/g, '')));
            };
        }
    });
    
    // Extend plugin scope
    $.fn.extend({tablesorter: $.tablesorter.construct});
    
    // Add default parsers
    $.tablesorter.addParser
    ({
        id: "text",
        is: function(s)
        {
            return true;
        },
        format: function(s)
        {
            return $.trim(s.toLocaleLowerCase());
        },
        type: "text"
    });
    
    $.tablesorter.addParser
    ({
        id: "digit",
        is: function(s)
        {
            return $.tablesorter.isDigit(s);
        },
        format: function(s)
        {
            return $.tablesorter.formatFloat(s);
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "currency",
        is: function(s)
        {
            return /^[£$€?.]/.test(s);
        },
        format: function(s)
        {
            return $.tablesorter.formatFloat(s.replace(new RegExp(/[£$€]/g), ""));
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "ipAddress",
        is: function(s)
        {
            return /^\d{2,3}[\.]\d{2,3}[\.]\d{2,3}[\.]\d{2,3}$/.test(s);
        },
        format: function(s)
        {
            var a = s.split(".");
            var r = "";
            for (var i = 0; i < a.length; i++)
            {
                var item = a[i];
                r += (((item.length == 2) ? "0" : "") + item);
            }
            return $.tablesorter.formatFloat(r);
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "url",
        is: function(s)
        {
            return /^(https?|ftp|file):\/\/$/.test(s);
        },
        format: function(s)
        {
            return jQuery.trim(s.replace(new RegExp(/(https?|ftp|file):\/\//), ''));
        },
        type: "text"
    });
    
    $.tablesorter.addParser
    ({
        id: "isoDate",
        is: function(s)
        {
            return /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(s);
        },
        format: function(s)
        {
            return $.tablesorter.formatFloat((s != "") ? new Date(s.replace(new RegExp(/-/g), "/")).getTime() : "0");
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "percent",
        is: function(s)
        {
            return /\%$/.test($.trim(s));
        },
        format: function(s)
        {
            return $.tablesorter.formatFloat(s.replace(new RegExp(/%/g), ""));
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "usLongDate",
        is: function(s)
        {
            return s.match(new RegExp(/^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/));
        },
        format: function(s)
        {
            return $.tablesorter.formatFloat(new Date(s).getTime());
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "shortDate",
        is: function(s)
        {
            return /\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}/.test(s);
        },
        format: function(s, table)
        {
            s = s.replace(/\-/g, "/");
            if (table.config.dateFormat == "us")
            {
                s = s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/, "$3/$1/$2");
            }
            else if (table.config.dateFormat == "uk")
            {
                s = s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/, "$3/$2/$1");
            }
            else if (table.config.dateFormat == "dd/mm/yy" || table.config.dateFormat == "dd-mm-yy")
            {
                s = s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2})/, "$1/$2/$3");
            }
            return $.tablesorter.formatFloat(new Date(s).getTime());
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "time",
        is: function(s)
        {
            return /^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(am|pm)))$/.test(s);
        },
        format: function(s)
        {
            return $.tablesorter.formatFloat(new Date("2000/01/01 " + s).getTime());
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "metadata",
        is: function(s)
        {
            return false;
        },
        format: function(s, table, cell)
        {
            var p = table.config.parserMetadataName ? table.config.parserMetadataName : 'sortValue';
            return $(cell).metadata()[p];
        },
        type: "numeric"
    });
    
    $.tablesorter.addParser
    ({
        id: "attribute",
        is: function(s)
        {
            return false;
        },
        format: function(s, table, cell)
        {
            return $(cell).attr('data-sortValue');
        },
        type: "numeric"
    });
    
    // Add default widgets
    $.tablesorter.addWidget
    ({
        id: "zebra",
        format: function (table)
        {
            if (table.config.debug)
            {
                var time = new Date();
            }
            
            var row = -1;
            $("tr:visible", table.tBodies[0]).each(function()
            {
                // style children rows the same way the parent row was styled
                if (!$(this).hasClass(table.config.cssChildRow))
                {
                    row++;
                }
                
                var odd = (row % 2 == 0);
                var classToRemove = table.config.widgetZebra.css[odd ? 0 : 1];
                var classToAdd = table.config.widgetZebra.css[odd ? 1 : 0];
                
                $(this).removeClass(classToRemove).addClass(classToAdd);
            });
            
            if (table.config.debug)
            {
                $.tablesorter.benchmark("Applying Zebra widget: ", time);
            }
        }
    });
})(jQuery);
