<?php


use JetBrains\PhpStorm\Pure;

class Devsunset_Widget_Popular_Posts extends WP_Widget {

  /* 1. Constructors */
  public function __construct(){

    $widget_ops =  array(
      'classname'       => 'devsunset-widget-popular-posts',
      'description'     => 'Devsunset Widget Popular Posts',
    );

    parent::__construct('devsunset_popular_posts', 'Devsunset Popular Posts', $widget_ops);
    $this->alt_option_name = 'devsunset_widget_popular_posts';

    // Load the inline CSS for this custom Widget
    /*if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
      add_action( 'wp_head', array( $this, 'devsunset_popular_posts' ) );
    }*/
  }

  public function widget($args, $instance){


    $tot = absint( $instance['tot'] );
    /* Prepare query post arguments
     * - The meta key is in the file custom-widgets.php
     * */
    $postsArgs = array(
      'post_type'       => 'post',
      'post_per_page'   => $tot,
      'meta_key'        => 'devsunset_post_views',
      'orderby'         => 'meta_value_num',
      'order'           => 'DESC'
    );

    $postsQuery = new WP_Query( $postsArgs );

    $output = '';

    $output .= $args['before_widget'];

    if( !empty( $instance['title'] ) ):
      // $output .= $args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title'];
      $output .= sprintf('%s%s%s',
        $args['before_title'], apply_filters('widget_title', $instance['title']), $args['after_title']
      );
    endif;

    if( $postsQuery->have_posts() ):
      //$output .= '<ul>';

      while( $postsQuery->have_posts() ): $postsQuery->the_post();
        /* Ultilize the Bootstrap 5 layout */
        $outputFormat = <<<HDSTR
        <div class="media">
          <div class="media-content">
            <div class="media-left">
              <img class="media-object" src="%s" alt="%s"/>
            </div><!--media-left-->
            <div class="media-body justify-content-center">            
                <a href="%s">%s</a>                     
            </div><!--media-body-->          
          </div><!--media-content-->   
           <div class="media-meta">
             <div class="meta-share float-start">
              <span class="devsunset-share-white"></span><p> %s Views</p>
             </div><!--media-meta-->
            <div class="meta-comments float-start">
              <span class="devsunset-comment-white"></span><p> %s</p>
            </div><!--meta-comment-->            
             
           </div><!--media-meta-->
        </div><!--media-->       
        HDSTR;

        $postFormat = get_post_format();
        $postFormat = $postFormat ? $postFormat : 'standard';

        $commentsNumber = get_comments_number();
        $comments = '';

        if($commentsNumber == 0):
          $comments = __('No comments');
        elseif ($commentsNumber > 1 ):
          $comments = $commentsNumber.__(' Comments');
        else:
          $comments = __('1 Comment');
        endif;

        $output .= sprintf(
          $outputFormat,
          get_template_directory_uri().'/assets/img/post-'.$postFormat.'.png', get_the_title(),
          get_permalink(), get_the_title(),
          get_post_meta(get_the_ID(), 'devsunset_post_views', true), $comments
        );

        /*$output .= sprintf('<li><a href="%s" target="_blank">%s</a></li>',
          get_permalink(), get_the_title()
        );  //OK*/
      endwhile;

      //$output .= '</ul>';
    endif;

    $output .= $args['after_widget'];

    echo $output;
  }

  /* 2. Backend display of widget */
  public function form( $instance ){
    /* 1. The title of the widget */
    $title = ( !empty( $instance['title'] )  ? $instance['title'] : '' );

    /* 2. Total posts that will be displayed
    * - Default is 4 posts */
    $tot = ( !empty($instance['tot'])  ? absint( $instance['tot'] ) : 4 );

    /**
    * widefat is the default WordPress class */
    $output = '';
    $outputFormat = <<<HDSTR
    <p>
      <label for="%s">Title : </label>
      <input type="text" class="widefat" id="%s" name="%s" value="%s">
    </p>
    HDSTR;

    $output .= sprintf(
      $outputFormat, esc_attr( $this->get_field_name('title') ),
      esc_attr( $this->get_field_id('title') ), esc_attr( $this->get_field_name('title') ), esc_attr( $title )
    );

    // Update $outputFormat
    $outputFormat = <<<HDSTR
    <p>
      <label for="%s">Number of posts : </label>
      <input type="number" class="widefat" id="%s" name="%s" value="%s">
    </p>
    HDSTR;

    $output .= sprintf(
      $outputFormat, esc_attr( $this->get_field_name('tot') ),
      esc_attr( $this->get_field_id('tot') ), esc_attr( $this->get_field_name('tot') ), esc_attr( $tot )
    );

    echo $output;
  }

  /* 3. Frontend display of widget */

  /* 4. Method to update values defined by users
   * - This $new_instance parsing to this method is null ???
   * + Data flow of updating custom Widget WordPress
   *    * */

  public function update($new_instance, $old_instance) {
    $instance = array();

    $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '' ;
    $instance['tot'] = ( !empty( $new_instance['tot'] ) ) ? absint( strip_tags( $new_instance['tot'] ) ) : 0 ;

    return $instance;
  }



}

/* Register the custom widget using WordPress hooks */
add_action('widgets_init', 'register_devsunset_widget_popular_posts');
function register_devsunset_widget_popular_posts(){
  register_widget('Devsunset_Widget_Popular_Posts');
}