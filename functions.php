<?php
/**
 * Header template
 * @package Devsunsettheme
 **/

/* Include vendor lib
 * * Detail list of vendor library:
 * - Mobile detect
 * */
include('inc/vendor/autoload.php');

include('inc/config-general.php');


include('inc/cleanup.php');
// Custom Admin menu
include('inc/function-admin.php');
include('inc/enqueue-admin.php');
include('inc/mail-config.php');

/** Include the file that contain custom theme support :
 *  1. Enable the selection of different post format (default post).
 **/
include('inc/theme-support.php');

include('inc/theme-comments.php');
include('inc/custom-post-type.php');
include('inc/ajax.php');
include('inc/shortcode.php'); // All shortcode using in the post contents
include('inc/custom-widgets.php');   // Custom widgets

// Custom Walker Class for Navigation Menu
// include('inc/menu-navigation-custom-walker.php');


