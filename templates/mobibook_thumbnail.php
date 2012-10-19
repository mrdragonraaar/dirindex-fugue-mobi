<?php
/**
 * mobibook_thumbnail.php
 *
 * Display thumbnails view info for mobi file.
 * @param $mobi mobi file to display.
 *
 * (c)2012 mrdragonraaar.com
 */
function mobibook_thumbnail($mobi) {

global $dirindex_fugue_mobi;

$title = USE_TITLE_SORT ? $mobi->get_title_sort() : $mobi->get_title();
$authors_str = USE_AUTHOR_SORT ? $mobi->get_authors_sort_str() : $mobi->get_authors_str();
$cover_url = $dirindex_fugue_mobi::cover_url($mobi);
$filename = basename($mobi->filename);

?>
<!-- <?php echo $title; ?> -->
<div class="mobibook_thumbnail">
<a title="<?php echo $filename; ?>" href="<?php echo $filename; ?>"><img class="mobibook_cover" src="<?php echo $cover_url; ?>" alt="[MOBI]" /></a>

<!-- Title -->
<h1 class="mobibook_title"><?php echo $title; ?></h1>
<!-- END Title -->

<!-- Author -->
<h2 class="mobibook_author"><?php echo $authors_str; ?></h2>
<!-- END Author -->
</div>
<!-- END <?php echo $title; ?> -->
<?php } ?>
