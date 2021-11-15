<?php
/**
@package DevSunsettheme

===========================================
AJAX FUNCTIONS
===========================================
 **/

/* 1. Hook the AJAX calls to PHP callback function
* - Using WP action hooks wp_ajax_nopriv_<callback function name> & wp_ajax_<callback function name>
*/

use JetBrains\PhpStorm\NoReturn;


/**
* 1. Generic actions to handle load (more next & more previous) posts
 **/

add_action('wp_ajax_nopriv_devsunset_load_more','devsunset_load_more');
add_action('wp_ajax_devsunset_load_more','devsunset_load_more');

#[NoReturn] function devsunset_load_more(): void{
  // $queryNextPage = $_POST["queryNextPage"] + 1;   // as tutorial
  $queryNextPage = $_POST["queryNextPage"];
  // $queryPrevPage = $_POST["queryPrevPage"];

  $customQuery = new WP_Query( array(
    'post_type'     => 'post',
    'post_status'   => 'publish',
    'paged'         => $queryNextPage,
  ) );

  // echo '<h3> previous page is : '.$queryPrevPage.'</h3>';

  // Need to reload JavaScript devsunset.js when implementing Custom Query
  if( $customQuery->have_posts() ):
    //echo '<div class="page-limit" data-page="/page/'.$queryPage.'/">';
    echo sprintf('<div class="page-limit" data-page="/page/%s">', $queryNextPage);

    while ( $customQuery->have_posts() ): $customQuery->the_post();
      get_template_part('template-parts/content', get_post_format());
    endwhile;

    echo '</div><!--.page-limit-->';
  endif;

  //
  wp_reset_postdata();

  // Close the current connection to ajax-admin.php
  die();
}

/**
 * 1. AJAX callback to handle "load more next post action"
 **/
add_action('wp_ajax_nopriv_devsunset_load_more_next','devsunset_load_more_next');
add_action('wp_ajax_devsunset_load_more_next','devsunset_load_more_next');

#[NoReturn] function devsunset_load_more_next(): void{
  // $queryNextPage = $_POST["queryNextPage"] + 1;   // as tutorial
  $queryNextPage = $_POST["queryNextPage"];
  // echo '<p> The $queryNextPage is : '.var_dump($queryNextPage).'</p>';
  $archivePageInfo = $_POST["archivePage"]; // contain both paginated url or not
  //$queryPrevPage = $_POST["queryPrevPage"];
  // echo '<p> The $archivePageInfo is : '.var_dump($archivePageInfo).'</p>';

  $queryArgs = array(
    'post_type'     => 'post',
    'post_status'   => 'publish',
    'paged'         => $queryNextPage,
  );

  // If having archive page. Must indicate '0' letter to avoid dynamic data type of PHP
  if( $archivePageInfo != '0' ) {
    // Obtain an array of archived page info
    $archivedInfos = explode('/', $archivePageInfo); // OK
    $flippedArchivedInfos = array_flip($archivedInfos);
    //echo '<p> $archivedInfos: '.var_dump($archivedInfos).'</p>';

    /**
     * 1. Check if archived page is category, tag, author, page
     * - For each case, need to update 2 things:
     * + Query arguments ( $queryArgs[ $queryKey ] = $queryCategoryValue; )
     * + $pageTrail
     */

    /* 1.1. Create pre-defined array contain taxonomies: category, tag, author
    * -Sub item: taxonomy_name : category , query_type : category_name
     *  */
    $taxonomiesList = array(
      array('category', 'category_name'),
      array('tag', 'tag'),
      array('author', 'author')
    );

    $pageTrail = '';
    foreach($taxonomiesList as $taxonomy):
      if( isset( $flippedArchivedInfos[ $taxonomy[0] ] ) ):
        $queryKey = $taxonomy[1];

        $taxonomyKey = $flippedArchivedInfos[ $taxonomy[0] ];
        /*
         * 1. Check if this is a paginated url,
         * 1.1. True:
         * - Assign the latest cagegory/tag/author - $subCategories 3rd element from the end of the array
         * + Remove the last 2 element of $subCategories
         * + Get the last element of $subCategories
         *
         * 1.2. Not true (not a paginated url):
         * - Assign the latest category/tag/author - $subCategories last element
         * */
        $subTaxonomies = array_slice($archivedInfos, $taxonomyKey + 1);
        // echo '<p> $subTaxonomies is : '.var_dump($subTaxonomies).' </p>';
        // If this is paginated archived url
        if(in_array('page', $subTaxonomies )){
          // Remove the last 2 item in subCategories to obtain categories
          array_splice($subTaxonomies, -2);

          $queryValue = end($subTaxonomies); // OK// 3rd element from end array is latest categories

          // Remove the last item (page number) of the whole archived path info
          //array_splice($archivedInfos, -1); // OK
          //$archivedInfos[] = $queryNextPage; // OK - Update new page value

          array_splice($archivedInfos, -1,1, array($queryNextPage));  // Equivalent

          $pageTrail = implode('/', $archivedInfos);    //OK
        } else{
          $queryValue = end($subTaxonomies); // OK
          $pageTrail = $archivePageInfo . '/page/' . $queryNextPage;
        }

        // Add query argument for category
        $queryArgs[$queryKey] = $queryValue; // OK

        break;  // stop iterating through foreach

      else: // Not contain anything. Use default query with paginated info
        // 1. Update page trail
        if (isset($flippedArchivedInfos['page'])){
          array_splice($archivedInfos, -1);
          $archivedInfos[] = $queryNextPage; // OK - Update new page value

          $pageTrail = implode('/', $archivedInfos);    //OK
        } else {
          $pageTrail = $archivePageInfo . '/page/' . $queryNextPage;
        }

        // 2. No queried argument will be added
      endif;
    endforeach;
  } else {
    $pageTrail = $archivePageInfo;
  }

  //echo '<p> $pageTrail : '.var_dump($pageTrail).'</p>';
  //echo '<p> Query arguments : '.var_dump($queryArgs).'</p>';
  $customQuery = new WP_Query( $queryArgs );

  // Need to reload JavaScript devsunset.js when implementing Custom Query
  if( $customQuery->have_posts() ):
    //echo '<div class="page-limit" data-page="/page/'.$queryPage.'/">';
    /*echo sprintf('<div class="page-limit container-page-%s" data-page="%s/page/%s" data-max-page="%s">',
                  $queryNextPage, $pageTrail, $queryNextPage, $customQuery->max_num_pages);*/
    echo sprintf('<div class="page-limit container-page-%s" data-page="%s" data-max-page="%s">',
                    $queryNextPage, $pageTrail, $queryNextPage, $customQuery->max_num_pages);

    while ( $customQuery->have_posts() ): $customQuery->the_post();
      get_template_part('template-parts/content', get_post_format());
    endwhile;

    echo sprintf('</div><!--.page-limit container-page-%s-->', $queryNextPage);
  endif;

  //
  wp_reset_postdata();

  // Close the current connection to ajax-admin.php
  die();
}

/**
 * 2. AJAX callback to handle "load more previous post action"
 **/
add_action('wp_ajax_nopriv_devsunset_load_more_previous','devsunset_load_more_previous');
add_action('wp_ajax_devsunset_load_more_previous','devsunset_load_more_previous');

#[NoReturn] function devsunset_load_more_previous(): void{
  // $queryNextPage = $_POST["queryNextPage"] + 1;   // as tutorial
  //$queryNextPage = $_POST["queryNextPage"];
  $queryPrevPage = $_POST["queryPrevPage"];
  $archivePageInfo = $_POST["archivePage"];

  $queryArgs = array(
    'post_type'     => 'post',
    'post_status'   => 'publish',
    'paged'         => $queryPrevPage,
  );

  // If having archive page. Must indicate '0' letter to avoid dynamic data type of PHP
  if( $archivePageInfo != '0' ) {
    // Obtain an array of archived page info
    $archivedInfos = explode('/', $archivePageInfo); // OK
    $flippedArchivedInfos = array_flip($archivedInfos);
    //echo '<p> $archivedInfos: '.var_dump($archivedInfos).'</p>';

    /**
     * 1. Check if archived page is category, tag, author, page
     * - For each case, need to update 2 things:
     * + Query arguments ( $queryArgs[ $queryKey ] = $queryCategoryValue; )
     * + $pageTrail
     */

    /* 1.1. Create pre-defined array contain taxonomies: category, tag, author
    * -Sub item: taxonomy_name : category , query_type : category_name
     *  */
    $taxonomiesList = array(
      array('category', 'category_name'),
      array('tag', 'tag'),
      array('author', 'author')
    );

    $pageTrail = '';
    foreach($taxonomiesList as $taxonomy):
      if( isset( $flippedArchivedInfos[ $taxonomy[0] ] ) ):
        $queryKey = $taxonomy[1];

        $taxonomyKey = $flippedArchivedInfos[ $taxonomy[0] ];
        /*
         * 1. Check if this is a paginated url,
         * 1.1. True:
         * - Assign the latest cagegory/tag/author - $subCategories 3rd element from the end of the array
         * + Remove the last 2 element of $subCategories
         * + Get the last element of $subCategories
         *
         * 1.2. Not true (not a paginated url):
         * - Assign the latest category/tag/author - $subCategories last element
         * */
        $subTaxonomies = array_slice($archivedInfos, $taxonomyKey + 1);
        // echo '<p> $subTaxonomies is : '.var_dump($subTaxonomies).' </p>';
        // If this is paginated archived url
        if(in_array('page', $subTaxonomies )){
          // Remove the last 2 item in subCategories to obtain categories
          array_splice($subTaxonomies, -2);

          $queryValue = end($subTaxonomies); // OK// 3rd element from end array is latest categories

          // Remove the last item (page number) of the whole archived path info
          //array_splice($archivedInfos, -1); // OK
          //$archivedInfos[] = $queryPrevPage; // OK - Update new page value

          array_splice($archivedInfos, -1,1, array($queryPrevPage));

          $pageTrail = implode('/', $archivedInfos);    //OK
          //echo '<p> $pageTrail 1 : '.var_dump($pageTrail).'</p>';
        } else{
          $queryValue = end($subTaxonomies); // OK
          $pageTrail = $archivePageInfo . '/page/' . $queryPrevPage;
        }

        // Add query argument for category
        $queryArgs[$queryKey] = $queryValue; // OK

        break;  // stop iterating through foreach

      else: // Not contain anything. Use default query with paginated info
        // 1. Update page trail
        if (isset($flippedArchivedInfos['page'])){
          array_splice($archivedInfos, -1);
          $archivedInfos[] = $queryPrevPage; // OK - Update new page value

          $pageTrail = implode('/', $archivedInfos);    //OK
        } else {
          $pageTrail = $archivePageInfo . '/page/' . $queryPrevPage;
        }

        // 2. No queried argument will be added
      endif;
    endforeach;
  } else {
    $pageTrail = $archivePageInfo;
  }

  $customQuery = new WP_Query( $queryArgs );

  // echo '<h5> The previous page to be queried is : '.$queryPrevPage.'</h5>';

  // Need to reload JavaScript devsunset.js when implementing Custom Query
  if( $customQuery->have_posts() ):
    //echo '<div class="page-limit" data-page="/page/'.$queryPage.'/">';
    //echo sprintf('<div class="page-limit query-page-previous" data-page="/page/%s">', $queryPrevPage);
    /*echo sprintf('<div class="page-limit container-page-%s" data-page="%spage/%s" data-max-page="%s">',
                  $queryPrevPage, $pageTrail, $queryPrevPage, $customQuery->max_num_pages);*/
    echo sprintf('<div class="page-limit container-page-%s" data-page="%s" data-max-page="%s">',
      $queryPrevPage, $pageTrail, $queryPrevPage, $customQuery->max_num_pages);

    while ( $customQuery->have_posts() ): $customQuery->the_post();
      get_template_part('template-parts/content', get_post_format());
    endwhile;

    echo sprintf('</div><!--.page-limit container-page-%s-->', $queryPrevPage);
  endif;

  //
  wp_reset_postdata();

  // Close the current connection to ajax-admin.php
  die();
}

/*
 *  * ===================================================
 *  Contact form handler
 *  * ===================================================
 * */

add_action('wp_ajax_nopriv_devsunset_save_user_contact_form', 'devsunset_save_user_contact');
add_action('wp_ajax_devsunset_save_user_contact_form', 'devsunset_save_user_contact');
function devsunset_save_user_contact(){
  $title = wp_strip_all_tags($_POST['name']);
  $email = wp_strip_all_tags($_POST['email']);
  $message = wp_strip_all_tags($_POST['message']); // Not really a securiy issue if writing HTML tag here

  /** Create an argument of entry that will be saved into database
   * Save this entry as custom post type, with the custom post meta key
   * - author : 1 - default admin author (to gather all non-logged-in users)
   * - meta_input: meta key that we want to save in custom post type
   *
   * "_contact_email_value_key" is obtained from custom post type.
   **/
  $contactArgs = array(
    'post_title'      => $title,
    'post_content'    => $message,
    'post_author'     => 1,
    'post_status'     => 'publish',
    'post_type'       => 'devsunset-contact',
    'meta_input'      => array('_contact_email_value_key' => $email),
  );

  /* 1. Update the contact information as custom post type "Message"
   * - Return a postId if successfully inserting the entry to WordPress database.
   * */
  $postId = wp_insert_post( $contactArgs );

  // Send email notify to admin users if successful (vnlab.manager@gmail.com)
  if($postId !== 0){
    $mailTo = get_bloginfo('admin_email');  // vnlab.manager@gmail.com
    $mailSubject = 'Devsunset Contact Form - client '.$title;

    $mailHeaders = array();
    $mailHeaders[] = sprintf('From: %s <%s>', get_bloginfo('name'), $mailTo);
    $mailHeaders[] = sprintf('Reply-To: %s <%s>', $title, $email);
    // $mailHeaders[] = 'Content-Type: text/html:charset=UTF-8';  // Send as default email format.

    $mailMessage = sprintf(
      'Submitted client: %s'.PHP_EOL.'Email: %s'.PHP_EOL.'---Message :'.PHP_EOL.'%s'.PHP_EOL.'--- End of message ---',
      $title, $email, $message
    );

    $mailStatus = wp_mail($mailTo, $mailSubject, $mailMessage, $mailHeaders); // mail submission fail
  }


  echo '<p> Saved contact entry status is : '.$postId.'</p>';

  die();
}
/**
 * ===================================================
 * Helper function
 * ===================================================
 */

/** Check current page - original methods from tutorial
 *
 */
function devsunset_check_paged( $pageNumber = null){
  $output = '';

  // Check if the current status is paginated?
  if( is_paged() ){
    $output = 'page/'.get_query_var('paged');
  }

  if ($pageNumber == 1){
    return get_query_var('paged') == 0 ? 1 : get_query_var('paged');
  } else {
    return $output;
  }

}


function devsunset_get_previous_page(){
  $currentPage = devsunset_get_current_page();

  return ($currentPage == 0) ? 0 : $currentPage - 1;
}

function devsunset_get_next_page(){
  return devsunset_get_current_page() + 1;
}

/* Get the current page:
 * http://vnlabwin.local.info/page/4 ~ current page = 4
 * - Obtain from function get_query_var('paged')
 * - Need to get current page from full slugs
 * */
function devsunset_get_current_page(){
  /** @noinspection PhpIncludeInspection */
  /*include_once(get_template_directory().'/troubleshoot/custom-theme-debugger.php'); // Include debug OK
  $debugger = new Custom_Theme_Debugger();
  $debugger->write_log_general(get_query_var('paged'));*/
  $currentRequestUrl = home_url($_SERVER['REQUEST_URI']);
  $parsedUrl = parse_url($currentRequestUrl);

  $isContainPageOrder = preg_match('~/page/(\d+)/~', $parsedUrl['path'], $matches );
  return ($isContainPageOrder == 1) ? $matches[1] : 1;

  /*if( is_paged() ){
   return get_query_var('paged');
   } else{
     return get_query_var('paged') == 0 ? 1 : get_query_var('paged');
   }*/
  //$currentPage = get_query_var('paged');
  //return is_paged() ? $currentPage : ($currentPage == 0 ? 1 : $currentPage);

}

function devsunset_get_client_request_url(): string {
  // $_SERVER['HTTP_HOST'] = vnlabwin.local.info/
  // $_SERVER['REQUEST_URI'] = /tag/entertainment/
  // $_SERVER['HTTP_REFERER']; // http://vnlabwin.local.info/tag/entertainment/
  $requestProtocol = ( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on') ) ? "https" : "http";

  return sprintf('%s://%s%s',
    $requestProtocol,
    rtrim($_SERVER['HTTP_HOST'], '/'),
    rtrim($_SERVER['REQUEST_URI'],'/')
  );
}

/* Check if the URL already contain paginated slug in format:
* http://vnlabwin.local.info/page/3
* http://vnlabwin.local.info/tag/entertainment/page/2
 *
*/
function devsunset_is_paginated_url(string $url): bool
{
  $urlComponents = explode( '/',  $url); // OK
  return in_array('page', $urlComponents);
}
