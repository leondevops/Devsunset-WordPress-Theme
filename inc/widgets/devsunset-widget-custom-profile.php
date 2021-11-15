<?php


class Devsunset_Widget_Custom_Profile extends WP_Widget{

  public function __construct(){
    $widget_ops = array(
      'classname'       => 'devsunset-widget-custom-profile',
      'description'     => 'Devsunset Widget Custom Profile',
    );

    parent::__construct('devsunset_custom_profile', 'Devsunset Custom Profile', $widget_ops);
  }

  /** 1. Front-end display of widget
   *- Display what users see at front-end
   *+ The sidebar typically
   * 1. $args: arguments of widget that will be declared
   * - This is argument parsing to the function "register_sidebar(...)"
   * 2. $instance: carry all the options to that specific single widgets
   * - Depend on how many widgets we use
   * - Each widget is a unique instance.
   * - A user can use multiple widgets (~ multiple widget instance) at a time.
   ***/
  public function widget( $args, $instance ){
    /* one argument widget_id = devsunset_profile-2 ??? */
    $profilePicture = esc_attr( get_option('profile_picture') );

    $firstName = esc_attr( get_option('first_name') );
    $lastName = esc_attr(get_option('last_name'));
    $fullName = $firstName.' '.$lastName;
    $description = esc_attr( get_option('user_description') );

    $twitter_icon_val = esc_attr( get_option( 'twitter_handler' ) );
    $facebook_icon_val = esc_attr( get_option( 'facebook_handler' ) );
    $gplus_icon_val = esc_attr( get_option( 'gplus_handler' ) );

    $twitterOutput = '';
    if( !empty($twitter_icon_val) ){
      $twitterOutput = <<<HDSTR
      <a href="https://twitter.com/{$twitter_icon_val}" target="_blank">
        <span class="devsunset-icon-sidebar devsunset-twitter"></span>
      </a>
      HDSTR;
    }

    $gplusOutput = '';
    if( !empty($gplus_icon_val) ){
      $gplusOutput = <<<HDSTR
      <a href="https://plus.google.com/u/0/+{$gplus_icon_val}" target="_blank">
        <span class="devsunset-icon-sidebar devsunset-google-plus"></span>  
      </a>
      HDSTR;
    }

    $facebookOutput = '';
    if( !empty($facebook_icon_val) ){
      $facebookOutput = <<<HDSTR
      <a href="https://facebook.com/{$facebook_icon_val}" target="_blank">
        <span class="devsunset-icon-sidebar devsunset-facebook"></span>
      </a>
      HDSTR;
    }

    $output = <<<HDSTR
    {$args['before_widget']}
    <div class="text-center">
      <div class="profile-image-container">
        <div id="profile-picture-preview" class="profile-picture"
             style="background-image:url({$profilePicture})">
        </div>
      </div><!--profile-image-container-->  
      <h3 class="devsunset-username">{$fullName}</h3>
      <h4 class="devsunset-description">{$description}</h4>
      <div class="icons-wrapper">      
        {$twitterOutput}
        {$gplusOutput}
        {$facebookOutput}
      </div><!--icons-wrapper-->
    </div><!--text-center-->
    {$args['after_widget']}
    HDSTR;

    echo $output;
  }

  /** 2. Back-end display of widget
   * (Temporarily manage at admin setting pages)
   *  - Display at WordPress admin setting pages - when WP open widgets.php
   *  - WordPress handle widget in backend as a form.
   */
  public function form( $instance ){


  }
}

/* Register the custom widget using WordPress hooks */
add_action('widgets_init', 'register_devsunset_widget_custom_profile');
function register_devsunset_widget_custom_profile(){
  register_widget('Devsunset_Widget_Custom_Profile');
}