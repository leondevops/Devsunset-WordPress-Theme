<?php
/*
 *  @Package devsunsettheme
 *  --- Aside post format - default post type
 * - This page can detect the functions defined in /inc
 * - No post title
 * (perhaps PHP storm can detect this page is already included in the index.php
 * (or related PHP page template)).
* */

$isRevealedPost = get_query_var('isRevealedPost');
// echo '<h3> $isRevealedPost : '.var_dump(empty($isRevealedPost)).'</h3';
?>


<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(array('devsunset-format-aside')); ?>>
  <div class="aside-container">
    <div class="row">
      <div class="col-xs-2 col-md-2 col-sm-3 text-center">
        <?php
          // Obtain the featured images of the current post if exists:
          $featuredImage = devsunset_post_get_first_image();
        ?>
        <a class="standard-featured-link" href="<?php the_permalink(); ?>">
          <!-- Make the featured image responsive by making it fit the whole div-->
          <div class="aside-featured background-image"
               style="background-image: url(<?php echo $featuredImage;?>);">
          </div> <!--.standard-featured-->
        </a>
      </div> <!--col-xs-12 col-md-3 col-sm-4-->
      <div class="col-xs-10 col-md-10 col-sm-9">
        <header class="entry-header text-center">
          <div class="entry-meta text-start">
            <?php echo devsunset_posted_meta(); ?>
          </div> <!--.entry-meta-->
        </header>

        <div class="entry-content">
          <div class="entry-excerpt">
            <?php the_excerpt(); ?>
          </div>
        </div> <!--.entry-content-->
      </div> <!--col-xs-10 col-md-10 col-sm-9-->
    </div><!-- row -->

    <div class="row row-entry-footer justify-content-start">
      <div class="col-xs-10 offset-xs-2 col-md-10 offset-md-2 col-sm-9 offset-sm-3">
        <footer class="entry-footer">
          <?php echo devsunset_posted_footer(); ?>
        </footer>
      </div> <!--col-xs-10 col-md-10 col-sm-9-->
    </div><!-- row justify-content-start -->
  </div><!-- aside-container -->
</article>

