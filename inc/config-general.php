<?php

/** Define additional global constants */
const DEFAULT_POST_GALLERY_FEATURED_IMAGE = 'http://vnlabwin.local.info/wp-content/uploads/2021/10/default-post-featured_image.jpg';

/** Sidebar */
/* 1. Enable classic editors
* Reference: https://mainwp.com/how-to-restore-the-widget-editor-back-to-use-the-classic-editor/
 * */
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

/* Mobile support */

add_action('after_setup_theme', 'setup_mobile_detect_global');
function setup_mobile_detect_global(){
  global $deviceDetect;
  $deviceDetect = new Mobile_Detect;
}

/** Define additional global variables that store wp_registered styles & scripts
 * - There are a lot of available styles & scripts that have been registered in advance.
 * + Just call & use depend on purposes.
 **/

// For debugging
/*global $registered_wp_scripts;
global $registered_wp_styles;

add_action('wp_print_script', function(){
  global $wp_scripts;
  global $enqueued_scripts;
  $registered_wp_scripts = array();
  foreach( $wp_scripts->queue as $handle ) {
    $registered_wp_scripts[] = $wp_scripts->registered[$handle]->src;
  }
});

add_action('wp_print_script', function(){
  global $wp_styles;
  global $enqueued_styles;
  $registered_wp_styles = array();
  foreach( $wp_styles->queue as $handle ) {
    $registered_wp_styles[] = $wp_styles->registered[$handle]->src;
  }
});*/

/*
 * Register WordPress built-in dashicons
 * https://developer.wordpress.org/resource/dashicons/
 * **/

// add_action('wp_enqueue_scripts', function(){wp_enqueue_style( 'dashicons' );}, 999);
