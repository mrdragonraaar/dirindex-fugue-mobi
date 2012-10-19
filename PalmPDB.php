<?php
/**
 * PalmPDB.php
 *
 * (c)2011 mrdragonraaar.com
 */
define('PDB_HEADER_LEN', 32+2+2+(9*4)); // PDB header length
define('PDB_RECORD_INDEX_LEN', 8); // PDB record index length
define('PDB_RECORD_INDEX_HEADER_LEN', 6); // PDB record index header length

/**
 * Parse Palm PDB files.
 *
 * @author Adrian D. Elgar
 */
class PalmPDB
{
	// PDB header data
	public $pdb_header;
	// PDB record index
	public $record_index;
	// PDB records
	public $records = array();
	// PDB file name
	public $filename;
	// PDB file size
	public $filesize;
	// PDB file modified time
	public $filemtime;

	/**
         * Create new PalmPDB instance and
         * load pdb file if specified.
         * @param string $pdbfile PDB file to load.
         */
	function __construct($pdbfile = '')
	{
		if ($pdbfile)
		{
			$this->load($pdbfile);
		}
	}

	/**
         * Load PDB file.
         * @param string $pdbfile PDB file to open.
	 * @return undef on failure.
         */
	public function load($pdbfile)
	{
		$fh = fopen($pdbfile, "rb");
		if ($fh)
		{
			$this->filename = $pdbfile;
			$this->filesize = filesize($pdbfile);
			$this->filemtime = filemtime($pdbfile);

			$this->parse_pdb_header($fh);
			if (!$this->pdb_header) { return; }

			$this->parse_record_index($fh);
			if (!$this->record_index) { return; }

			if ($this->pdb_header['appinfo_id'])
			{
				#$this->parse_appinfo($fh);
			}

			if ($this->pdb_header['sortinfo_id'])
			{
				#$this->parse_sortinfo($fh);
			}

			$this->parse_records($fh);

			fclose($fh);
			return 1;
		}

		return;
	}

	/**
         * Parse PDB header.
         * @param file $fh handle to open PDB file.
         */
	private function parse_pdb_header($fh)
	{
		$buf = fread($fh, PDB_HEADER_LEN);
		$this->pdb_header = unpack("a32name/nattributes/nversion/Nctime/Nmtime/Nbaktime/Nmodnum/Nappinfo_id/Nsortinfo_id/a4type/a4creator/Nunique_id_seed", $buf);
	}

	/**
         * Parse record index.
         * @param file $fh handle to open PDB file.
         */
	private function parse_record_index($fh)
	{
		$this->parse_record_index_header($fh);
		if (!$this->record_index || !$this->record_index['num_recs']) { return; }

		for ($i = 0; $i < $this->record_index['num_recs']; $i++)
		{
			$buf = fread($fh, PDB_RECORD_INDEX_LEN);
			$record_info = unpack("Nrecord_offset/Crecord_attributes/C3id", $buf);
			if (!$record_info) { return; }
			$record_info['id'] = ($record_info['id1'] << 16) | ($record_info['id2'] << 8) | $record_info['id3'];

			$this->record_index['record_info'][] = $record_info;
		}
	}

	/**
         * Parse record index header.
         * @param file $fh handle to open PDB file.
         */
	private function parse_record_index_header($fh)
	{
		$buf = fread($fh, PDB_RECORD_INDEX_HEADER_LEN);
		$this->record_index = unpack("Nnext_rec_id/nnum_recs", $buf);
	}

	/**
         * Parse records.
         * @param file $fh handle to open PDB file.
         */
	private function parse_records($fh)
	{
		for ($i = 0; $i < $this->record_index['num_recs']; $i++)
		{
			$record_offset = $this->record_index['record_info'][$i]['record_offset'];

			// Check we are where we should be
			if (ftell($fh) > $record_offset)
			{
				echo "Bad offset for record $i\n";
			}

			if ($record_offset > $this->filesize)
			{
				echo "Record $i beyond end of database\n";
			}

			// Seek to right place, if necessary
			if (ftell($fh) != $record_offset)
			{
				fseek($fh, $record_offset, 0);
			}

			// Calculate record length
			$record_length = 0;
			if ($i == $this->record_index['num_recs'] - 1)
			{
				// This is the last record
				$record_length = $this->filesize - $record_offset;
			}
			else
			{
				// This is not the last record
				$record_length = $this->record_index['record_info'][$i+1]['record_offset'] - $record_offset;
			}

			if ($record_length)
			{
				$buf = fread($fh, $record_length);
				$this->records[] = $buf;
			}
		}
	}

	/**
	 * Get file modified time as formatted string.
	 * @param $format date format.
	 * @return string.
	 */
	public function get_filemtime_str($format = '')
	{
		$format = $format ? $format : 'd-M-Y H:i';

		return date($format, $this->filemtime);
	}

	/**
	 * Get file size as a string.
	 * @return size as string.
	 */
	public function get_filesize_str()
	{
		$size_kb = round(($this->filesize / 1024), 1); // bytes to KB  
		$size_mb = round(($this->filesize / 1048576), 1); // bytes to MB

		return (($size_mb > 1) ? $size_mb : (($size_kb > 1) ? $size_kb : $this->filesize)) . (($size_mb > 1) ? 'M' : (($size_kb > 1) ? 'K' : ''));
	}

}

?>
