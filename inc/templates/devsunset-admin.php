<h1>DevSunset General theme options</h1>

<!-- Display if there is any error after submitting the form. -->
<?php settings_errors(); ?>


<!-- 1. Informational area -->
<div class="devsunset-outer-container">

<?php
  $profilePicture = esc_attr( get_option('profile_picture') );

  $firstName = esc_attr( get_option('first_name') );
  $lastName = esc_attr(get_option('last_name'));
  $fullName = $firstName.' '.$lastName;
  $description = esc_attr( get_option('user_description') );

  $twitter_icon_val = esc_attr( get_option( 'twitter_handler' ) );
  $facebook_icon_val = esc_attr( get_option( 'facebook_handler' ) );
  $gplus_icon_val = esc_attr( get_option( 'gplus_handler' ) );
?>


<div class="devsunset-sidebar-preview">
  <div class="devsunset-sidebar">

    <div class="profile-image-container">
      <div id="profile-picture-preview" class="profile-picture"
           style="background-image:url(<?php printf("%s", $profilePicture); ?>)">
      </div>
    </div>

    <h3 class="devsunset-username"><?php printf("%s", $fullName); ?></h3>
    <h4 class="devsunset-description"><?php printf("%s", $description); ?></h4>
    <div class="icons-wrapper">
      <?php if( !empty($twitter_icon_val) ): ?>
        <span class="devsunset-icon-sidebar dashicons-before dashicons-twitter"></span>
      <?php endif;?>
      <?php if( !empty($gplus_icon_val) ): ?>
        <span class="devsunset-icon-sidebar dashicons-before dashicons-googleplus devsunset-icon-sidebar-gplus"></span>
      <?php endif;?>
      <?php if( !empty($facebook_icon_val) ): ?>
        <span class="devsunset-icon-sidebar dashicons-before dashicons-facebook-alt"></span>
      <?php endif;?>

    </div><!--profile-picture-preview-->

  </div><!--devsunset-sidebar-->
</div><!--devsunset-sidebar-preview-->


<!-- action = optiosn.php - submit data to options.php -->
<form method="post" action="options.php" class="devsunset-general-form">
	<!-- 1. Specify setting groups. This will include a hidden form -->
	<!-- This function will generate reference to custom admin page -->
	<?php settings_fields('devsunset-settings-group'); ?>

	<!-- 2. Implement the settings for a specific page - using slugs-->
	<?php do_settings_sections('custom_devsunset'); ?>

	<!-- 3. Create a submit button -->
	<?php
    //submit_button(); // OK but will cause conflict with other submission
    /**
    * Specify the name that is different from 'submit'
     **/
    submit_button('Save your changes', 'primary', 'customBtnSubmit');
  ?>

</form>

</div>


<p>=== End of DevSunset General theme options ===</p>


