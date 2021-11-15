<?php
/*
 *  @Package devsunsettheme
 *  --- Link post format - default post type
 * - This page can detect the functions defined in /inc
 * (perhaps PHP storm can detect this page is already included in the index.php
 * (or related PHP page template)).
* */

$isRevealedPost = get_query_var('isRevealedPost');
?>

<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(array('devsunset-format-link')); ?>>
  <header class="entry-header text-center">
    <?php
    $links = devsunset_post_get_first_link();
      the_title(
        '<h2 class="entry-title"><a href="'.$links.'" target="_blank">',
        '<div class="link-icon"><span class="devsunset-icon devsunset-link"></span></div></a></h2>'
        );

        //OK
      //$links = devsunset_post_get_all_links(); //OK
      // echo '<p> $links : '.var_dump($links).'</p>';
    ?>
  </header>

  <div class="entry-content">
    <div class="entry-excerpt">
      <?php the_excerpt(); ?>
    </div>

    <div class="button-container text-center">
      <a href="<?php the_permalink(); ?>" class="btn btn-devsunset"><?php _e('Read More');?></a>
    </div> <!--.button-contain-->

  <footer class="entry-footer">
    <?php echo devsunset_posted_footer(); ?>

  </footer>
</article>

