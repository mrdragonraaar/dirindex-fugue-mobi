<?php
/**
 * mobiindex_details.php
 *
 * Display details view of mobi files.
 *
 * (c)2012 mrdragonraaar.com
 */
include('mobibook_details.php');

function mobiindex_details() {

global $dirindex_fugue_mobi;

?>
<!-- MOBIIndex Details -->
<div id="mobiindex_details">
<table>
<?php
		foreach ($dirindex_fugue_mobi->mobi_files() as $mobi)
		{
			mobibook_details($mobi);
		}
?>
</table>
</div>
<!-- END MOBIIndex Details -->
<?php } ?>
