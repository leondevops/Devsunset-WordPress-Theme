<?php

/**

@package DevSunsettheme

===========================================
ADD CUSTOM ITEM TO ADMIN ENQUEUE FUNCTIONS
===========================================

 **/

/*
 * 1. Remove the WordPress versions
 * (Essentially, it is about remove the queried params 'ver'.
 * ***/
function devsunset_remove_wp_version_strings( $src ) {
  global $wp_version;
  /* 1. Parse the string,
   * - PHP_URL_QUERY is the queried parameters of the string.
   * http://vnlabwin.local.info/wp-includes/css/dist/block-library/style.min.css?ver=5.8.1'
   * - The queried param is "ver" : 5.8.1
   * * Return value is the string after fine-tuned
  */
  parse_str( parse_url($src, PHP_URL_QUERY), $query  );
  if ( !empty( $query['ver'] ) && $query['ver'] === $wp_version ){
    $src = remove_query_arg( 'ver', $src);
  }
  return $src;
}
add_filter('script_loader_src', 'devsunset_remove_wp_version_strings');
add_filter('style_loader_src', 'devsunset_remove_wp_version_strings');

/* 2. Remove the meta tag generator from header **/
add_filter('the_generator', function(){return '';} );

