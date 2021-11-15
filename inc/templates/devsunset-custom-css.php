<h1>Devsunset Custom CSS</h1>

<?php settings_errors(); ?>


<form id="save-ace-custom-css-form" method="post" action="options.php"
      class="devsunset-contact-form devsunset-custom-css-form">

  <?php
    /** 1. Specify setting groups. This will include a hidden form
     * - This function will generate reference to custom admin page
     * - Use the 1st argument in function register_setting :
     *    register_setting('devsunset-contact-options', 'contact_activate');
     **/
    settings_fields('devsunset-custom-css-options');
  ?>

  <?php
    /*
     * 2. Implement the settings for a specific page - using slugs
     * - Use the last argument of this function:
     *   add_settings_section('devsunset-custom-css-section','Custom CSS',
     *                 'devsunset_custom_css_section_callback', 'custom_devsunset_css' );
     **/
    do_settings_sections('custom_devsunset_css');
  ?>

  <?php
    /*
     * 3. Create a submit button
     * **/
    submit_button();
  ?>
</form>

