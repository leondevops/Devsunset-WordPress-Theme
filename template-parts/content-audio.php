<?php
/*
 *  @Package devsunsettheme
 *  --- Audio post format - default post type
 * - This page can detect the functions defined in /inc
 * (perhaps PHP storm can detect this page is already included in the index.php
 * (or related PHP page template)).
* */
$isRevealedPost = get_query_var('isRevealedPost');

?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(array('devsunset-format-audio')); ?>>
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
      // Display the 1st embedded audio media (Soundcloud)
      echo devsunset_post_get_first_embedded_media(array('audio', 'iframe'));
    ?>

  </div> <!--.entry-content-->

  <footer class="entry-footer">
    <?php echo devsunset_posted_footer(); ?>

  </footer>
</article>

