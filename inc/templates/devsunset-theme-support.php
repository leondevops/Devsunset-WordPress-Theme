<h1>DevSunset General theme options</h1>

<!-- Display if there is any error after submitting the form. -->
<?php settings_errors(); ?>


<!-- 1. Informational area -->
<div class="devsunset-outer-container">

  <?php
    // $profilePicture = esc_attr( get_option('profile_picture') );

  ?>



  <!-- action = optiosn.php - submit data to options.php -->
  <form method="post" action="options.php" class="devsunset-general-form">
    <!-- 1. Specify setting groups. This will include a hidden form -->
    <!-- This function will generate reference to custom admin page -->
    <?php
      //settings_fields('devsunset-settings-group');
      settings_fields('devsunset-theme-support');
    ?>

    <!-- 2. Implement the settings for a specific page - using slugs-->
    <?php
      //do_settings_sections('custom_devsunset');
      do_settings_sections('custom_devsunset_theme');
    ?>

    <!-- 3. Create a submit button -->
    <?php submit_button(); ?>
  </form>

</div>


<p>=== End of DevSunset General theme options ===</p>


