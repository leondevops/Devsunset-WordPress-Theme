<?php /** @noinspection ALL */

/**

@package DevSunsettheme

=====================================
ADD CUSTOM ITEM TO ADMIN PAGE
=====================================
 **/

/* OK
 * if( isset($backgroundOption) && $backgroundOption == 1 ) { // ~ if( @$activatedContact == 1 )
 * add_theme_support('custom-background');
}*/


// 1. Get custom post type activation status (to manage custom contact)
$activatedContactPostType = get_option('activate_contact');

// 2. Activate the custom post type if checkbox condition is activated.
if( @$activatedContactPostType == 1 ) {
  // 2.1. Register the custom post type
  add_action('init', 'devsunset_contact_custom_post_type');

  /**
   * 2.2. Customize the custom post type registered: "devsunset-contact"
   * (specified by function register_post_type('devsunset-contact', $customPostArgs);
   * - Register a filter to modify custom post type column:
   */
  add_filter('manage_devsunset-contact_posts_columns', 'devsunset_set_contact_columns');

  /**
   * 2.3. Add action to do with each custom column:
   * - 1st argument: custom action hook (for custom post type 'devsunset-contact) capability_type: posts
   * - 3rd argument: order of action added. Trigger this action after (1) action has been triggered.
   * - 4th argument: number of input arguments
   **/
  add_action('manage_devsunset-contact_posts_custom_column', 'devsunset_contact_custom_column', 10, 2);

  /*
   * 3. Add custom meta boxes to manage Contact Custom Post Type
   * - Add to the side of 'contact' CPT edit pages
   * */
  add_action('add_meta_boxes','devsunset_contact_add_meta_box');

  /* 4. Enable the Contact Custom Post Type to be updated value from meta box**/
  add_action('save_post','devsunset_save_contact_email_data');
}


// Register custom post types called 'message'
function devsunset_contact_custom_post_type() {
  $customPostLabels = array(
    'name'          => 'Messages',
    'singular_name' => 'Message',
    'menu_name'     => 'Messages',
    'name_admin_bar'=> 'Message'
  );
  /**
    * - Menu position 26 means under 'Comment' position
   **/
  $customPostArgs = array(
    'labels'          => $customPostLabels,
    'show_ui'         => true,
    'show_in_menu'    => true,
    'capability_type' => 'post',
    'hierarchical'    => false,
    'menu_position'   => 26,
    'menu_icon'       => 'dashicons-email-alt',
    'supports'        => array('title','editor','author')
  );

  register_post_type('devsunset-contact', $customPostArgs);
}

/**
 * 2. Function to re-format the 'Message' post type:  *
 */
function devsunset_set_contact_columns($columns){
  // 1. Unset the 'author' column registered in advance:
  unset ($columns['author']);

  // 2. Rename the original column 'title' to 'Full Name':
  $columns['title'] = 'Full Name';

  // 3. Add more custom columns on 'Message' post type management:
  $columns['message'] = 'Message';
  $columns['email'] = 'Email';
  $columns['date'] = 'Date';

  return $columns;
}

/**
 * 3. Function to add column list :
 * - Loop through all 'Message' custom posts, with given post_id,then do something with appropriate column:
 *  * + Message column: print the excerpt()
 * + Email: print the email
 * - This callback function will apply for all 'Message' custom post type.
 **/

function devsunset_contact_custom_column($column , $post_id){
  switch($column) {
    case 'message':
      echo get_the_excerpt();
      break;
    case 'email':
      $email = get_post_meta($post_id, '_contact_email_value_key', true);
      echo '<a href="mailto:'.$email.'">'.$email.'</a>';
      break;

  }
}

/**
 * ====================================
 * Contact Meta-boxes / "Message CPT" Meta boxes
 * ====================================*
 *
 * - 4th arg - $screen - obtain from register_post_type('devsunset-contact', $customPostArgs);
 */

function devsunset_contact_add_meta_box() {
  add_meta_box('contact_email','User Emails', 'devsunset_contact_email_callback',
                'devsunset-contact','side', 'default');
}

/*
* - 1st arg : WP_Post $post
* */
function devsunset_contact_email_callback($post){
  // Generate a unique string to check action: saving, deleting ...
  // - Prevent someone from hacking, do malicious activities to your system
  wp_nonce_field('devsunset_save_contact_email_data','devsunset_contact_email_meta_box');

  /*
   * - 1st arg : post ID
   * - 2nd arg : email key
   * */
  $value = get_post_meta($post->ID,  '_contact_email_value_key', true);
  $heredocArg = esc_attr($value);
/*
  echo '<label for="devsunset_contact_email_field">User Email Address</label>';
  echo '<input type="email" id="devsunset_contact_email_field" name="devsunset_contact_email_field"
                value="'.esc_attr($value).'" size="25"/>';
*/

  echo <<<HEREDOCSTR
  <label for="devsunset_contact_email_field">User Email Address</label>
  <input type="email" id="devsunset_contact_email_field" name="devsunset_contact_email_field"
         value="{$heredocArg}" size="25"/>
  HEREDOCSTR;

}


/** Validate the $_POST['devsunset_contact_email_meta_box'] */
function devsunset_save_contact_email_data( $post_id ){
  /** 1. Validate the meta box value **/
  /* 1.1. Check if the nonce is set?
   * If the nonce is not set, do nothing
   * (the nonce is a unique string generated when user do something with meta box: save, update ....)
   * */
  if ( !isset( $_POST['devsunset_contact_email_meta_box'] ) ){
    return;
  }

  /*
   * 1.2. Check if a valid nonce is generated
   * - If the nonce is invalid, do nothing
  */
  if( ! wp_verify_nonce($_POST['devsunset_contact_email_meta_box'], 'devsunset_save_contact_email_data') ) {
    return;
  }

  /* 1.3. Check if this is an auto-save or manual save.
   * Do not save the value of metabox if this is an anto-save action
   * - Check the global variable DOING_AUTOSAVE
   * */

  if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
    return;
  }

  /* 1.4. Check user permission
   * - If the user cannot edit this post (does not have permission), do nothing.
   * */
  if( ! current_user_can('edit_post') ){
    return;
  }

  /* 1.5. Check if not set the input field devsunset_contact_email_field
   * - If the user cannot edit this post (does not have permission), do nothing.
   * */
  if ( !isset( $_POST['devsunset_contact_email_field'] ) ){
    return;
  }

  /** 2. Obtain the value of the custom meta box*/
  $metabox_data = sanitize_text_field( $_POST['devsunset_contact_email_field'] );

  /** 3. Update the 'Message' Custom Post Type with value filled from the custom meta box
   * - 2nd arg is meta_key - obtained from:
   *   $value = get_post_meta($post->ID,  '_contact_email_value_key', true);
   */
  update_post_meta( $post_id , '_contact_email_value_key', $metabox_data);
}

