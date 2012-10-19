<?php
/**
 * mobiindex_thumbnails.php
 *
 * Display thumbnails view of mobi files.
 *
 * (c)2012 mrdragonraaar.com
 */
include('mobibook_thumbnail.php');

function mobiindex_thumbnails() {

global $dirindex_fugue_mobi;

?>
<!-- MOBIIndex Thumbnails -->
<div id="mobiindex_thumbnails">
<?php
	$count = 0;
	foreach ($dirindex_fugue_mobi->mobi_files() as $mobi)
	{
		if (!($count % 5))
		{
?>
<!-- MOBIIndex Shelf -->
<div class="mobiindex_shelf">
<?php
		}

		mobibook_thumbnail($mobi);

		if ($count % 5 == 4)
		{
?>
</div>
<!-- END MobiIndex Shelf -->
<?php
		}

		$count++;
	}

	if ($count % 5)
	{
?>
</div>
<!-- END MobiIndex Shelf -->
<?php
	}
?>
</div>
<!-- END MOBIIndex Thumbnails -->
<?php } ?>
