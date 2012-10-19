<?php
/**
 * header.php
 *
 * (c)2012 mrdragonraaar.com
 */
include('functions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?php dirindex_current_url(); ?></title>
<link rel="shortcut icon" href="<?php dirindex_favicon_url(); ?>" />
<link rel="stylesheet" href="<?php dirindex_css_url(); ?>" type="text/css" />
<link rel="stylesheet" href="<?php dirindex_mobi_css_url(); ?>" type="text/css" />
<?php if (!dirindex_is_kindle()) { ?>
<script type="text/javascript" src="<?php jquery_js_url(); ?>"></script>
<script type="text/javascript" src="<?php dirindex_mobi_js_url(); ?>"></script>
<?php } ?>
</head>

<body>
<!-- Content -->
<div id="content" <?php dirindex_kindle_class(); ?>>

<!-- Header -->
<div id="header">
<?php dirindex_searchbox(); ?>
<?php dirindex_path_navbar(); ?>
</div>
<!-- END Header -->

<!-- Directory Listing -->
<div id="dirindex">
