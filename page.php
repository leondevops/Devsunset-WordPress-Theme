<?php
/*
  *  @Package devsunsettheme
  * */

global $wp_query;
?>

<!-- Get header by loading the file header.php . Remove background color silver #c0c0c0 -->
<?php get_header() ?>

<div id="primary" class="content-area">
  <!--<h1> === This is index.php === </h1>-->
  <main id="main" class="site-main" role="main">
    <?php
    //echo '<p> Requesting Url ($_SERVER["REQUEST_URI"]) : '.$_SERVER["REQUEST_URI"].'</p>';

    // $archivedUrl = $_SERVER["REQUEST_URI"]; // ~ /category/<category_name>/<sub-category-name>/...
    /** 1. This archiveUrl can be categories, tags, author, or page
     * - Need to update in 3 main positions:
     * + Load next button
     * + Load previous button
     * + Posts containers
     */
    $archivedUrl = devsunset_get_client_request_url();

    ?>

    <div class="container devsunset-page-container">
      <?php

      if (have_posts()):
        // check current page within the loop.
        $currentPage = devsunset_get_current_page();
        /*echo sprintf('<div class="page-limit container-page-%s" data-page="/page/%s" data-max-page="%s">',
                      $currentPage, $currentPage, $wp_query->max_num_pages );*/

        if (devsunset_is_paginated_url($archivedUrl)){
          echo sprintf('<div class="page-limit container-page-%s" data-page="%s" data-max-page="%s">',
            $currentPage, $archivedUrl, $wp_query->max_num_pages );
        } else {
          echo sprintf('<div class="page-limit container-page-%s" data-page="%s/page/%s" data-max-page="%s">',
            $currentPage, $archivedUrl, $currentPage, $wp_query->max_num_pages );
        }

        while( have_posts() ):
          the_post();   // Iterate through all posts in the default post loop

          /*
          $isRevealedPost = 'reveal';  //parse this variable to child page template:
          set_query_var('isRevealedPost', $isRevealedPost);
          */

          get_template_part('template-parts/page','general');

        endwhile;

        // Reset the post loop for the next iteration query
        wp_reset_postdata();

        echo sprintf('</div><!--.page-limit container-page-%s-->', $currentPage);
      endif;
      ?>
      <!-- AJAX function append area. Why ??? -->
    </div> <!-- .container -->

  </main>

</div> <!-- #primary -->


<?php get_footer() ?>


