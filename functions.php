<?php
/**
 * functions.php
 *
 * (c)2012 mrdragonraaar.com
 */
define('FUGUE_ICONS_BASE_URL', '/global/icons/fugue-icons/');
define('USE_TITLE_SORT', false);
define('USE_AUTHOR_SORT', false);

include('lib/DirIndexFugueMOBI/DirIndexFugueMOBI.php');
include('templates/path_navbar.php');
include('templates/searchbox.php');
include('templates/mobiindex_menubar.php');
include('templates/mobiindex_details.php');
include('templates/mobiindex_thumbnails.php');

/**
 * Display the url of current path.
 */
function dirindex_current_url()
{
	global $dirindex_fugue_mobi;

	echo $dirindex_fugue_mobi->current_url();
}

/**
 * Display the url of dirindex css file.
 */
function dirindex_css_url()
{
	global $dirindex_fugue_mobi;

	echo $dirindex_fugue_mobi->css_url();
}

/**
 * Display the url of dirindex mobi css file.
 */
function dirindex_mobi_css_url()
{
	global $dirindex_fugue_mobi;

	echo $dirindex_fugue_mobi->mobi_css_url();
}

/**
 * Display the url of jquery js file.
 */
function jquery_js_url()
{
	global $dirindex_fugue_mobi;

	echo $dirindex_fugue_mobi->jquery_js_url();
}

/**
 * Display the url of dirindex mobi js file.
 */
function dirindex_mobi_js_url()
{
	global $dirindex_fugue_mobi;

	echo $dirindex_fugue_mobi->mobi_js_url();
}

/**
 * Display the url of favicon.
 */
function dirindex_favicon_url()
{
	global $dirindex_fugue_mobi;

	echo $dirindex_fugue_mobi->favicon_url();
}

/**
 * Display the MOBIIndex listing.
 */
function mobiindex_listing()
{
	global $dirindex_fugue_mobi;

	if ($dirindex_fugue_mobi->is_view_thumbnails() > 0)
	{
		return mobiindex_thumbnails();
	}

	return mobiindex_details();
}

/**
 * Is kindle browser?.
 * @return true if kindle browser.
 */
function dirindex_is_kindle()
{
	global $dirindex_fugue_mobi;

	return $dirindex_fugue_mobi->is_kindle();
}

/**
 * Display the class for kindle browser.
 */
function dirindex_kindle_class()
{
	if (dirindex_is_kindle())
	{
		echo 'class="kindle"';
	}
}

?>
