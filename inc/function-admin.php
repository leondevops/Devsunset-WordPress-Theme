<?php /** @noinspection ALL */

/**

@package DevSunsettheme

=====================================
	ADD CUSTOM ITEM TO ADMIN PAGE
=====================================
 **/

// I. Main function of custom Admin page
add_action('admin_menu', 'devsunset_add_admin_page');
function devsunset_add_admin_page(){
	/*
	 * Referential order of WP admin page items:
	 *  https://developer.wordpress.org/reference/functions/add_menu_page/
	 * custom page slug: custom_devsunset
	 * */
	// 1. General parent admin page: (1.1.)
	add_menu_page('DevSunset Theme Options',
								'DevSunset','manage_options',
								'custom_devsunset', 'devsunset_theme_create_page',
								get_template_directory_uri().'/assets/img/sunset-icon.png', 110);

	// 2. General sub-admin pages: (1.2)
	// It is recommended to repeat the same arguments for the 1st custom-submenu with parent custom-menu.
	// 2.1. General options. (1.2.1)
	add_submenu_page('custom_devsunset', 'DevSunset Sidebar Options',
										'Sidebar','manage_options',
										'custom_devsunset','devsunset_theme_general_page');

  // 2.2. Support page (1.2.2)
  add_submenu_page('custom_devsunset', 'DevSunset Theme Options',
    'Theme Options','manage_options',
    'custom_devsunset_theme','devsunset_theme_support_page');

  // 2.3. Contact Form Management Page (1.2.3)
  add_submenu_page('custom_devsunset', 'DevSunset Contact Options',
                    'Contact Form', 'manage_options',
                    'custom_devsunset_theme_contact', 'devsunset_contact_form_page');

	// 2.4. Custom CSS (1.2.4)
	add_submenu_page('custom_devsunset','DevSunset CSS Options',
										'Custom CSS', 'manage_options',
										'custom_devsunset_css','devsunset_theme_settings_page');



	// 3. Add custom hooks & custom actions
	add_action('admin_init', 'devsunset_custom_settings');

}

/**
 * ADD CUSTOM ITEM TO ADMIN PAGE - functions for devsunset_add_admin_page()
 **/

// 1. Main function - general page. This will provide HTML datas for custom admin pages
function devsunset_theme_create_page(){
  // Generate the body of custom admin page
  // Enable ignoring checking the relative path in this statement.
  /** @noinspection PhpIncludeInspection */
  require_once(get_template_directory().'/inc/templates/devsunset-admin.php');
}

// 2.1. General options - function (1.2.1)
function devsunset_theme_general_page(){
  echo '<p>=== start of DevSunset General settings Page ===</p>';
  echo '<p>=== end of DevSunset General settings Page ===</p>';
}

// 2.2. Support (1.2.3)
function devsunset_theme_support_page() {
  require_once(get_template_directory().'/inc/templates/devsunset-theme-support.php');
}

// 2.3. Support (1.2.3)
function devsunset_contact_form_page() {
  require_once(get_template_directory().'/inc/templates/devsunset-contact-form.php');
}

// 2.4. Custom CSS - function (1.2.4)
function devsunset_theme_settings_page(){
  require_once(get_template_directory().'/inc/templates/devsunset-custom-css.php');

  /*echo '<h2>DevSunset Custom CSS page</h2>';
  echo '<p>=== start of DevSunset Custom CSS page ===</p>';
  echo '<p>=== end of DevSunset Custom CSS page ===</p>';*/
}


// 3. custom action
/**
ADD CUSTOM ITEM TO ADMIN PAGE - functions for INNER devsunset_add_admin_page()
 * 1. Add custom sections & Fields for each custom section to the general pages
 *
 **/
function devsunset_custom_settings(){
  // I. Page "sidebar options"
	// 1. Page Sidebar Register custom settings
  // 1.1. Profile image
  register_setting('devsunset-settings-group', 'profile_picture');
  // 1.1. Full name : First Name + Last Name
	register_setting('devsunset-settings-group', 'first_name');
	register_setting('devsunset-settings-group', 'last_name');
  // User description
  register_setting('devsunset-settings-group', 'user_description');
	// 1.2. Twitter handler. 3rd argument is sanitization callback functions
  register_setting('devsunset-settings-group', 'twitter_handler',
                    'devsunset_sanitize_twitter_handler');
  // 1.3. Facebook handler
  register_setting('devsunset-settings-group', 'facebook_handler');
  // 1.4. Google+ handler
  register_setting('devsunset-settings-group', 'gplus_handler');

	// 2. Add a section to a registered setting
	// Need to create function devsunset_sidebar_options()
	add_settings_section('devsunset-sidebar-options', 'Devsunset Sidebar Option',
												'devsunset_sidebar_options', 'custom_devsunset');

	// 3. Add custom fields to sections above.
  // 3.1. Profile picture. The page argument uses the 'slug' of the available page
  add_settings_field('sidebar-profile-picture', 'Profile Picture','devsunset_sidebar_profile',
                    'custom_devsunset','devsunset-sidebar-options');

  // 3.1. Full name field - contains both first name & last name
	// Need to create function devsunset_sidebar_name()
  add_settings_field('sidebar-name', 'Full Name','devsunset_sidebar_name',
                     'custom_devsunset','devsunset-sidebar-options');
  // User descripiton
  add_settings_field('sidebar-description', 'Description','devsunset_sidebar_description',
    'custom_devsunset','devsunset-sidebar-options');
  // 3.2. Twitter field
  add_settings_field('sidebar-twitter', 'Twitter handler','devsunset_sidebar_twitter',
                      'custom_devsunset', 'devsunset-sidebar-options');
  // 3.3. Facebook field
  add_settings_field('sidebar-facebook', 'Facebook handler','devsunset_sidebar_facebook',
                    'custom_devsunset', 'devsunset-sidebar-options');
  // 3.4. Google plus field
  add_settings_field('sidebar-gplus', 'Google+ handler','devsunset_sidebar_gplus',
                    'custom_devsunset', 'devsunset-sidebar-options');

  // II. Page "Theme Support"
  // 1. Register setting
  register_setting('devsunset-theme-support', 'post_formats',
                  'devsunset_post_formats_callback'); // callback function may not be used
  register_setting('devsunset-theme-support', 'custom_header');
  register_setting('devsunset-theme-support', 'custom_background');

  // 2. Add sections to theme support page. Alecadd ~ custom_devsunset
  add_settings_section('devsunset-theme-options', 'Theme Option',
                      'devsunset_theme_options', 'custom_devsunset_theme');

  // 3. Add fields to the previous section
  add_settings_field('post-formats', 'Post Formats','devsunset_post_formats',
                          'custom_devsunset_theme', 'devsunset-theme-options');
  add_settings_field('custom-header', 'Custom Header','devsunset_custom_header',
                          'custom_devsunset_theme', 'devsunset-theme-options');
  add_settings_field('custom-background', 'Custom Background','devsunset_custom_background',
                          'custom_devsunset_theme', 'devsunset-theme-options');

  // III. Page Contact form options
  // 1. Register settings
  register_setting('devsunset-contact-options', 'activate_contact');

  // 2. Add sections
  add_settings_section('devsunset-contact-section', 'Contact Form',
                            'devsunset_contact_section', 'custom_devsunset_theme_contact');

  // 3. Add fields to the available sections
  add_settings_field('activate-form','Activate Contact Form', 'devsunset_activate_contact',
                        'custom_devsunset_theme_contact', 'devsunset-contact-section');

  // IV. Page Custom CSS
  // 1. Register settings
  // - Register additional sanitization text area - with its callback function.
  // - The callback function is valid.
  register_setting('devsunset-custom-css-options', 'devsunset_css','devsunset_sanitize_custom_css');

  // 2. Add sections
  add_settings_section('devsunset-custom-css-section','Custom CSS',
                      'devsunset_custom_css_section_callback', 'custom_devsunset_css' );

  // 3. Add fields to the available sections
  add_settings_field('custom-css','Insert your custom CSS','devsunset_custom_css_callback',
                        'custom_devsunset_css', 'devsunset-custom-css-section');

}

/** I. List of callback function for (I) - sidebar options pages **/
// Call back function of 2.
function devsunset_sidebar_options(){
  echo 'Customize your Sidebar Information';
	/*echo '<p>=== Start of sidebar options ===</p>';
	echo '<p>=== End of sidebar options ===</p>';*/
}

// Call back function of 3.
// Display a text field for the 'First Name'
/**
Helper methods for devsunset_custom_setting()

 **/

function devsunset_sidebar_profile(){
  $profilePicture = esc_attr(get_option('profile_picture'));

  if ( empty($profilePicture) ) {
    echo <<<HEREDOCSTR
    <button type="button" class="button button-secondary" 
            value="Upload Profile Picture" id="upload-profile-button" >
      <span class="devsunset-icon-button dashicons-before dashicons-format-image"></span>
            Upload Profile Picture
    </button>
    <input type="hidden" id="profile-picture" name="profile_picture" value="" />
    <p class="description"> Choose your profile picture here </p>
    HEREDOCSTR;
  } else {
    echo <<<HEREDOCSTR
    <button type="button" class="button button-secondary" 
            value="Replace Profile Picture" id="upload-profile-button" >
      <span class="devsunset-icon-button dashicons-before dashicons-format-image"></span>
            Replace Profile Picture
    </button>
    <input type="hidden" id="profile-picture" name="profile_picture" value="{$profilePicture}" />
    <button type="button" class="button button-secondary" 
            value="Remove Picture" id="remove-profile-button" >
      <span class="devsunset-icon-button dashicons-before dashicons-no"></span>
      Remove Picture
    </button>
    <p class="description"> Choose your profile picture here </p>
    HEREDOCSTR;
  }
}


/*** Generate attributes methods ***/
function devsunset_sidebar_name(){
	$firstName = esc_attr( get_option('first_name') );
	echo '<input type="text" name="first_name" value="'.$firstName.'" placeholder="First Name" />';

	$lastName = esc_attr(get_option('last_name'));
	echo '<input type="text" name="last_name" value="'.$lastName.'" placeholder="Last Name" />';
}

function devsunset_sidebar_description(){
  $description = esc_attr(get_option('user_description'));

  echo <<<HEREDOCSTR
  <input type="text" name="user_description" value="{$description}" placeholder="User Description" />
  <p class="description"> Write description here ! </p>
  HEREDOCSTR;

}


function devsunset_sidebar_twitter(){
  $twitter = esc_attr(get_option('twitter_handler'));

  echo <<<HEREDOCSTR
  <input type="text" name="twitter_handler" value="{$twitter}" placeholder="Twitter handler" />
  <p class="description"> Input your Twitter username without the \'@\' character</p>
  HEREDOCSTR;
}

function devsunset_sidebar_facebook(){
  $facebook = esc_attr(get_option('facebook_handler'));
  echo '<input type="text" name="facebook_handler" value="'.$facebook.'" placeholder="Facebook handler" />';
}

function devsunset_sidebar_gplus(){
  $gplus = esc_attr(get_option('gplus_handler'));
  echo '<input type="text" name="gplus_handler" value="'.$gplus.'" placeholder="Google+ handler" />';
}

/*** Sanitization methods ***/
/**
 * 1.3. Sanitization method for Twitter field
 * - Input argument should has generic type
 */
function devsunset_sanitize_twitter_handler($input){
  $output = sanitize_text_field($input);
  $output = str_replace('@','',$output);
  return $output;
}

/** I. List of callback function for (II) - theme support pages **/
function devsunset_post_formats_callback( $input ){
  // echo '<p> $input = '.var_dump($input).PHP_EOL.'</p>';
  return $input;
}

function devsunset_theme_options(){
  echo 'Activate and Deactivate specific Themes Support Options ';
}

function devsunset_post_formats(){
  $options = get_option('post_formats');

  $formats = array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat');

  $output = '';
  foreach ($formats as $format) {
    // post_formats
    //
    // $isChecked = (isset($options[$format]) && $options[$format] == 1) ? 'checked' : '';  // OK
    $isChecked = @$options[$format] == 1 ? 'checked' : ''; // OK - equivalent statement above.
    // Append heredoc string
    $output .= <<<HEREDOCSTR
      <label>
        <input type="checkbox" id="{$format}" name="post_formats[{$format}]" 
          value="1" {$isChecked} />{$format}</label><br>
    HEREDOCSTR;

  }

  echo $output;
}

function devsunset_custom_header(){
  $options = get_option('custom_header');

  $isChecked = (@$options == 1) ? 'checked' : '';
  echo '<label><input type="checkbox" id="custom_header" name="custom_header" value="1" '.$isChecked.' />Activate the Custom Header</label><br>';
}

function devsunset_custom_background(){
  $options = get_option('custom_background');

  $isChecked = (@$options == 1) ? 'checked' : '';

  echo <<<HEREDOCSTR
  <label>
    <input type="checkbox" id="custom_background" name="custom_background" 
      value="1" {$isChecked} />Activate the Custom background</label><br>
  HEREDOCSTR;

}


/** I. List of callback function for (III) - contact management pages **/


function devsunset_contact_section(){
  echo 'Activate and Deactivate the Built-in Contact Form ';
}

function devsunset_activate_contact(){
  $options = get_option('activate_contact');

  $isChecked = (@$options == 1) ? 'checked' : '';
  echo <<<HEREDOCSTR
  <label>
    <input type="checkbox" id="activate_contact" name="activate_contact" 
            value="1" {$isChecked} />Activate the Contact Form</label><br>
  HEREDOCSTR;
}

/** I. List of callback function for (IV) - Manage Custom CSS page **/

function devsunset_custom_css_section_callback(){
  echo 'Customize Devsunset theme with your own CSS';
}

function devsunset_custom_css_callback(){
  /* 1. Obtain value from input CSS text field
   * get option from function: register_setting('devsunset-custom-css-options', 'devsunset_css');*/
  $customCss = get_option('devsunset_css');

  $customCss = empty($customCss) ? '/* Devsunset theme Custom CSS */' : $customCss;
  echo <<<HEREDOCSTR
  <div id="customCss">{$customCss}</div>
  <textarea id="devsunset_css" name="devsunset_css" 
            style="display:none;visibility:hidden">{$customCss}</textarea>
  HEREDOCSTR;
}

/* Sanitization method for users' custom CSS
*  register_setting('devsunset-custom-css-options', 'devsunset_css','devsunset_sanitize_custom_css');
*/
function devsunset_sanitize_custom_css( $input ) {
  $output = esc_textarea($input);

  return $output;
}
