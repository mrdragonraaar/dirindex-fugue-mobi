<?php
/**
 * DirIndexFugueMOBI.php
 *
 * (c)2012 mrdragonraaar.com
 */
// icon to use for favicon.
if (!defined('FAVICON_ICON')) {
define('FAVICON_ICON', 'e-book-reader-black.png');
}
// css filename
if (!defined('DIRINDEX_CSS_MOBI')) {
define('DIRINDEX_CSS_MOBI', 'dirindex-fugue-mobi.css');
}
// jquery filename
if (!defined('JQUERY_JS')) {
define('JQUERY_JS', 'jquery-1.8.2.min.js');
}
// javascript filename
if (!defined('DIRINDEX_JS_MOBI')) {
define('DIRINDEX_JS_MOBI', 'dirindex-fugue-mobi.js');
}
// icon to use for path navbar.
if (!defined('PATH_NAVBAR_ICON')) {
define('PATH_NAVBAR_ICON', 'e-book-reader.png');
}
// blank cover image
if (!defined('BLANK_COVER')) {
define('BLANK_COVER', 'blank_cover.jpg');
}
// icon to use for mobi file.
if (!defined('MOBI_ICON')) {
define('MOBI_ICON', 'document-mobi-text.png');
}
// amazon icon
if (!defined('AMAZON_ICON')) {
define('AMAZON_ICON', 'amazon_16.png');
}
// amazon search url
if (!defined('AMAZON_SEARCH_URL')) {
define('AMAZON_SEARCH_URL', 'http://www.amazon.co.uk/s/ref=sr_adv_b?field-isbn=');
}
// goodreads icon
if (!defined('GOODREADS_ICON')) {
define('GOODREADS_ICON', 'goodreads_16.png');
}
// goodreads search url
if (!defined('GOODREADS_SEARCH_URL')) {
define('GOODREADS_SEARCH_URL', 'http://www.goodreads.com/search?query=');
}
// use sort title
if (!defined('USE_TITLE_SORT')) {
define('USE_TITLE_SORT', false);
}
// use sort author
if (!defined('USE_AUTHOR_SORT')) {
define('USE_AUTHOR_SORT', false);
}
// title sort icon.
if (!defined('SORT_TITLE_ICON')) {
define('SORT_TITLE_ICON', 'sort-alphabet.png');
}
// author sort icon.
if (!defined('SORT_AUTHOR_ICON')) {
define('SORT_AUTHOR_ICON', 'sort--pencil.png');
}
// publishing date sort icon.
if (!defined('SORT_PUBLISHING_DATE_ICON')) {
define('SORT_PUBLISHING_DATE_ICON', 'sort-date.png');
}
// details view icon.
if (!defined('VIEW_DETAILS_ICON')) {
define('VIEW_DETAILS_ICON', 'document-tree.png');
}
// thumbnails view icon.
if (!defined('VIEW_THUMBNAILS_ICON')) {
define('VIEW_THUMBNAILS_ICON', 'document-view-thumbnail.png');
}

include('DirIndexFugue.php');
include('lib/MOBIPocket/MOBIPocket.php');

/**
 * Helper methods for MOBI fugue-icon themed mod_autoindex.
 *
 * @author Adrian D. Elgar
 */
class DirIndexFugueMOBI extends DirIndexFugue
{
	// array of mobi files.
	private $_mobi_files = array();

	/**
         * Create new DirIndexFugueIconsMOBI instance.
         */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get dirindex mobi css url.
	 * @return dirindex mobi css url.
	 */
	static public function mobi_css_url()
	{
		return self::url('css/' . DIRINDEX_CSS_MOBI);
	}

	/**
	 * Get jquery js url.
	 * @return jquery js url.
	 */
	static public function jquery_js_url()
	{
		return self::url('js/' . JQUERY_JS);
	}

	/**
	 * Get dirindex mobi js url.
	 * @return dirindex mobi js url.
	 */
	static public function mobi_js_url()
	{
		return self::url('js/' . DIRINDEX_JS_MOBI);
	}

	/**
         * Get url for title sort icon.
         * @return title sort icon url.
         */
	static public function sort_title_icon_url()
	{
		return self::icon_url(SORT_TITLE_ICON);
	}

	/**
         * Get url for author sort icon.
         * @return author sort icon url.
         */
	static public function sort_author_icon_url()
	{
		return self::icon_url(SORT_AUTHOR_ICON);
	}

	/**
         * Get url for publishing date sort icon.
         * @return publishing date sort icon url.
         */
	static public function sort_publishing_date_icon_url()
	{
		return self::icon_url(SORT_PUBLISHING_DATE_ICON);
	}

	/**
         * Get url for details view icon.
         * @return details view icon url.
         */
	static public function view_details_icon_url()
	{
		return self::icon_url(VIEW_DETAILS_ICON);
	}

	/**
         * Get url for thumbnails view icon.
         * @return thumbnails view icon url.
         */
	static public function view_thumbnails_icon_url()
	{
		return self::icon_url(VIEW_THUMBNAILS_ICON);
	}

	/**
         * Get url for mobi file icon.
         * @return mobi file icon url.
         */
	static public function mobi_icon_url()
	{
		return self::icon_url(MOBI_ICON);
	}

	/**
	 * Get amazon icon url.
	 * @return amazon icon url.
	 */
	static public function amazon_icon_url()
	{
		return self::url('images/' . AMAZON_ICON);
	}

	/**
	 * Get amazon search url.
	 * @param $isbn ISBN of mobi file.
	 * @return amazon search url.
	 */
	static public function amazon_search_url($isbn)
	{
		return AMAZON_SEARCH_URL . $isbn;
	}

	/**
	 * Get goodreads icon url.
	 * @return goodreads icon url.
	 */
	static public function goodreads_icon_url()
	{
		return self::url('images/' . GOODREADS_ICON);
	}

	/**
	 * Get goodreads search url.
	 * @param $isbn ISBN of mobi file.
	 * @return goodreads search url.
	 */
	static public function goodreads_search_url($isbn)
	{
		return GOODREADS_SEARCH_URL . $isbn;
	}

	/**
	 * Get cover url for mobi file.
	 * @param $mobi mobi file.
	 * @return mobi file cover url.
	 */
	static public function cover_url($mobi)
	{
		if ($url = self::mobi_cover_url($mobi))
		{
			return $url;
		}

		if ($url = self::mobi_thumb_url($mobi))
		{
			return $url;
		}

		return self::blank_cover_url();
	}

	/**
	 * Get blank cover url.
	 * @return blank cover url.
	 */
	static public function blank_cover_url()
	{
		return self::url('images/' . BLANK_COVER);
	}

	/**
	 * Get mobi file cover data url.
	 * @param $mobi mobi file.
	 * @param $thumbnail true if cover is thumbnail.
	 * @return mobi file cover data url.
	 */
	static public function mobi_cover_url($mobi, $thumbnail = false)
	{
		$data = $thumbnail ? base64_encode($mobi->get_thumbnail()) : 
		   base64_encode($mobi->get_cover());
		if (!$data) { return ''; }

		$url = 'data:image/jpg;base64,' . $data;

		return getimagesize($url) ? $url : '';
	}

	/**
	 * Get mobi file thumbnail data url.
	 * @param $mobi mobi file.
	 * @return mobi file thumbnail data url.
	 */
	static public function mobi_thumb_url($mobi)
	{
		return self::mobi_cover_url($mobi, true);
	}

	/**
	 * Get mobi file list.
	 * @return mobi file list.
	 */
	public function mobi_files()
	{
		return $this->_mobi_files ? 
		   $this->_mobi_files : $this->load_mobi_files();
	}

	/**
	 * Gets list of mobi files in current directory.
	 * Matches and sorts by querystring parameters.
	 */
	private function load_mobi_files()
	{
		if ($handler = opendir(self::current_path()))
		{
			while ($filename = readdir($handler))
			{
				if (self::is_mobi($filename) && 
				   $this->is_search_match($filename))
				{
					$this->_mobi_files[] = new MOBIPocket(self::current_file_path($filename));
				}
			}

			$this->_sort_mobi_files();
		}

		return $this->_mobi_files;
	}

	/**
	 * Test for mobi extension.
	 * @param $filename name of file.
	 * @return true if mobi extension.
	 */
	static function is_mobi($filename)
	{
		return pathinfo($filename, PATHINFO_EXTENSION) == 'mobi';
	}

	/**
	 * Sort list of mobi files by querystring paramaters.
	 */
	private function _sort_mobi_files()
	{
		if ($this->is_sort_by_filename() > 0)
		{
			return ($this->is_sort_asc() > 0) ?
			   usort($this->_mobi_files, 'self::_name_sort_asc') :
			   usort($this->_mobi_files, 'self::_name_sort_des');
		}

		if ($this->is_sort_by_filemtime() > 0)
		{
			return ($this->is_sort_asc() > 0) ?
			   usort($this->_mobi_files, 'self::_mtime_sort_asc') :
			   usort($this->_mobi_files, 'self::_mtime_sort_des');
		}

		if ($this->is_sort_by_filesize() > 0)
		{
			return ($this->is_sort_asc() > 0) ?
			   usort($this->_mobi_files, 'self::_size_sort_asc') :
			   usort($this->_mobi_files, 'self::_size_sort_des');
		}

		if ($this->is_sort_by_title() > 0)
		{
			return ($this->is_sort_asc() > 0) ?
			   usort($this->_mobi_files, 'self::_title_sort_asc') :
			   usort($this->_mobi_files, 'self::_title_sort_des');
		}

		if ($this->is_sort_by_author() > 0)
		{
			return ($this->is_sort_asc() > 0) ?
			   usort($this->_mobi_files, 'self::_author_sort_asc') :
			   usort($this->_mobi_files, 'self::_author_sort_des');
		}

		if ($this->is_sort_by_publishing_date() > 0)
		{
			return ($this->is_sort_asc() > 0) ?
			   usort($this->_mobi_files, 'self::_publishing_date_sort_asc') :
			   usort($this->_mobi_files, 'self::_publishing_date_sort_des');
		}

		return usort($this->_mobi_files, 'self::_name_sort_asc');
	}

	/**
	 * Test if querystring view is thumbnails.
	 * @return 1 if thumbnails view. -1 if not set.
	 */
	public function is_view_thumbnails()
	{
		return isset($this->_query['D']) ? !strcmp($this->_query['D'], 'T') : -1;
	}

	/**
	 * Get querystring view url for given value.
	 * @param $view display value.
	 * @return view url.
	 */
	public function view_url($view)
	{
		$url = '?';
		$url .= $this->sort_column() ? 
		   'C=' . $this->sort_column() . ';' : '';
		$url .= $this->sort_order() ? 
		   'O=' . $this->sort_order() . ';' : '';
		$url .= $this->search_pattern() ? 
		   'P=' . $this->search_pattern() . ';' : '';

		return $url . 'D=' . $view;
	}

	/**
	 * Get querystring view url for details.
	 * @return details view url.
	 */
	public function view_details_url()
	{
		return $this->view_url('D');
	}

	/**
	 * Get querystring view url for thumbnails.
	 * @return thumbnails view url.
	 */
	public function view_thumbnails_url()
	{
		return $this->view_url('T');
	}

	/**
	 * Test if querystring sort column is title.
	 * @return 1 if filesize. -1 if not set.
	 */
	public function is_sort_by_title()
	{
		return $this->is_sort_by('T');
	}

	/**
	 * Test if querystring sort column is author.
	 * @return 1 if filesize. -1 if not set.
	 */
	public function is_sort_by_author()
	{
		return $this->is_sort_by('A');
	}

	/**
	 * Test if querystring sort column is publishing date.
	 * @return 1 if filesize. -1 if not set.
	 */
	public function is_sort_by_publishing_date()
	{
		return $this->is_sort_by('P');
	}

	/**
	 * Get querystring sort url for given value.
	 * @param $sort_column sort column value.
	 * @return sort url.
	 */
	public function sort_url($sort_column)
	{
		$url = '?C=' . $sort_column . ';O=A';

		if (($this->is_sort_by($sort_column) > 0) &&
		   ($this->is_sort_asc() > 0))
		{
			$url = '?C=' . $sort_column . ';O=D';
		}

		$url = $url . ($this->search_pattern() ? 
		   ';P=' . $this->search_pattern() : '');

		return $url . ($this->is_view_thumbnails() > 0 ? 
		   ';D=T' : '');
	}

	/**
	 * Get querystring sort url for title.
	 * @return title sort url.
	 */
	public function sort_title_url()
	{
		return $this->sort_url('T');
	}

	/**
	 * Get querystring sort url for author.
	 * @return author sort url.
	 */
	public function sort_author_url()
	{
		return $this->sort_url('A');
	}

	/**
	 * Get querystring sort url for publishing date.
	 * @return publishing date sort url.
	 */
	public function sort_publishing_date_url()
	{
		return $this->sort_url('P');
	}

	// Sort by filename (ascending).
	static private function _name_sort_asc($a, $b)
	{ return strcmp($a->filename, $b->filename); }

	// Sort by filename (descending).
	static private function _name_sort_des($a, $b)
	{ return strcmp($b->filename, $a->filename); }

	// Sort by file size (ascending).
	static private function _size_sort_asc($a, $b)
	{ return $a->filesize - $b->filesize; }

	// Sort by file size (descending).
	static private function _size_sort_des($a, $b)
	{ return $b->filesize - $a->filesize; }

	// Sort by file modified time (ascending).
	static private function _mtime_sort_asc($a, $b)
	{ return $a->filemtime - $b->filemtime; }

	// Sort by file modified time (descending).
	static private function _mtime_sort_des($a, $b)
	{ return $b->filemtime - $a->filemtime; }

	// Sort by title (ascending).
	static private function _title_sort_asc($a, $b)
	{ return strcmp($a->get_title_sort(), $b->get_title_sort()); }

	// Sort by title (descending).
	static private function _title_sort_des($a, $b)
	{ return strcmp($b->get_title_sort(), $a->get_title_sort()); }

	// Sort by author (ascending).
	static private function _author_sort_asc($a, $b)
	{ return strcmp($a->get_authors_sort_str(), $b->get_authors_sort_str()); }

	// Sort by author (descending).
	static private function _author_sort_des($a, $b)
	{ return strcmp($b->get_authors_sort_str(), $a->get_authors_sort_str()); }

	// Sort by publishing date (ascending).
	static private function _publishing_date_sort_asc($a, $b)
	{ return $a->get_publishingdate_utc() - $b->get_publishingdate_utc(); }

	// Sort by publishing date (descending).
	static private function _publishing_date_sort_des($a, $b)
	{ return $b->get_publishingdate_utc() - $a->get_publishingdate_utc(); }

	/**
	 * Get publishing string for mobi file.
	 * @param $mobi mobi file.
	 * @return publishing string.
	 */
	static public function publish_str($mobi)
	{
		$publish_str = '';

		$publish_date = $mobi->get_publishingdate_str();
		$publish_str .= $publish_date ? $publish_date : '';

		$publisher = $mobi->get_publisher();
		$publish_str .= $publisher ? ' by ' . $publisher : '';

		return $publish_str ? 'Published ' . $publish_str : '';
	}

	/**
	 * Test if browser is Kindle.
	 * @return true if kindle browser.
	 */
	static public function is_kindle()
	{
		return preg_match('/Kindle/', $_SERVER['HTTP_USER_AGENT']) ? 
		   true : false;
	}

}

$dirindex_fugue_mobi = new DirIndexFugueMOBI();

?>

