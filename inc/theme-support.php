<?php /** @noinspection ALL */

/**

@package DevSunsettheme

=====================================
ADD CUSTOM ITEM TO ADMIN PAGE
=====================================
 **/

// define('DEFAULT_POST_IMAGE', 'http://vnlabwin.local.info/wp-content/uploads/2021/10/default-post-featured_image.jpg');


// 1. Manually activate post format options:
$postFormatsOptions = get_option('post_formats');

$formats = array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat');

$postFormatsDisplay = array();

foreach ($formats as $format) {
  $postFormatsDisplay[] = (isset($postFormatsOptions[$format]) && $postFormatsOptions[$format] == 1) ? $format : '';
}

if ( !empty($postFormatsOptions) ) {
  // Activate the post format :
  add_theme_support('post-formats', $postFormatsDisplay);
}

// 2. Manually activate custom header:
$headerOption = get_option('custom_header');

if( isset($headerOption) && $headerOption == 1 ) {
  add_theme_support('custom-header');
}

// 3. Manually activate custom background:
$backgroundOption = get_option('custom_background');

if( isset($backgroundOption) && $backgroundOption == 1 ) {
  add_theme_support('custom-background');
}

// 4. Post thumbnail
add_theme_support('post-thumbnails');

// 5. Custom navigation menu
function devsunet_register_nav_menu(){
  register_nav_menu('devsunset_primary_menu', 'Devsunset Header Navigation Menu');
}
add_action('after_setup_theme', 'devsunet_register_nav_menu');

/* 6. HTML 5 features */
add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption') );

/* 7. Comments reply scripts - allow reply */
add_action('comment_form_before', 'devsunset_enqueue_comment_reply');
function devsunset_enqueue_comment_reply(){
  if( get_option( 'thread_comments' ) )  {
    wp_enqueue_script( 'comment-reply' );
  }
}

/* 8. Sidebar */
// 8.1 Enable widgets editor - edit sidebar (widget)
add_action('after_setup_theme', 'devtheme_enable_widget');
function devtheme_enable_widget(){
  add_theme_support('widgets');
}

// 8.2 Manually register devsunset sidebar
add_action('widgets_init', 'devtheme_setup_sidebar');
function devtheme_setup_sidebar(){

  register_sidebar(
    array(
      'name'          => esc_html__('Devsunset Sidebar', 'Devsunsettheme'),
      'id'            => 'devsunset-sidebar',
      'description'   => 'Devsunset Dynamic Right Sidebar',
      'before_widget' => '<section id="%1$s" class="devsunset-widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="devsunset-widget-title">',
      'after_title'   => '</h2>',
    )
  );
}

/**
 * ================================
 * Blog loop custon functions
 * - Template: home.php, index.php
 * ================================
 */

/** Resgister default dashicons **/
// Register the default built-in WordPress dashicon
// wp_enqueue_style( 'dashicons' );  // OK

/** 1. Display the post meta data : published time & categories **/
function devsunset_posted_meta(){
  // Display the time - how long the post has been published
  // $posted_on = human_time_diff( get_the_time('U') , current_time('U', 7));  // Working
  $posted_on = human_time_diff( get_the_time('U') , current_time('timestamp')); // Working
  $postCategories = get_the_category();
  $categorySeparator = ', ';
  $output = '';
  $indexLoop = 1;

  // Display the list of categories.
  if(!empty($postCategories)):
    foreach($postCategories as $category):
      // If at least one loop, add separator
      if( $indexLoop > 1):
        $output .= $categorySeparator;
      endif;

      $output .= sprintf('<a href="%s" alt="%s">%s</a>',
                    esc_url( get_category_link($category->term_id) ),
                    esc_attr( 'View all posts in '.$category->name ),
                    esc_html($category->name)
                  );

      $indexLoop++;
    endforeach;
  endif;

  $resultFormat = <<<HEREDOCSTR
  <span class="posted-on">Posted <a href="%s">{$posted_on}</a> ago / </span>
  <span class="posted-in"> %s</span>
  HEREDOCSTR;

  return sprintf($resultFormat , esc_url(get_permalink()), $output );
}

/** 2. Display the post meta data : tags & comment links at footer **/
function devsunset_posted_footer(){
  // return 'Tags & comment links';
  $commentsNumber = get_comments_number();

  if (comments_open()):
    if($commentsNumber == 0):
      $comments = __('No comments');
    elseif ($commentsNumber > 1 ):
      $comments = $commentsNumber.__(' Comments');
    else:
      $comments = __('1 Comment');
    endif;

    // Using custom font defined in SCSS files:
    $comments = sprintf(
      '<a class="comments-link" href="%s">%s<span class="devsunset-icon devsunset-comment"></span></a>',
        get_comments_link(), $comments);
  else:
    $comments = __('Comments are closed !');
  endif;

  /** Using the custom defined dashicons
   * - Tags icon: devsunset-icon devsunset-tag
   **/
  $tagList = get_the_tag_list(
            '<div class="tags-list"><span class="devsunset-icon devsunset-tag"></span>',
            '  ', '</div>');

  // Return just the empty tag list if there is no tag for the post
  $tagList = $tagList ? $tagList : <<<HEREDOCSTR
    <div class="tags-list">
      <span class="devsunset-icon devsunset-tag">      
      </span>
    </div> 
  HEREDOCSTR;

  return <<<HEREDOCSTR
  <div class="post-footer-container">
    <div class="row">
      <div class="col-xs-8 col-md-8 col-sm-8">        
        {$tagList}
      </div>
      <!-- equivalent text-right in ealier bootstrap-->
      <div class="col-xs-4 col-md-4 col-sm-4 text-end">      
        {$comments}
      </div>
    </div> <!--row-->
  </div> <!--post-footer-container-->
  HEREDOCSTR;
}

/* 1. Get generic attachment objects
 * - At first just images
 * */
function devsunset_post_get_generic_attachments(int $quantity = 1){
  $result = array();

  //include_once(get_template_directory().'/troubleshoot/custom-theme-debugger.php');
  //$debugger = new Custom_Theme_Debugger();

  if ( has_post_thumbnail() && (1 == $quantity) ):
    $thumbnailUrl = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

    $attachedImgs = devsunset_post_get_all_attached_images();

    // Attach the featured image to the post if not attached in advance.
    if ( !in_array($thumbnailUrl,$attachedImgs )):
      devsunset_attach_thumbnail_if_not_attached($thumbnailUrl, get_the_ID() );
    endif;
  endif;

  if ( has_post_thumbnail()):
    // Need to obtain custom image as WP_Post object
    $thumbnailUrl = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

    $attachedImgs = devsunset_post_get_all_attached_images();

    // Attach the featured image to the post if not attached in advance.
    if ( !in_array($thumbnailUrl,$attachedImgs )):
      devsunset_attach_thumbnail_if_not_attached($thumbnailUrl, get_the_ID() );
    endif;
    // Attach to post if not

  endif;

  $attachments = get_posts( array(
    'post_type'       =>  'attachment',
    'posts_per_page'  =>  $quantity,
    'post_parent'     =>  get_the_ID()
  ) );

  if( $attachments ):
    foreach ($attachments as $attachedItem):
      $result[] = $attachedItem; // Obtaining all attached item objects
      //$result[] = wp_get_attachment_url($attachedItem->ID);
    endforeach;
  endif;

  // Reset the post loop
  wp_reset_postdata();

  return $result;
}

/* 2. Get images attachment
 * - At first just images
 * */
function devsunset_post_get_attachments(int $quantity = 1){
  $result = array();

  if ( has_post_thumbnail() && (1 == $quantity) ):
    $result[] = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
  else:
    $attachments = get_posts( array(
      'post_type'       =>  'attachment',
      'posts_per_page'  =>  $quantity,
      'post_parent'     =>  get_the_ID()
    ) );

    if( $attachments ):
      foreach ($attachments as $attachedItem):
        // $result[] = $attachedItem; // Obtaining all attached item objects
        $result[] = wp_get_attachment_url($attachedItem->ID);
      endforeach;
    endif;

    // Reset the post loop

    wp_reset_postdata();
  endif;

  return $result;
}

/**
 * - Attach the post thumbnails (featured images) if not attached
 **/
function devsunset_attach_thumbnail_if_not_attached($fileName, $postID){
  $filetype = wp_check_filetype( basename( $fileName ), null );

  // Get the path to the upload directory.
  $wp_upload_dir = wp_upload_dir();

  $attachmentArgs = array(
    'guid'           => $wp_upload_dir['url'] . '/' . basename( $fileName ),
    'post_mime_type' => $filetype['type'],
    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $fileName ) ),
    'post_content'   => '',
    'post_status'    => 'inherit'
  );

  // Insert the attachment.
  $attach_id = wp_insert_attachment( $attachmentArgs, $fileName, $postID );

  // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
  require_once( ABSPATH . 'wp-admin/includes/image.php' );

// Generate the metadata for the attachment, and update the database record.
  $attach_data = wp_generate_attachment_metadata( $attach_id, $fileName );
  wp_update_attachment_metadata( $attach_id, $attach_data );

  // set_post_thumbnail( $postID, $attach_id );
}

/* Get the array of attached images of the current post
 *  1. Featured images - temporary exclude
 *  2. Attached images
 * */
function devsunset_post_get_all_attached_images(){

  $attachedImgs = array();

  // Add to attached list if has post thumbnail
 /* if( has_post_thumbnail() ):
    $attachedImgs[] = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
  endif;*/

  $attachedImgsObjs = get_attached_media('image', get_the_ID());
  // Add to attached list all images that has been
  if (!empty($attachedImgsObjs)):
    foreach ($attachedImgsObjs as $item){
      // echo '<p>$attachedImgs :'.var_dump($item->guid).' </p>';
      // $attachedImgs[] = $item->guid;    // OK. Below is equivalent way but better
      $attachedImgs[] = wp_get_attachment_url( $item->ID );
    }
  endif;

  return $attachedImgs;
}

/* 1. Get all images inserted by page builders/editors */
function devsunset_post_get_all_content_images(){
  global $post;
  // Return the number of full matches (can be 0)
  $matchedStatus = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  // $first_img = $matches[1][0];

  //Defines a default image if no image exist in the post
  $result = array();
  if( !($matchedStatus > 0) || empty($matches[1]) ){
    //$featuredImage = 'http://vnlabwin.local.info/wp-content/uploads/2021/10/default-post-featured_image.jpg';
    return $result;
  } else {
    // $featuredImage = $matches[1][0];
    foreach ($matches[1] as $img){
      $result[] = $img;
    }
  }

  return $result;
}

/* get the 1st image in the post and display as featured image.
 * - Filtering directly the content generated from page editor
 * - If not, assign the default image
 * https://wordpress.stackexchange.com/questions/60245/get-the-first-image-from-post-content-eg-hotlinked-images
 * - Get the image from content built from page editor: $post->post_content
 * - the_content() will modify the $post->content and make it become actual content display to users.
 * */
function devsunset_post_get_first_image(){
  $featuredImage = '';
  /** @noinspection PhpIncludeInspection */
  //include_once(get_template_directory().'/troubleshoot/custom-theme-debugger.php'); // Include debug OK
  //$debugger = new Custom_Theme_Debugger();

  // Obtain the featured images of the current post if exists:
  if(has_post_thumbnail()):
    $featuredImage = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
  else:
    /* get the 1st image in the post and display as featured image.
       * - Filtering directly the content generated from page editor
       * - If not, assign the default image */
    global $post;
    // Return the number of full matches (can be 0)
    $matchedStatus = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    // $first_img = $matches[1][0];

    //Defines a default image if no image exist in the post
    if( !($matchedStatus > 0) || empty($matches[1][0]) ){
      $featuredImage = 'http://vnlabwin.local.info/wp-content/uploads/2021/10/default-post-featured_image.jpg';
    } else {
      $featuredImage = $matches[1][0];
    }
  endif;

  return $featuredImage;
}


function devsunset_post_get_first_embedded_media( $mediaTypes = array()){
  global $post;
  // Assign default value for $mediaTypes
  $mediaTypes = empty($mediaType) ? $mediaType = array('audio', 'iframe') : $mediaType;
  /**
   * 1. Search content for shortcodes, filter shortcodes (for new data) through their hooks
   * - "$post->content" is the content generated from page builder/editor (Gutenberg, Classic Editor)
   * - "apply_filters" ~ somehow equivalent to calling 'the_content()'
   * - "$audioContent" stores the actual HTML data that displayed for clients browsers by this below function:       *
   **/

  // global $post->post_content ~ get_the_content() ~ the content produced by page builder/editor
  $audioContent = do_shortcode( apply_filters('the_content', get_the_content()) );

  // Get the widget that contains audio content data (contain iframe)
  $audioEmbeddedData = get_media_embedded_in_content($audioContent, $mediaTypes);
  // echo $audioContent;

  if ( in_array('audio', $mediaTypes) ):
    $result = str_replace('?visual=true','?visual=false', $audioEmbeddedData[0]);
  else:
    $result = $audioEmbeddedData[0];
  endif;
  return $result;
}

function devsunset_post_gallery_get_bs_carousel_slide($attachedImgs){

  $maxIndex = count($attachedImgs) - 1; // max index = total item - 1

  $bsSlideResult = array();

  for($currentIndex = 0; $currentIndex <= $maxIndex; $currentIndex++):
    //1. Current image
    $mainImg = wp_get_attachment_url($attachedImgs[$currentIndex]->ID);
    //2. Next image
    $nextIndex = ( $currentIndex == $maxIndex ) ? 0 : ($currentIndex + 1);
    $nextImg = wp_get_attachment_thumb_url($attachedImgs[$nextIndex]->ID);
    //3. Previous image
    $previousIndex = ( $currentIndex == 0 ) ? $maxIndex : ($currentIndex - 1);
    $previousImg = wp_get_attachment_thumb_url($attachedImgs[$previousIndex]->ID);

    $activeClass = ($currentIndex == 0) ? ' active' : '';

    $bsSlideResult[$currentIndex] = array(
      'index'           =>  $currentIndex,
      'activeClass'     =>  $activeClass,
      'mainImg'         =>  $mainImg,
      'nextImg'         =>  $nextImg,
      'prevImg'         =>  $previousImg,
      'caption'         =>  $attachedImgs[$currentIndex]->post_excerpt,
    );

    /*
     * Output result will be used to produce below HTML structure of carousel slide:
     * $bsSlide = <<<HDSTR
    <div class="carousel-item{$activeClass} index-{$currentIndex} background-image" 
          style="background-image:url({$mainImg});" data-bs-interval="4000">   
      <div class="hide previous-image-preview" data-image="{$previousImg}"></div>  
      <div class="hide next-image-preview" data-image="{$nextImg}"></div>         
      <div class="entry-excerpt image-caption text-center">
        <p>{$attachedImgs[$currentIndex]->post_excerpt}</p>
      </div>      
    </div>                    
    HDSTR;
    */

  endfor;

  return $bsSlideResult;
}

function devsunset_post_get_first_link(){
  $matchedStatus = preg_match('/a\s[^>]*?href=[\'"](.+?)[\'"]/i', get_the_content() , $matchedResult);
  // Return the google search if not match any URL
  return $matchedStatus ? esc_url_raw($matchedResult[1]) : 'https://google.com.vn';
}

function devsunset_post_get_all_links(){
  /** @noinspection PhpIncludeInspection */
  //include_once(get_template_directory().'/troubleshoot/custom-theme-debugger.php'); // Include debug OK
  //$debugger = new Custom_Theme_Debugger();

  $matchedStatus = preg_match_all('/a\s[^>]*?href=[\'"](.+?)[\'"]/i',
                      get_the_content() , $matchedResult);

//  $debugger->write_log_detail_general($matchedStatus);
//  $debugger->write_log_detail_general($matchedResult);
//  unset($debugger);

  return $matchedStatus ? $matchedResult[1] : array();
}


/**
 * ====================================================
 * Single post functions
 * ====================================================
 **/

function devsunset_get_custom_post_navigation(){
  $prevPostFormat = <<<HDSTR
  <div class="post-link-nav">
    <span class="devsunset-icon devsunset-chevron-left" aria-hidden="true">    
    </span>
    %link
  </div>
  HDSTR;
  $prevPost = get_previous_post_link($prevPostFormat, '%title');

  $nextPostFormat = <<<HDSTR
  <div class="post-link-nav">
    %link
    <span class="devsunset-icon devsunset-chevron-right" aria-hidden="true">    
    </span>
  </div>
  HDSTR;
  $nextPost = get_next_post_link($nextPostFormat, '%title');

  return <<<HDSTR
  <div class="row devsunset-custom-post-navigation">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    {$prevPost}
    </div><!-- col-xs-6 -->
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    {$nextPost}
    </div><!-- col-xs-6 -->
  </div><!-- row -->
  HDSTR;

}

/** Create the custom social media share after the_content
 * - This function will add custom social media share after the content generated by page builder)
 * - input argument ($content) is the result of get_the_content()
 * ( before applying to filter the_content)
**/
add_filter('the_content', 'devsunset_custom_social_media_share');
function devsunset_custom_social_media_share( $content = null ){
  if ( is_single() ){
    $postTitle = get_the_title();
    $postLink = get_permalink();

    // Social Media handler
    $twitterHandler = get_option('twitter_handler') ? '&amp;via='.esc_attr( get_option('twitter_handler') ) : '';
    $facebookHandler = get_option('facebook_handler') ? '&amp;via='.esc_attr( get_option('facebook_handler') ) : '';
    $gplusHandler = get_option('gplus_handler') ? '&amp;via='.esc_attr( get_option('gplus_handler') ) : '';

    $twitterShareUrl = sprintf('https://twitter.com/intent/tweet?text=%s&amp;url=%s%s',
        'This is twitter custom text', $postTitle, $postLink, $twitterHandler);
    $facebookShareUrl = sprintf('https://facebook.com/sharer/sharer.php?u=%s', $postLink);
    $gplusShareUrl = sprintf('https://plus.google.com/share?url=%s',$postLink);

    return $content.<<<HDSTR
    <div class="devsunset-share-this text-center">
      <h4>Share this</h4>
      <ul>
        <li>
          <a href="{$twitterShareUrl}" target="_blank" rel="nofollow">
            <span class="devsunset-icon devsunset-twitter"></span>
          </a>
        </li>
        <li>
          <a href="{$facebookShareUrl}" target="_blank" rel="nofollow">
            <span class="devsunset-icon devsunset-facebook"></span>
          </a>
        </li>
        <li>
          <a href="{$gplusShareUrl}" target="_blank" rel="nofollow">
            <span class="devsunset-icon devsunset-google-plus"></span>
          </a>
        </li>
      </ul>
    </div><!--devsunset-share-this -->    
    HDSTR;

  }

  return $content;
}
