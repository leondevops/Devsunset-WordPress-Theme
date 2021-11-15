<?php
/**
 * Custom widgets
 * @package Devsunsettheme
 **/

include('widgets/devsunset-widget-custom-profile.php'); // Devsunset custom Profile widget
include('widgets/devsunset-widget-recent-comments.php'); // Custom Recent Comments Widget
include('widgets/devsunset-widget-popular-posts.php');

/* Register the custom widget using WordPress hooks */
/*add_action('widgets_init', 'register_devsunset_profile_widget');
function register_devsunset_profile_widget(){
  register_widget('Devsunset_Profile_Widget');
}*/

/*  Edit default WordPress widgets behaviors
 * - $args: contain default decoration of tags clouds
 * */
add_filter('widget_tag_cloud_args', 'devsunset_tagcloud_font_change');
function devsunset_tagcloud_font_change( $args ){
  $args['smallest'] = 8;
  $args['largest'] = 8;

  return $args;
}


/* Modify the default WordPress categories widget
*- Modify the category item in list categories.
*- Default category item:
* <li class="cat-item cat-item-42">
*	  <a href="http://vnlabwin.local.info/category/giai-tri/">Giải trí</a> (8)
* </li>
* */
add_filter('wp_list_categories', 'devsunset_modify_default_categories_widget');
function devsunset_modify_default_categories_widget($catItems): array|string|null {
  /* Search for "</a> (category_number)"
   * Replace with "</a> <span>category_number</span>"
   * Sample regrex for "</a> (category_number)" - regrex filter all things inside rounded brackets
   * https://stackoverflow.com/questions/8220180/php-regex-with-parentheses
   *  */
  $searchPattern = '/<\/a>\s\(([^)]*)\)/i';  // OK
  $replacePattern = '</a> <span>$1</span>';  // OK  //

  return preg_replace($searchPattern, $replacePattern, $catItems);
}


/* 1. Update posts view to the WordPress database
 * - Create custom meta key 'devsunset_post_views
 * - The custom meta key is saved to the table
 * */
function devsunset_save_post_views( int $postId ){
  $metaKey = 'devsunset_post_views';

  $views = get_post_meta($postId, $metaKey, true);
  $viewsCount = empty($views) ? 0 : $views;
  $viewsCount++;

  update_post_meta($postId, $metaKey, $viewsCount);

  // echo '<p>Total views : '.$viewsCount.'</p>'; // OK for test
}

/** Helper functions to create custom recent posts views
 * Original is 4 arguments - as tutorial: 10, 0
 */

// Remove action display adjacent posts
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

