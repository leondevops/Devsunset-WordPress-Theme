<?php
/*
 *  @Package devsunsettheme
 *  --- Standard post format - default post type
 * - This page can detect the functions defined in /inc
 * (perhaps PHP storm can detect this page is already included in the index.php
 * (or related PHP page template)).
* */
$isRevealedPost = get_query_var('isRevealedPost');

?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?>>
  <header class="entry-header text-center">
    <?php
      the_title('<h2 class="entry-title"><a href="'.esc_url(get_permalink()).'" rel="bookmark">',
                '</a></h2>');
    ?>


    <div class="entry-meta">
      <?php echo devsunset_posted_meta(); ?>
    </div> <!--.entry-meta-->

  </header>

  <div class="entry-content">

    <?php
      // Obtain the featured images of the current post if exists:
      $featuredImage = devsunset_post_get_first_image();
      ?>
      <a class="standard-featured-link" href="<?php the_permalink(); ?>">
        <!-- Make the featured image responsive by making it fit the whole div-->
        <div class="standard-featured background-image"
             style="background-image: url(<?php echo $featuredImage;?>);">
        </div> <!--.standard-featured-->
      </a>



    <div class="entry-excerpt">
      <?php the_excerpt(); ?>
    </div>

    <div class="button-container text-center">
      <a href="<?php the_permalink(); ?>" class="btn btn-devsunset"><?php _e('Read More');?></a>
    </div> <!--.button-contain-->

  </div> <!--.entry-content-->

  <footer class="entry-footer">
    <?php echo devsunset_posted_footer(); ?>

  </footer>
</article>

