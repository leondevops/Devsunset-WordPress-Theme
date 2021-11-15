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
    <div class="container devsunset-single-post-container">
      <div class="row">
        <div class="col-xs-12 col-lg-8 offset-lg-2 col-md-9 offset-md-1 col-sm-9 offset-sm-1">
          <?php
          if (have_posts()):

            while( have_posts() ):
              the_post();   // Iterate through all posts in the default post loop

              devsunset_save_post_views( get_the_ID() );

              get_template_part('template-parts/single', get_post_format());

              //the_post_navigation();
              echo devsunset_get_custom_post_navigation();

              // Open the comment template: comments.php
              if (comments_open()):
                comments_template();    // Return just a form to comments
              else:
                echo '<p class="text-center">Sorry, comments are disabled</p>';
              endif;


            endwhile;

            // Reset the post loop for the next iteration query
            wp_reset_postdata();

          endif;
          ?>
        </div><!-- col-xs-12 col-lg-8 offset-lg-2 col-md-9 offset-md-1 col-sm-9 offset-sm-1-->

      </div><!-- row-->
    </div> <!-- .container -->

  </main>

</div> <!-- #primary -->


<?php get_footer() ?>


