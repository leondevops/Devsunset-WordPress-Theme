<?php
/*
*  @Package Devsunsettheme
* */
?>

  <nav id="comment-nav-top" class="comments-navigation" role="navigation">
    <div class="row">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="post-link-nav">
          <span class="devsunset-icon devsunset-chevron-left" aria-hidden="true"></span>
          <?php previous_comments_link( esc_html__('Older comments','Devsunsettheme' ) ) ?>
        </div>
      </div><!--col-xs-6 col-sm-6 col-md-6 col-lg-6-->
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-end">
        <div class="post-link-nav">
          <?php next_comments_link( esc_html__('Newer comments','Devsunsettheme' ) ) ?>
          <span class="devsunset-icon devsunset-chevron-right" aria-hidden="true"></span>
        </div>
      </div><!--col-xs-6 col-sm-6 col-md-6 col-lg-6 text-end-->
    </div><!-- row -->
  </nav>

