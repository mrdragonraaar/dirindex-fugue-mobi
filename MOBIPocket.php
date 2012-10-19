<?php
/**
 * MOBIPocket.php
 *
 * (c)2011 mrdragonraaar.com
 */
include('PalmPDB.php');

// EXTH Record Types
define('EXTH_RECTYPE_AUTHOR', 100); // Author
define('EXTH_RECTYPE_PUBLISHER', 101); // Publisher
define('EXTH_RECTYPE_IMPRINT', 102); // Imprint
define('EXTH_RECTYPE_DESC', 103); // Description
define('EXTH_RECTYPE_ISBN', 104); // ISBN
define('EXTH_RECTYPE_SUBJECT', 105); // Subject
define('EXTH_RECTYPE_PUBLISHINGDATE', 106); // Publishing Date
define('EXTH_RECTYPE_REVIEW', 107); // Review
define('EXTH_RECTYPE_CONTRIBUTOR', 108); // Contributor
define('EXTH_RECTYPE_RIGHTS', 109); // Rights
define('EXTH_RECTYPE_SUBJECTCODE', 110); // Subject Code
define('EXTH_RECTYPE_TYPE', 111); // Type
define('EXTH_RECTYPE_SOURCE', 112); // Source
define('EXTH_RECTYPE_ASIN', 113); // ASIN
define('EXTH_RECTYPE_VERSION', 114); // Version Number
define('EXTH_RECTYPE_SAMPLE', 115); // Sample
define('EXTH_RECTYPE_STARTREADING', 116); // Start Reading
define('EXTH_RECTYPE_ADULT', 117); // Adult
define('EXTH_RECTYPE_RETAILPRICE', 118); // Retail Price
define('EXTH_RECTYPE_RETAILPRICECUR', 119); // Retail Price Currency

define('EXTH_RECTYPE_DICTSHORTNAME', 200); // Dictionary Short Name
define('EXTH_RECTYPE_COVEROFFSET', 201); // Cover Offset
define('EXTH_RECTYPE_THUMBOFFSET', 202); // Thumbnail Offset

define('EXTH_RECTYPE_CREATORSOFTWARE', 204); // Creator Software
define('EXTH_RECTYPE_CREATORMAJOR', 205); // Creator Major Version
define('EXTH_RECTYPE_CREATORMINOR', 206); // Creator Minor Version
define('EXTH_RECTYPE_CREATORBUILD', 207); // Creator Build Version

define('EXTH_RECTYPE_TTSFLAG', 404); // Text to Speech enabled flag

define('EXTH_RECTYPE_LASTUPDATETIME', 502); // Last updated time
define('EXTH_RECTYPE_UPDATEDTITLE', 503); // Updated Title
define('EXTH_RECTYPE_LANGUAGE', 524); // Language

// Creator Software IDs
define('CREATORSOFTWARE_ID_MOBIGEN', 1); // mobigen
define('CREATORSOFTWARE_ID_MOBIPOCKETCREATOR', 2); // MobiPocket Creator
define('CREATORSOFTWARE_ID_KINDLEGEN_WINDOWS', 200); // kindlegen (Windows)
define('CREATORSOFTWARE_ID_KINDLEGEN_LINUX', 201); // kindlegen (Linux)
define('CREATORSOFTWARE_ID_KINDLEGEN_MAC', 202); // kindlegen (Mac)


/**
 * Parse MOBI Pocket files.
 *
 * @author Adrian D. Elgar
 */
class MOBIPocket extends PalmPDB
{
	public $palmdoc_header;
	public $mobi_header;
	public $exth_header;
	public $author_prefixes = array('Mr', 'Mrs', 'Ms', 'Dr', 'Prof');
	public $author_suffixes = array('Jr', 'Sr', 'Inc', 'Ph.D', 'Phd',
	   'MD', 'M.D', 'I', 'II', 'III', 'IV', 'Junior', 'Senior');

	/**
         * Load MOBIPocket file.
         * Overides PalmPDB->load().
         * @param string $mobifile MOBIPocket file to open.
	 * @return undef on failure.
         */
	public function load($mobifile)
	{
		parent::load($mobifile);

		if ($this->pdb_header['type'] == 'BOOK' && $this->pdb_header['creator'] == 'MOBI')
		{
			$this->parse_record_0();
		}
	}

	/**
         * Parse record 0.
	 * @return undef on failure.
         */
	private function parse_record_0()
	{
		$record_0 = $this->records[0];

		$palmdoc_header = substr($record_0, 0, 16);
		$this->parse_palmdoc_header($palmdoc_header);

		$mobi_header = substr($record_0, 16);
		$this->parse_mobi_header($mobi_header);

		if ($this->mobi_header['exth_flags'] & 0x40)
		{
			$exth_header = substr($record_0, $this->mobi_header['header_length'] + 16);
			$this->parse_exth_header($exth_header);
		}
	}

	/**
         * Parse PalmDoc header.
         * @param string $palmdoc_header raw PalmDoc header.
	 * @return undef on failure.
         */
	private function parse_palmdoc_header($palmdoc_header)
	{
		$this->palmdoc_header = unpack("ncompression/x2/Ntext_length/nrecord_count/nrecord_size/nencryption_type/x2", $palmdoc_header);
	}

	/**
         * Parse MOBI header.
         * @param string $mobi_header raw MOBI header.
	 * @return undef on failure.
         */
	private function parse_mobi_header($mobi_header)
	{
		$this->mobi_header = unpack("a4identifier/Nheader_length/Nmobi_type/Ntext_encoding/Nunique_id/Nfile_version/Norto_index/Ninflection_index/Nindex_names/Nindex_keys/N6extra_index/Nfirst_nonbook_index/Nfullname_offset/Nfullname_len/Nlocale/Ninput_language/Noutput_language/Nmin_version/Nfirst_image_index/Nhuff_rec_offset/Nhuff_rec_count/Nhuff_table_offset/Nhuff_table_length/Nexth_flags", $mobi_header);
	}

	/**
         * Get the fullname from record 0.
	 * @return string.
         */
	public function get_fullname()
	{
		if (!$this->mobi_header) { return; } 

		$record_0 = $this->records[0];
		$fn_off = $this->mobi_header['fullname_offset']; 
		$fn_len = $this->mobi_header['fullname_len'];

		return substr($record_0, $fn_off, $fn_len);
	}

	/**
         * Parse EXTH header.
         * @param string $exth_header raw EXTH header.
	 * @return undef on failure.
         */
	private function parse_exth_header($exth_header)
	{
		$this->exth_header = unpack("a4identifier/Nheader_length/Nrecord_count", $exth_header);
		$pos = 12;
		for ($i = 0; $i < $this->exth_header['record_count']; $i++)
		{
			$exth_record = unpack("Nrecord_type/Nrecord_length", substr($exth_header, $pos));
			$record_data_len = $exth_record['record_length'] - 8;
			$exth_record = unpack("Nrecord_type/Nrecord_length/a" . $record_data_len . "record_data", substr($exth_header, $pos));

			$this->exth_header['exth_records'][] = $exth_record;

			$pos += $exth_record['record_length'];
		}
	}

	/**
         * Get all the instances of EXTH record data
	 * for given record type.
         * @param string $exth_record_type EXTH record type.
	 * @return array.
         */
	public function get_exth_record_data_all($exth_record_type)
	{
		if (!$this->exth_header) { return; } 
		
		$record_data = array();
		foreach ($this->exth_header['exth_records'] as $exth_record)
		{
			if ($exth_record['record_type'] == $exth_record_type)
			{
				$record_data[] = $exth_record['record_data'];
			}
		}

		return $record_data;
	}

	/**
         * Get the first instance of EXTH record data
	 * for given record type.
         * @param string $exth_record_type EXTH record type.
	 * @return string.
         */
	public function get_exth_record_data($exth_record_type)
	{
		if (!$this->exth_header) { return; } 
		
		foreach ($this->exth_header['exth_records'] as $exth_record)
		{
			if ($exth_record['record_type'] == $exth_record_type)
			{
				return $exth_record['record_data'];
			}
		}

		return;
	}

	/**
         * Get the first instance of EXTH record data
	 * for given record type as an integet.
         * @param string $exth_record_type EXTH record type.
	 * @return int.
         */
	public function get_exth_record_data_int($exth_record_type)
	{
		$record_data = $this->get_exth_record_data($exth_record_type);
		if (!$record_data) { return; }

		$record_data = unpack('Nvalue', $record_data);
		return $record_data['value'];
	}

	/**
         * Get the author(s) from EXTH records.
	 * @return array.
         */
	public function get_authors()
	{
		return $this->get_exth_record_data_all(EXTH_RECTYPE_AUTHOR);
	}

	/**
         * Get the first author from EXTH records.
	 * @return string.
         */
	public function get_first_author()
	{
		$authors = $this->get_authors();

		return sizeof($authors) > 0 ? $authors[0] : '';
	}

	/**
         * Get the sort author(s) from EXTH records.
	 * @param $author_seperator seperator to use between author 
	 * first and last names.
	 * @return array.
         */
	public function get_authors_sort($author_seperator = ', ')
	{
		$authors_sort = array();
		foreach ($this->get_authors() as $author)
		{
			$authors_sort[] = $this->get_author_sort($author, 
			   $author_seperator);
		}

		return $authors_sort;
	}

	/**
         * Get the sort author from author.
	 * @param $author author.
	 * @param $author_seperator seperator to use between author 
	 * first and last names.
	 * @return string.
         */
	public function get_author_sort($author, $author_seperator = ', ')
	{
		$author = preg_replace('/\./', '', $author);

		$pattern = '/^(.+)\b(\S+)$/';
		$suffix_pattern = '/^(.+)\b(\S+\s+(' . 
		   implode('|', $this->author_suffixes) . '))$/';

		if (!preg_match('/,/', $author) &&
		   (preg_match($suffix_pattern, $author, $m) ||
		   preg_match($pattern, $author, $m)))
		{
			$fn = $m[1];
			$ln = $m[2];

			$prefix_pattern = '/^(' .
			   implode('|', $this->author_prefixes) . ')\b/';
			$fn = preg_replace($prefix_pattern, '', $fn);

			return $ln . $author_seperator . $fn;
		}

		return $author;
	}

	/**
	 * Get string of author(s).
	 * @param $seperator seperator to use between authors.
	 * @param $last_seperator seperator to use before last author.
	 * @param $author_sort true if using sort author.
	 * @param $author_seperator seperator to use between author 
	 * first and last names.
	 * @return string.
	 */
	public function get_authors_str($seperator = ', ', 
	   $last_seperator = ' & ', $author_sort = false, 
	   $author_seperator = ', ')
	{
		$authors = $author_sort ? 
		   $this->get_authors_sort($author_seperator) : 
		   $this->get_authors();

		if ($authors)
		{
			if (sizeof($authors) == 1)
			{
				return $authors[0];
			}

			$last_author = array_pop($authors);
			return implode($seperator, $authors) . 
			   $last_seperator . $last_author;
		}

		return '';
	}

	/**
	 * Get string of sort author(s).
	 * @param $seperator seperator to use between authors.
	 * @param $last_seperator seperator to use before last author.
	 * @param $author_seperator seperator to use between author 
	 * first and last names.
	 * @return string.
	 */
	public function get_authors_sort_str($seperator = ', ', 
	   $last_seperator = ' & ', $author_seperator = ', ')
	{
		return $this->get_authors_str($seperator, $last_seperator, 
		   true, $author_seperator);
	}

	/**
         * Get the publisher from EXTH records.
	 * @return string.
         */
	public function get_publisher()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_PUBLISHER);
	}

	/**
         * Get the description from EXTH records.
	 * @return string.
         */
	public function get_desc()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_DESC);
	}

	/**
         * Get the ISBN from EXTH records.
	 * @return string.
         */
	public function get_isbn()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_ISBN);
	}

	/**
         * Get the subject(s) from EXTH records.
	 * @return array.
         */
	public function get_subjects()
	{
		return $this->get_exth_record_data_all(EXTH_RECTYPE_SUBJECT);
	}

	/**
         * Get string of subject(s).
	 * @param $seperator seperator to use between subjects.
	 * @return string.
         */
	public function get_subjects_str($seperator = ', ')
	{
		return implode($seperator, $this->get_subjects());
	}

	/**
         * Get the publishing date from EXTH records.
	 * @return string.
         */
	public function get_publishingdate()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_PUBLISHINGDATE);
	}

	/**
         * Get the publishing date as UTC.
	 * @return int.
         */
	public function get_publishingdate_utc()
	{
		$publish_date = $this->get_publishingdate();

		return $publish_date ? strtotime($publish_date) : false;
	}

	/**
         * Get the publishing date as formatted string.
	 * @param $format date format.
	 * @return string.
         */
	public function get_publishingdate_str($format = '')
	{
		$format = $format ? $format : 'F jS Y';
		$publish_date_utc = $this->get_publishingdate_utc();

		return $publish_date_utc ? date($format, $publish_date_utc) : 
		   false;
	}

	/**
         * Get the review from EXTH records.
	 * @return string.
         */
	public function get_review()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_REVIEW);
	}

	/**
         * Get the contributor from EXTH records.
	 * @return string.
         */
	public function get_contributor()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_CONTRIBUTOR);
	}

	/**
         * Get the rights from EXTH records.
	 * @return string.
         */
	public function get_rights()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_RIGHTS);
	}

	/**
         * Get the ASIN from EXTH records.
	 * @return string.
         */
	public function get_asin()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_ASIN);
	}

	/**
         * Get the cover offset from EXTH records.
	 * @return int.
         */
	public function get_coveroffset()
	{
		return $this->get_exth_record_data_int(EXTH_RECTYPE_COVEROFFSET);
	}

	/**
         * Get the cover from PDB records.
	 * @return image data.
         */
	public function get_cover()
	{
		$cover_offset = $this->get_coveroffset();
		$first_image_index = $this->mobi_header['first_image_index'];
		if (!$cover_offset || !$first_image_index) { return; }

		$pdb_record_index = $cover_offset + $first_image_index;

		return $this->records[$pdb_record_index];
	}

	/**
         * Save the cover from PDB records 
	 * to a file.
	 * @param string $cover_filename The cover filename to save image data to.
         */
	public function save_cover($cover_filename)
	{
		$cover = $this->get_cover();
		if (!$cover) { return; }

		return file_put_contents($cover_filename, $cover);
	}

	/**
         * Get the thumbnail offset from EXTH records.
	 * @return int.
         */
	public function get_thumboffset()
	{
		return $this->get_exth_record_data_int(EXTH_RECTYPE_THUMBOFFSET);
	}

	/**
         * Get the thumbnail from PDB records.
	 * @return image data.
         */
	public function get_thumbnail()
	{
		$thumb_offset = $this->get_thumboffset();
		$first_image_index = $this->mobi_header['first_image_index'];
		if (!$thumb_offset || !$first_image_index) { return; }

		$pdb_record_index = $thumb_offset + $first_image_index;

		return $this->records[$pdb_record_index];
	}

	/**
         * Save the thumbnail from PDB records 
	 * to a file.
	 * @param string $thumb_filename The thumbnail filename to save image data to.
         */
	public function save_thumbnail($thumb_filename)
	{
		$thumb = $this->get_thumbnail();
		if (!$thumb) { return; }

		return file_put_contents($thumb_filename, $thumb);
	}

	/**
         * Get the creator software from EXTH records.
	 * @return int.
         */
	public function get_creatorsoftware()
	{
		return $this->get_exth_record_data_int(EXTH_RECTYPE_CREATORSOFTWARE);
	}

	/**
         * Get the creator software as a string from EXTH records.
	 * @return string.
         */
	public function get_creatorsoftware_str()
	{
		$creatorid = $this->get_creatorsoftware();
		if (!$creatorid) { return; }

		if ($creatorid == CREATORSOFTWARE_ID_MOBIGEN) { return 'mobigen'; }
		if ($creatorid == CREATORSOFTWARE_ID_MOBIPOCKETCREATOR) { return 'Mobipocket Creator'; }
		if ($creatorid == CREATORSOFTWARE_ID_KINDLEGEN_WINDOWS) { return 'kindlegen (Windows)'; }
		if ($creatorid == CREATORSOFTWARE_ID_KINDLEGEN_LINUX) { return 'kindlegen (Linux)'; }
		if ($creatorid == CREATORSOFTWARE_ID_KINDLEGEN_MAC) { return 'kindlegen (Mac)'; }

		return;
	}

	/**
         * Get the creator major version from EXTH records.
	 * @return int.
         */
	public function get_creatormajor()
	{
		return $this->get_exth_record_data_int(EXTH_RECTYPE_CREATORMAJOR);
	}

	/**
         * Get the creator minor version from EXTH records.
	 * @return int.
         */
	public function get_creatorminor()
	{
		return $this->get_exth_record_data_int(EXTH_RECTYPE_CREATORMINOR);
	}

	/**
         * Get the creator build version from EXTH records.
	 * @return string.
         */
	public function get_creatorbuild()
	{
		return $this->get_exth_record_data_int(EXTH_RECTYPE_CREATORBUILD);
	}

	/**
         * Get the creator version from EXTH records.
	 * @return string.
         */
	public function get_creatorversion()
	{
		$major = $this->get_creatormajor();
		$minor = $this->get_creatorminor();
		$build = $this->get_creatorbuild();

		if (!$major && !$minor && !$build) { return; }

		return ($major ? $major : '0') . '.' . ($minor ? $minor : '0') . (is_numeric($build) ? '.' . $build : $build);
	}

	/**
         * Get the last updated time from EXTH records.
	 * @return date / time.
         */
	public function get_lastupdatetime()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_LASTUPDATETIME);
	}

	/**
         * Get the updated title from EXTH records.
	 * @return string.
         */
	public function get_updatedtitle()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_UPDATEDTITLE);
	}

	/**
         * Get the title from updated title or fullname.
	 * @return string.
         */
	public function get_title()
	{
		$updated_title = $this->get_updatedtitle();

		return $updated_title ? $updated_title : $this->get_fullname();
	}

	/**
         * Get the sort title from updated title or fullname.
	 * @return string.
         */
	public function get_title_sort()
	{
		return preg_replace('/^(A|The|An)\s+(.+)/i', '$2, $1', $this->get_title());
	}

	/**
         * Get the language from EXTH records.
	 * @return string.
         */
	public function get_language()
	{
		return $this->get_exth_record_data(EXTH_RECTYPE_LANGUAGE);
	}

}

?>
