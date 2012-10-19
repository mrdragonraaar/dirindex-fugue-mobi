<?php
/**
 * mobibook_details.php
 *
 * Display details view info for mobi file.
 * @param $mobi mobi file to display.
 *
 * (c)2012 mrdragonraaar.com
 */
function mobibook_details($mobi) {

global $dirindex_fugue_mobi;

$title = USE_TITLE_SORT ? $mobi->get_title_sort() : $mobi->get_title();
$authors_str = USE_AUTHOR_SORT ? $mobi->get_authors_sort_str() : $mobi->get_authors_str();
$desc = $mobi->get_desc();
$mobi_icon = $dirindex_fugue_mobi::mobi_icon_url();
$filename = basename($mobi->filename);
$filesize = $mobi->get_filesize_str();
$filemtime = $mobi->get_filemtime_str();
$cover_url = $dirindex_fugue_mobi::cover_url($mobi);
$publish_str = $dirindex_fugue_mobi::publish_str($mobi);
$isbn = $mobi->get_isbn();
$amazon_icon = $dirindex_fugue_mobi::amazon_icon_url();
$amazon_search_url = $dirindex_fugue_mobi::amazon_search_url($isbn);
$goodreads_icon = $dirindex_fugue_mobi::goodreads_icon_url();
$goodreads_search_url = $dirindex_fugue_mobi::goodreads_search_url($isbn);
$tags_str = $mobi->get_subjects_str();
$language = $mobi->get_language();
$contributor = $mobi->get_contributor();

?>
<!-- <?php echo $title; ?> -->
<tr class="mobibook_details">
<td><img src="<?php echo $cover_url; ?>" alt="[MOBI]" /></td>
<td>
<!-- Title -->
<h1 class="mobibook_title"><?php echo $title; ?></h1>
<!-- END Title -->

<!-- Author -->
<h2 class="mobibook_author">by <?php echo $authors_str; ?></h2>
<!-- END Author -->

<!-- File -->
<a class="mobibook_file" title="<?php echo $filename; ?>" href="<?php echo $filename; ?>"><img src="<?php echo $mobi_icon; ?>" /><?php echo $filename; ?></a>
<!-- END File -->

<?php if ($desc) { ?>
<!-- Description -->
<div class="mobibook_desc">
<div class="mobibook_desc_text collapse_desc">
<?php echo $desc; ?>
</div>
</div>
<!-- END Description -->
<?php } ?>

<!-- Metadata -->
<div class="mobibook_meta">

<!-- Publish -->
<div class="mobibook_publish"><?php echo $publish_str; ?></div>
<!-- END Publish -->

<table>
<!-- ISBN -->
<?php if ($isbn) { ?>
<tr class="mobibook_isbn"><td>ISBN</td><td><?php echo $isbn; ?>
<!-- ISBN Search Links -->
<span class="mobibook_searchlinks">
<a title="Amazon" target="_blank" href="<?php echo $amazon_search_url; ?>"><img src="<?php echo $amazon_icon; ?>" /></a>
<a title="Goodreads" target="_blank" href="<?php echo $goodreads_search_url; ?>"><img src="<?php echo $goodreads_icon; ?>" /></a>
</span>
<!-- END ISBN Search Links -->
</td></tr>
<?php } ?>
<!-- END ISBN -->
<!-- Tags -->
<?php if ($tags_str) { ?>
<tr class="mobibook_tags"><td>tags</td><td><?php echo $tags_str; ?></td></tr>
<?php } ?>
<!-- END Tags -->
<!-- Language -->
<?php if ($language) { ?>
<tr class="mobibook_language"><td>language</td><td><?php echo $language; ?></td></tr>
<?php } ?>
<!-- END Language -->
<!-- Contributor -->
<?php if ($contributor) { ?>
<tr class="mobibook_contributor"><td>contributor</td><td><?php echo $contributor; ?></td></tr>
<?php } ?>
<!-- END Contributor -->
</table>
</div>
<!-- END Metadata -->
</td>
<td align="right"><?php echo $filemtime; ?></td>
<td align="right"><?php echo $filesize; ?></td>
</tr>
<!-- END <?php echo $title; ?> -->
<?php } ?>
