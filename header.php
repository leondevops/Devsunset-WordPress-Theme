<?php
  /**
   * Header template
   * @package Devsunsettheme
   **/
?>

<!doctype html>



<html <?php language_attributes(); ?>>
	<head>
    <!-- Display title & bloginfo to optimize SEO -->
    <title><?php bloginfo('name'); wp_title();  ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
		<meta charset="<?php bloginfo('charset'); ?>">
    <!-- Display responsively in various device -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="profile" href="http://gmpg.org/xfn/11" >
    <?php
      // Check if the ping is opened for the queried object is true & the page is singular:
      if( is_singular() && pings_open( get_queried_object() ) ): ?>

      <link rel="pingback" href="<?php bloginfo('pingback_url') ?>" >
    <?php endif;?>

		<!-- Load the local CSS files -->
    <?php wp_head(); ?>

    <?php
      /* Apply the CSS specified in the Custom CSS setting page */
      $devsunset_CustomCSS = esc_attr( get_option('devsunset_css') );
      if ( !empty($devsunset_CustomCSS) ):
        echo '<style>'.$devsunset_CustomCSS.'</style>';
      endif;
    ?>

	</head>
	
	<body <?php body_class() ?> >
  <!-- Close the sidebar by default - using class: sidebar-closed-->
  <div class="devsunset-sidebar sidebar-closed">
    <div class="devsunset-sidebar-container">
      <!-- Button to close sidebar -->
      <a class="js-closeSidebar js-toggleSidebar sidebar-close">
        <span class="devsunset-icon devsunset-close"></span>
      </a>

      <div class="sidebar-scroll">
        <?php
        global $wp_registered_sidebars;
        //include_once(get_template_directory().'/sidebar.php');
        // echo '<p> $wp_registered_sidebars : '.var_dump($wp_registered_sidebars).'</p>';
        get_sidebar();
        ?>
      </div><!--sidebar-scroll-->
    </div> <!--devsunset-sidebar-container-->
  </div><!--devsunset-sidebar -->

  <div class="sidebar-overlay js-toggleSidebar"></div><!--.sidebar-overlay-->

  <div class="devsunset-open-sidebar-container float-end">
    <!-- Button to open sidebar -->
    <a class="js-openSidebar js-toggleSidebar sidebar-open">
      <span class="devsunset-icon devsunset-menu"></span>
    </a>
  </div><!--devsunset-open-sidebar-container-->

  <div class="devsunset-header container-fluid">
    <header class="devsunset-header-container background-image text-center"
            style="background-image:url(<?php header_image(); ?>)">

      <div class="header-content table">
        <div class="table-cell">
          <h1 class="site-title devsunset-icon">
            <span class="devsunset-logo"></span>
            <span class="hide" style="display:none;"><?php bloginfo('name');?></span>
          </h1>
          <h2 class="site-description"><?php bloginfo('description'); ?></h2>
        </div> <!--.table-cell-->
      </div> <!--.header-content-->

      <!-- Nav menu is hidden in xs (only XS OK)
       - apply for xs, sm - hide on screens smaller than md
       These will be showed in sidebar -->
      <div class="nav-container devsunset-header-navigation-menu d-none d-md-block">

        <nav class="navbar navbar-default navbar-devsunset devsunset-main-nav-menu">
          <?php
            include_once('inc/menu/devsunset-nav-menu-walker.php');

            // Use as tutorial
            wp_nav_menu(array(
                'theme_location'        => 'devsunset_primary_menu',
                'container'             =>  false,
                'menu_class'            => 'nav navbar-nav ms-auto mb-0',
                'depth'                 =>  3,
                'walker'                =>  new Devsunset_Nav_Menu_Walker()
              )
            );
          ?>

        </nav>

      </div> <!--.nav-container-->
    </header>  <!--.header-container-->
  </div> <!--.container-fluid-->

