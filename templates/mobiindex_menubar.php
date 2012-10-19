<?php
/**
 * mobiindex_menubar.php
 *
 * Display the MOBIIndex menu bar.
 *
 * (c)2012 mrdragonraaar.com
 */
function mobiindex_menubar() {

global $dirindex_fugue_mobi;

$sort_title_icon = $dirindex_fugue_mobi::sort_title_icon_url();
$sort_title_url = $dirindex_fugue_mobi->sort_title_url();
$sort_author_icon = $dirindex_fugue_mobi::sort_author_icon_url();
$sort_author_url = $dirindex_fugue_mobi->sort_author_url();
$sort_publishing_date_icon = $dirindex_fugue_mobi::sort_publishing_date_icon_url();
$sort_publishing_date_url = $dirindex_fugue_mobi->sort_publishing_date_url();
$view_details_icon = $dirindex_fugue_mobi::view_details_icon_url();
$view_details_url = $dirindex_fugue_mobi->view_details_url();
$view_thumbnails_icon = $dirindex_fugue_mobi::view_thumbnails_icon_url();
$view_thumbnails_url = $dirindex_fugue_mobi->view_thumbnails_url();
$is_thumbnails_view = $dirindex_fugue_mobi->is_view_thumbnails() > 0;

if ($dirindex_fugue_mobi::is_kindle()) { return; }
if (!$dirindex_fugue_mobi->mobi_files()) { return; }

?>
<!-- MOBIIndex Menubar -->
<div id="mobiindex_menubar">
<span id="mobiindex_links_sort">
<a title="Sort by Title" href="<?php echo $sort_title_url; ?>"><img src="<?php echo $sort_title_icon; ?>"/></a>
<a title="Sort by Author" href="<?php echo $sort_author_url; ?>"><img src="<?php echo $sort_author_icon; ?>"/></a>
<a title="Sort by Publishing Date" href="<?php echo $sort_publishing_date_url; ?>"><img src="<?php echo $sort_publishing_date_icon; ?>"/></a>
</span>
<span id="mobiindex_links_view">
<a title="Details View" <?php if (!$is_thumbnails_view) { ?>class="selected_view"<?php } ?> href="<?php echo $view_details_url; ?>"><img src="<?php echo $view_details_icon; ?>"/></a>
<a title="Thumbnails View" <?php if ($is_thumbnails_view) { ?>class="selected_view"<?php } ?> href="<?php echo $view_thumbnails_url; ?>"><img src="<?php echo $view_thumbnails_icon; ?>"/></a>
</span>
</div>
<!-- END MOBIIndex Menubar -->
<?php } ?>
