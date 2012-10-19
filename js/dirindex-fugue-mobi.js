/** 
 * dirindex-fugue-mobi.js
 * 
 * (c)2012 mrdragonraaar.com
 */

/*
 * Collapse book description.
 * @param bookDescElem book description element.
 */
function collapseBookDesc(bookDescElem)
{
	var bookDescTextElem = bookDescElem.children('.mobibook_desc_text');
	return bookDescTextElem.addClass('collapse_desc');
}

/*
 * Expand book description.
 * @param bookDescElem book description element.
 */
function expandBookDesc(bookDescElem)
{
	var bookDescTextElem = bookDescElem.children('.mobibook_desc_text');
	return bookDescTextElem.removeClass('collapse_desc');
}

/*
 * Is book description collapsed?.
 * @param bookDescElem book description element.
 * @return true if book description collapsed.
 */
function isBookDescCollapsed(bookDescElem)
{
	var bookDescTextElem = bookDescElem.children('.mobibook_desc_text');
	return bookDescTextElem.hasClass('collapse_desc');
}

/*
 * Toggle book description.
 * @param toggleLinkElem book description toggle link element.
 */
function toggleBookDesc(toggleLinkElem)
{
	var bookDescElem = toggleLinkElem.parent();

	if (isBookDescCollapsed(bookDescElem))
	{
		expandBookDesc(bookDescElem);
		toggleLinkElem.html('(less)');
		return;
	}

	collapseBookDesc(bookDescElem);
	toggleLinkElem.html('(&#8230more)');
}

/*
 * Add toggle links to book descriptions.
 */
function addBookDescToggleLinks()
{
	$('.mobibook_desc').each(function() {
		addBookDescToggleLink($(this));
	});
}

/*
 * Add toggle link to book description.
 * @param bookDescElem book description element.
 */
function addBookDescToggleLink(bookDescElem)
{
	var toggleLink = '<a class="mobibook_toggledesc" href="#">(' +
	   (isBookDescCollapsed(bookDescElem) ? '&#8230more' : 'less') +
	   ')</a>';

	bookDescElem.append(toggleLink);
	bookDescElem.children('.mobibook_toggledesc').click(function(event) {
		toggleBookDesc($(this));
		event.preventDefault();
	});
}

/*
 * Get querystring value for given key.
 * @param key querystring key.
 * @return querystring value.
 */
function queryString(key)
{
	var url = window.location.href;
	var keyValues = url.split(/[\?&;]+/);
	var i, keyValue;

	for (i = 0; i < keyValues.length; i++)
	{
		keyValue = keyValues[i].split("=");

		if (keyValue[0] == key)
		{
			return keyValue[1];
		}
	}

	return '';
}

/*
 * Add title and view mode to column link.
 * @param col column number.
 * @param title column link title.
 */
function updateColumnLink(col, title)
{
	var column = $('#dirindex > table > tbody > tr > th:nth-child(' + col + ') > a');
	column.attr('title', title);

	var viewMode = queryString('D');
	if (viewMode)
	{
		column.attr('href', column.attr('href') + ';D=' + viewMode);
	}
}

/*
 * Add title and view mode to filename column link.
 */
function updateFileNameColumnLink()
{
	updateColumnLink(2, 'Sort by File Name');
}

/*
 * Add title and view mode to file modified time column link.
 */
function updateFileModifiedTimeColumnLink()
{
	updateColumnLink(3, 'Sort by File Modified Time');
}

/*
 * Add title and view mode to file size column link.
 */
function updateFileSizeColumnLink()
{
	updateColumnLink(4, 'Sort by File Size');
}

// initialise book description toggle links.
$(document).ready(function() {
	addBookDescToggleLinks();
	updateFileNameColumnLink();
	updateFileModifiedTimeColumnLink();
	updateFileSizeColumnLink();
});
