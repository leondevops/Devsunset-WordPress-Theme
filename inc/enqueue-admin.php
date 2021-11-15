<?php

/**

@package DevSunsettheme

===========================================
ADD CUSTOM ITEM TO ADMIN ENQUEUE FUNCTIONS
===========================================

 **/

function devsunset_load_admin_scripts($hook){
  // Return : $hook = toplevel_page_custom_devsunset
  //echo '<h3 id="devsunset-page-name">'.sprintf("The current page is : %s", $hook).'</h3>'; // OK for debugging

  /* 1. Enqueue custom style file for setting pages of WP Admin */
  wp_register_style('devsunset-admin-style', get_template_directory_uri().'/assets/css/devsunset.admin.css',
                          array(), '1.0.0', 'all');

  /* 3. Enqueue custom script file for setting pages of WP Admin
     * Specify array('jquery') since this script requires JQuery dependency to work.
     * - JQuery is loaded by default in Administration panel of WordPress.
     * */
  wp_register_script('devsunset-admin-script',get_template_directory_uri().'/assets/js/devsunset.admin.js',
                            array('jquery'), '1.0.0', true);

  /* Custom Google Font:
  * <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,200;0,300;0,500;1,200;1,300;1,500&display=swap" rel="stylesheet">
  */
  wp_register_style(
    'raleway-admin',
    'https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,200;0,300;0,500;1,200;1,300;1,500&display=swap',
  );


  /*
   * 1. Check if the current slug (page) is :
   * 1.1. Top level, theme, or contact page ~ $hook in the below array list.
   * 1.2. Custom CSS page ~ $hook = 'devsunset_page_custom_devsunset_css'
   * */
  $pages_list = array(
    'toplevel_page_custom_devsunset',
    'devsunset_page_custom_devsunset_theme',
    'devsunset_page_custom_devsunset_theme_contact',
  );

  // 1.1. If in the array list
  if ( in_array($hook, $pages_list) ){
    wp_enqueue_style('raleway-admin');

    /* 1. Enqueue custom style file for setting pages of WP Admin*/
    wp_enqueue_style('devsunset-admin-style');

    /* 2. Register WP Media Uploader */
    wp_enqueue_media();

    /* 3. Enqueue custom script file for setting pages of WP Admin */
    wp_enqueue_script('devsunset-admin-script');

  }
  // 1.2. If the wp admin page is custom CSS page.
  if ( 'devsunset_page_custom_devsunset_css' == $hook) {
    /* 1. Enqueue custom style file for setting pages of WP Admin */
    wp_register_style('ace-css', get_template_directory_uri().'/assets/css/devsunset.ace.css',
                      array(), '1.0.0', 'all' );
    wp_enqueue_style('ace-css');

    /* 2. Register the AJAX Cloud9 Editor script
    * - Source tutorial: https://ace.c9.io/#nav=embedding
    * - Compiled source: https://github.com/ajaxorg/ace-builds/ - ver 1.4.13
    * + It is recommended to use source src-noconflict. Rename to ace
    */
    // Not work item
    wp_register_script('ace-js', get_template_directory_uri().'/assets/js/ace/ace.js', array('jquery'), '1.4.13', true);

    /*wp_register_script('ace-js', get_template_directory_uri().'/js/ace-builds-master/webpack-resolver',
                          array('jquery'), '1.4.13', true);*/

    // Custom JS for custom CSS editor page:
    wp_register_script('devsunset-custom-css-script', get_template_directory_uri().'/assets/js/devsunset.custom_css.js',
                          array('jquery'), '1.0.0', true);
    wp_enqueue_script('ace-js');
    wp_enqueue_script('devsunset-custom-css-script');
  }

}
/**
 * 1. Enqueue custom CSS for the custom admin setting pages.
 * - Latest CSS for setting pages has order 49. Still tracing manually
 * - The hook 'admin_enqueue_scripts' will automatically parse arguments  for callback function 'devsunset_load_admin_scripts'
 *
**/

add_action('admin_enqueue_scripts', 'devsunset_load_admin_scripts', 50);   // Enqueue the latest

/*
* =====================================
 * Custom Front-end enqueue functions
 * ====================================
**/

/* 1. Load external CSS & JS source
 * - Bootsstrap framework
 * - equivalent to method sunset_load_scripts() in tutorial video
 * **/
function devsunset_load_custom_resources(){
  /** === CSS === **/
  // Enqueue Bootstrap CSS 5.1.2
  wp_enqueue_style('bootstrap', get_template_directory_uri().'/assets/css/bootstrap.min.css',
                    array(), '5.1.3', 'all');
  // Enqueue the default Custom Theme Devsunset CSS
  wp_enqueue_style('devsunset', get_template_directory_uri().'/assets/css/devsunset.css',
                    array(), '1.0.0', 'all');
  /* Custom Google Font:
  * <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,200;0,300;0,500;1,200;1,300;1,500&display=swap" rel="stylesheet">
  */
  wp_enqueue_style('raleway', 'https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,200;0,300;0,500;1,200;1,300;1,500&display=swap',
    array(), '1.0.0', 'all');

  /** === JS === **/
  // De-register the default WordPress JQuery
  wp_deregister_script('jquery');

  // Register external jQuery
  wp_register_script('jquery', get_template_directory_uri().'/assets/js/jquery.min.js',
                      false, '3.6.0', true);
  // Enqueue Devsunset JS script before Bootstrap:

  // Enqueue Bootstrap bundle JS 5.1.3. It also include Bootstrap min JS
  wp_enqueue_script('bootstrap-bundle',get_template_directory_uri().'/assets/js/bootstrap.bundle.min.js',
                      array('jquery'), '5.1.3', true);
  // Enqueue Bootstrap JS 5.1.3 - temporary removed at 2021-Aug-27
  /*  wp_enqueue_script('bootstrap',get_template_directory_uri().'/js/bootstrap.min.js',
                        array('jquery'), '5.1.3', true);*/
  // Custom Theme Devsunset script
  wp_enqueue_script('devsunset-script',get_template_directory_uri().'/assets/js/devsunset.js',
                      array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'devsunset_load_custom_resources');


