<?php

/**
 * Header template
 * @package Devsunsettheme
 **/

// Return null if the sidebar is not activated
if ( !is_active_sidebar('devsunset-sidebar')) {
  return ;
}
?>

<aside id="secondary-sidebar" class="widget-area" role="complementary">

  <!-- Sidebar nav menu is shown only in xs(OK).
  - try to apply hide only with xs, sm (show on screen smaller than md)
  - Bootstrap 5 class is: d-block d-sm-block d-md-none
  These will not be display at main nav menu -->
  <div class="nav-container devsunset-sidebar-menu d-block d-sm-block d-md-none">
    <?php
    include_once('inc/menu/devsunset-nav-menu-walker.php');
    /** Need to implement separate walker class for sidebar mobile:
     *  - Open sub-menu by down-arrow menu
     *  - While open a page item links using the existed hyperlink
     *  => This requires complete different logic from the original walker...
     */
    include_once('inc/menu/devsunset-sidebar-menu-walker.php');

    // Use as tutorial
    wp_nav_menu(
      array(
        'theme_location'        => 'devsunset_primary_menu',
        'container'             =>  false,
        'menu_class'            => 'nav navbar-nav navbar-collapse ms-auto mb-0',
        'depth'                 =>  3,
        'walker'                =>  new Devsunset_Sidebar_Menu_Walker()
      )
    );
    ?>

  </div>


  <?php dynamic_sidebar('devsunset-sidebar');?>
</aside>