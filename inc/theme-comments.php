<?php

/** 1. Comment navigation **/
function devsunset_display_comment_navigation(){
  if( get_comment_pages_count() >= 1 && get_option('page_comments') ):
    require(get_template_directory().'/inc/templates/devsunset-navigation-comments.php');
  endif;
}

/** 1. Comment reply form **/

function devsunset_display_comment_reply(){
  $commenter = wp_get_current_commenter();

  $authorField = <<<HDSTR
  <div class="form-group">
    <label for="author">%s</label><span class="required">*</span>    
    <input id="author" name="author" type="text" class="form-control" value="%s" required="required">
  </div>
  HDSTR;

  $emailField = <<<HDSTR
  <div class="form-group">
    <label for="email">%s</label><span class="required">*</span>    
    <input id="email" name="email" type="text" class="form-control" value="%s" required="required">
  </div>
  HDSTR;

  $urlField = <<<HDSTR
  <div class="form-group">
    <label for="url">%s</label><span class="required">*</span>    
    <input id="url" name="url" type="text" class="form-control" value="%s" required="required">
  </div>
  HDSTR;

  $commentField = <<<HDSTR
  <div class="form-group">
    <label for="comment">%s</label>
    <textarea id="comment" class="form-control" name="comment" rows="4" required="required"></textarea>
  </div>
  HDSTR;

  $allFields = array(
    'author'=> sprintf($authorField, __('Name', 'domainreference'), esc_attr($commenter['comment_author'])),
    'email' => sprintf($emailField, __('Email', 'domainreference'), esc_attr($commenter['comment_author_email'])),
    'url'   => sprintf($urlField, __('Website', 'domainreference'), esc_attr($commenter['comment_author_url'])),
  );

  $replyArgs = array(
    'class_submit'  => 'btn btn-block btn-lg btn-warning',
    'label_submit'  => __('Submit Content'),
    'comment_field' => sprintf($commentField, _x('Comment','noun')),
    'fields'        => apply_filters('comment_form_default_fields', $allFields)

  );

  comment_form( $replyArgs );
}



/**
 * Use WP end callbacks & callback to customize the comment display
 * I. Full comments properties
 * 1. Author info
 * - Name
 * - Email
 *
 * 2. Comment metadata
 * - Posted date
 * - Last editted date
 *
 * 3. Commennt content
 *
 * 4. Comment responses
 * - Include comments objects
 *
 **/


function devsunset_comments_callback(){
  // List comments
  echo devsunset_get_list_comments();
  // Comment reply
  // comment_form();
}

/** === Not working  === **/
function devsunset_display_custom_list_comments(){
  echo devsunset_get_custom_list_comments();
}
/** === Not working  === **/
function devsunset_get_custom_list_comments(): string
{
  $currentCmt = get_comment();
  /** @noinspection PhpIncludeInspection */
  include_once(get_template_directory().'/troubleshoot/custom-theme-debugger.php'); // Include debug OK

// 1. Initialize debugger
  $debugger = new Custom_Theme_Debugger();
  // $debugger->write_log_general($currentCmt);

  $commentMetaFormat = <<<HDSTR
  <div class="devsunset-comment-entry comment-meta">
    <div class="row">
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 devsunset-comment-author">
        <span class="comment-author-avatar">%s</span>
        <span class="comment-author-name">%s</span>
      </div>
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 devsunset-comment-metadata text-end">
        <span class="devsunset-icon devsunset-clock"></span>
        %s : %s
      </div>
    </div><!-- row -->
  </div><!--devsunset-comment-entry comment-meta-->
  HDSTR;
  $commentMeta = sprintf($commentMetaFormat,
    get_avatar($currentCmt->comment_ID , 32), get_comment_author($currentCmt->comment_ID),
    get_comment_date('j M Y'), devsunset_get_different_comment_time($currentCmt->comment_ID)
  );

  $commentBodyFormat = <<<HDSTR
  <div class="devsunset-comment-entry comment-body">
    <p>%s</p>
  </div><!--devsunset-comment-entry comment-body-->
  HDSTR;
  $commentBody = sprintf($commentBodyFormat, get_comment_text($currentCmt->comment_ID));

  /***
  <div class="reply">
  <a rel="nofollow" class="comment-reply-link"
  href="#comment-%s" data-commentid="%s" data-postid="%s" data-respondelement="respond"
  data-belowelement="div-comment-%s" data-replyto="Reply to %s" aria-label="Reply to %s">
  <span class="devsunset-icon devsunset-comment-bubble"></span>Reply</a>
  </div>
   */
  $commentReplyFormat = <<<HDSTR
  <div class="devsunset-comment-entry comment-reply">
    <div class="row">
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6">      
        %s
                  
      </div><!--col-xs-6 col-lg-6 col-md-6 col-sm-6-->
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 text-end">        
      </div><!--col-xs-6 col-lg-6 col-md-6 col-sm-6-->
    </div><!--row -->
  </div><!--devsunset-comment-entry comment-body-->
  HDSTR;

  $replyArgs = array(
    'reply_text'  => 'Reply',
    'before'      => '<span class="devsunset-icon devsunset-comment-bubble"></span>',
    'after'       => ''
  );

  $debugger->write_log_general((get_comment_reply_link($replyArgs)));
  $commentReply = sprintf($commentReplyFormat,
    get_comment_reply_link($replyArgs)
  );

  $commentFormat = <<<HDSTR
  <div id="comment-id-%s-post-%s" class="devsunset-comment-entry comment-id-%s"
       style="margin-top:0.25em;margin-bottom:0.25em;">
       {$commentMeta}
       {$commentBody}
       {$commentReply}
  </div> <!-- devsunset-comment-entry--> 
  HDSTR;

  return sprintf($commentFormat,
    $currentCmt->comment_ID , $currentCmt->comment_post_ID, $currentCmt->comment_ID,);

  /*==================================*/
}

function devsunset_get_list_comments(): string
{
  $currentCmt = get_comment();

  // Get reply comment block
  /*$getReplyCommentBlock = function(){
    ob_start();
    comment_form();
    return ob_get_clean();
  };*/

  /**
   * get_comment_ID()
   * get_comment_author( get_comment_ID() )
   * get_comment_meta()
   *
   * get_comment_text()
   **/
  $commentsFormat = <<<HDSTR
  <div id="comment-id-%s-post-%s" class="devsunset-comment-entry comment-id-%s"
       style="margin-top:0.25em;margin-bottom:0.25em;">
    <div class="row devsunset-comment-begin" 
          style="background-color:rgba(0,0,0,0.1);border:1px solid black;">
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 devsunset-comment-author">
        <span class="comment-author-avatar">%s</span>
        <span class="comment-author-name">%s</span>
      </div>
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 devsunset-comment-metadata text-end">
        <span class="devsunset-icon devsunset-clock"></span>
        %s : %s
      </div>
    </div><!--row devsunset-comment-begin-->
    <div class="row devsunset-comment-body" style="border:1px solid black;">      
      <p>%s</p>
    </div><!--row devsunset-comment-body-->
    <div class="row devsunset-comment-footer" style="border:1px solid black;">
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6">      
        <a><span class="devsunset-icon devsunset-comment-bubble"></span>Reply</a>
        <a><span class="devsunset-icon devsunset-edit"></span>Edit</a>        
      </div>
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 text-end">
        <a><span class="devsunset-icon devsunset-delete"></span>Delete</a>
      </div>
    </div><!-- devsunset-comment-entry devsunset-comment-footer-->    
    <div class="devsunset-comment-reply" 
        style="border:1px solid black;display:none">       
    </div><!-- devsunset-comment-reply-->  
  </div> <!-- devsunset-comment-entry-->      
    
  HDSTR;

  return sprintf($commentsFormat,
    $currentCmt->comment_ID , $currentCmt->comment_post_ID, $currentCmt->comment_ID,
    get_avatar($currentCmt->comment_ID , 32),get_comment_author($currentCmt->comment_ID),
    get_comment_date('j M Y'), devsunset_get_different_comment_time($currentCmt->comment_ID),
    get_comment_text($currentCmt->comment_ID)
  );

  //echo '<p> ====== This is the callback of comments list  ====== </p>';
}


function devsunset_comments_end_callback(){

  $endCommentsFormat = <<<HDSTR
  <div class="devsunset-comment-entry devsunset-comment-end">
    <div class="row">
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 devsunset-comment-author">      
        <a><span class="devsunset-icon devsunset-comment-bubble"></span>Reply</a>
        <a><span class="devsunset-icon devsunset-edit"></span>Edit</a>        
      </div>
      <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 devsunset-comment-metadata text-end">
        <a><span class="devsunset-icon devsunset-delete"></span>Delete</a>
      </div>
    </div><!--row -->
  </div> <!-- devsunset-comment-entry devsunset-comment-begin-->
  HDSTR;

  echo $endCommentsFormat;
}

/******* Helper function *********/
function devsunset_get_different_comment_time( $comment_id = 0 ): string
{
  return sprintf(
    _x( '%s ago', 'Human-readable time', 'text-domain' ),
    human_time_diff(
      get_comment_date( 'U', $comment_id ),
      current_time( 'timestamp' )
    )
  );
}

