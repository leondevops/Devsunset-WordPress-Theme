<?php
/*
  *  @Package devsunsettheme
  * */

/*if ( post_password_required() ){
  return;
}*/
?>

<div id="comments" class="comments-area">
  <?php
    // If having comments
    if( have_comments() ):
  ?>
  <h5 class="comment-title">
    <?php
      $commentsTitleFormat = esc_html(
          _nx('One comment on &ldquo;%2$s&rdquo;',
              '%1$s comments on &ldquo;%2$s&rdquo;',
              get_comments_number(), 'comments title', 'Devsunsettheme')
      );

      printf($commentsTitleFormat, number_format_i18n( get_comments_number() ), '<span>'.get_the_title().'</span>');
    ?>
  </h5>

  <div class="comment-navigation comment-navigation-post-<?php echo get_the_ID();?>">
    <?php devsunset_display_comment_navigation(); ?>
  </div><!-- comment-navigation -->

  <ol class="comment-list">
    <?php
    /**
     * Building input arguments for function wp_list_comments
     * - The argument page, per_page should be declared as "default"
     * - In the middle of writing devsunset_comments_callback to customize the comments display
     */
      $commentsArgs = array(
        'walker'            => null,
        'max_depth'         => '',
        'style'             => 'ol',
        'callback'          => null,
        'end-callback'      => null,
        'type'              => 'all',
        'reply_text'        => 'Reply',
        'page'              => '',
        'per_page'          => '',
        'avatar_size'       => 32,
        'reverse_top_level' => null,
        'reverse_children'  => null,
        'format'            => 'html5',
        'short_ping'        => false,
        'echo'              => true,
      );

      /** Display a list of comments :
       * Can be customized to list comments of other posts (2nd argument)
       * + The 2nd argument is empty by default ~ display only comments of current post
       */
      wp_list_comments( $commentsArgs );  // return nothing
    ?>

  </ol>

  <?php
    // if comments are closes & having comments
    if( !comments_open() && get_comments_number() ): ?>

    <p class="no-comments"><?php esc_html_e('Comments are closed', 'Devsunset theme'); ?></p>

    <?php endif;?>

  <?php endif; // End of having comments  ?>

  <?php
  /** Comment Reply form (!important):
   * - Styling each element of comment forms with Bootstrap 5
   * + Name
   * + Emails
   * + Website
   * + Comments
   */


  devsunset_display_comment_reply();

    ?>

</div><!-- comments-area -->