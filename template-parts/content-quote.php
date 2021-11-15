<?php
/*
 *  @Package devsunsettheme
 *  --- Quote post format - default post type
 * - This page can detect the functions defined in /inc
 * (perhaps PHP storm can detect this page is already included in the index.php
 * (or related PHP page template)).
* */

$isRevealedPost = get_query_var('isRevealedPost');
?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(array('devsunset-format-quote')); ?>>
  <header class="entry-header text-center">

    <div class="row">
      <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2 mx-auto ms-auto md-auto">
        <div class="entry-excerpt quote-content">
          <a href="<?php echo get_permalink(); ?>">
          <?php the_excerpt(); ?>
          </a>
        </div> <!--entry-excerpt quote-content-->
        <?php
          the_title('<h2 class="entry-title quote-author"><a href="'.esc_url(get_permalink()).'" rel="bookmark">',
            '</a></h2>');
        ?>
      </div> <!--.col-sm-10-->
    </div> <!--.row-->



  </header>

  <footer class="entry-footer">
    <?php echo devsunset_posted_footer(); ?>

  </footer>
</article>

