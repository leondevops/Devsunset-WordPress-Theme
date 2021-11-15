<h1>Devsunset Contact Form</h1>

<?php settings_errors(); ?>

<p>Use the <strong>shortcode</strong> below to activate the Contact Form inside a page/post editor</p>

<code>[devsunset_contact_form]</code>

<form method="post" action="options.php" class="devsunset-contact-form">
  <!-- 1. Specify setting groups. This will include a hidden form -->
  <!-- This function will generate reference to custom admin page -->
  <?php
  /**Use the 1st argument in function:
   * devsunset-contact-options
   * // 1. Register settings
   * register_setting('devsunset-contact-options', 'contact_activate');
   **/
    settings_fields('devsunset-contact-options');
  ?>

  <!-- 2. Implement the settings for a specific page - using slugs-->
  <?php
  /*** Use the last argument of this function:
   * add_settings_section('devsunset-contact-section', 'Contact Form', 'devsunset_contact_section', 'custom_devsunset_theme_contact');
   **/
    do_settings_sections('custom_devsunset_theme_contact');
  ?>

  <!-- 3. Create a submit button -->
  <?php submit_button(); ?>
</form>
