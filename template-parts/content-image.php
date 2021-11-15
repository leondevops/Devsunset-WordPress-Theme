<?php
/*
 *  @Package devsunsettheme
 *  --- Image post format - default post type
 * - This page can detect the functions defined in /inc
 * (perhaps PHP storm can detect this page is already included in the index.php
 * (or related PHP page template)).
* */
$isRevealedPost = get_query_var('isRevealedPost');

?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(array('devsunset-format-image')); ?>>

  <!-- Featured images -->
  <?php
    $featuredImage = devsunset_post_get_first_image(); // defined in theme_support.php

    /*$attachedImgs = devsunset_post_get_attached_images();
    echo '<p>$attachedImgs :'.var_dump($attachedImgs).' </p>';*/
  ?>

  <header class="entry-header text-center background-image"
          style="background-image: url(<?php echo $featuredImage;?>);">

    <?php
      the_title('<h2 class="entry-title"><a href="'.esc_url(get_permalink()).'" rel="bookmark">',
                '</a></h2>');
    ?>

    <div class="entry-meta">
      <?php echo devsunset_posted_meta(); ?>
    </div> <!--.entry-meta-->

    <div class="entry-excerpt image-caption">
      <?php the_excerpt(); ?>
    </div> <!--.entry-excerpt-->

  </header>




  <footer class="entry-footer">
    <?php echo devsunset_posted_footer(); ?>

  </footer>
</article>

