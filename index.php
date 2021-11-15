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

    <?php if( is_paged() ):?>
    <div class="container container-load-previous text-center">
      <a class="btn-devsunset-load devsunset-load-more-btn devsunset-load-more-prev-btn"
         data-prev-page="<?php echo devsunset_get_previous_page();?>"
         data-page="<?php echo devsunset_get_current_page();?>"
         data-archive="<?php echo $archivedUrl;?>"
         data-url="<?php echo admin_url('admin-ajax.php');?>">
        <span class="devsunset-icon devsunset-loading"></span>
        <span class="text">LOAD PREVIOUS</span>
      </a>
    </div> <!-- .container text-center -->
    <?php endif; ?>

    <div class="container devsunset-posts-container">
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

            get_template_part('template-parts/content', get_post_format());

          endwhile;

          // Reset the post loop for the next iteration query
          wp_reset_postdata();

          echo sprintf('</div><!--.page-limit container-page-%s-->', $currentPage);
        endif;
      ?>
    <!-- AJAX function append area. Why ??? -->
    </div> <!-- .container -->

    <div class="container container-load-next text-center">
      <a class="btn-devsunset-load devsunset-load-more-btn devsunset-load-more-next-btn"
         data-prev-page="<?php echo devsunset_get_previous_page();?>"
         data-page="<?php echo devsunset_get_current_page();?>"
         data-next-page="<?php echo devsunset_get_next_page();?>"
         data-archive="<?php echo $archivedUrl;?>"
         data-url="<?php echo admin_url('admin-ajax.php');?>" >
        <span class="devsunset-icon devsunset-loading"></span>
        <span class="text">LOAD MORE</span>
      </a>
    </div> <!-- .container text-center container-load-next -->
  </main>

</div> <!-- #primary -->


<?php get_footer() ?>


