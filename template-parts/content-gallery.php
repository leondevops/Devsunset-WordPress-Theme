<?php
/*
 *  @Package devsunsettheme
 *  --- Gallery post format - default post type
 * - This page can detect the functions defined in /inc
 * (perhaps PHP storm can detect this page is already included in the index.php
 * (or related PHP page template)).
* */

$isRevealedPost = get_query_var('isRevealedPost');
?>



<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(array('devsunset-format-gallery')); ?>>

  <!-- Featured images -->
  <?php
    //$featuredImage = devsunset_post_get_first_image(); // defined in theme_support.php

    // Adjust the number of slide by editting the number of attached images
    // Obtain all images attached to the gallery posts
    // $attachedImgs = devsunset_post_get_all_attached_images(); // OK
    // $postImages = devsunset_post_get_all_images(); // OK
    // Get generic item
    // $attachedItems = devsunset_post_get_attachments(10);  // OK. Only obtain attached files
    // echo '<p> Debug result :'.var_dump($attachedImgs).' </p>';
  ?>

  <header class="entry-header text-center background-image">
    <?php
      the_title('<h2 class="entry-title"><a href="'.esc_url(get_permalink()).'" rel="bookmark">',
                '</a></h2>');
    ?>

    <div class="entry-meta">
      <?php echo devsunset_posted_meta(); ?>
    </div> <!--.entry-meta-->


  </header>

  <div class="entry-content">
    <?php

    global $deviceDetect;

    //echo '<p> Device detect status : '.var_dump($deviceDetect).'</p>';

    //echo '<p> Is mobile status : '.var_dump($deviceDetect->isMobile()).'</p>';
    //echo '<p> Get detection type: : '.var_dump($deviceDetect->getDetectionType()).'</p>';

    /* 1.1. If detect mobile device, display standard post format : */
    if ( $deviceDetect->isMobile() ):
      // If there is still
      $featuredImage = devsunset_post_get_first_image();
      ?>
      <a class="standard-featured-link" href="<?php the_permalink(); ?>">
        <!-- Make the featured image responsive by making it fit the whole div-->
        <div class="standard-featured background-image"
             style="background-image: url(<?php echo $featuredImage;?>);">
        </div> <!--.standard-featured-->
      </a>

    <!-- -->
    <?php
    /* 1.2. If there is not a mobile device, display the full carousel : */
    else: ?>

    <div id="carousel-post-<?php the_ID()?>"
         class="carousel slide devsunset-carousel-thumb post-carousel-<?php the_ID();?>"
         data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
            $attachedImgs = devsunset_post_get_generic_attachments(10); // OK
            $bsCarouselSlides = devsunset_post_gallery_get_bs_carousel_slide($attachedImgs);

            foreach ($bsCarouselSlides as $bsSlide):
              echo <<<HDSTR
              <div class="carousel-item{$bsSlide['activeClass']} index-{$bsSlide['index']} background-image" 
                    style="background-image:url({$bsSlide['mainImg']});" data-bs-interval="4000">   
                <div class="hide previous-image-preview" data-image="{$bsSlide['prevImg']}"></div>  
                <div class="hide next-image-preview" data-image="{$bsSlide['nextImg']}"></div>         
                <div class="entry-excerpt image-caption text-center">
                  <p>{$bsSlide['caption']}</p>
                </div>      
              </div>                    
              HDSTR;
            endforeach;

          // Include debug
          /** @noinspection PhpIncludeInspection */
          //include_once(get_template_directory().'/troubleshoot/custom-theme-debugger.php'); // Include debug OK
          //$debugger = new Custom_Theme_Debugger();
          //$attachedImgs = devsunset_post_get_generic_attachments(10); // OK

          //$bsCarouselSlides = devsunset_post_gallery_get_bs_carousel_slide($attachedImgs);
          //$debugger->write_log_general($bsCarouselSlides);
          // unset($debugger);


        ?>
      </div> <!-- .carousel-inner-->

      <a class="left carousel-control-prev" href="#carousel-post-<?php echo get_the_ID()?>"
         role="button" data-bs-slide="prev">
        <div class="table">
          <div class="table-cell">
            <div class="preview-container">
              <span class="thumbnail-container background-image"></span>
              <span class="devsunset-icon devsunset-chevron-left" aria-hidden="true"></span>
              <span class="sr-only visually-hidden">Previous</span>
            </div> <!--.preview-container-->
          </div><!-- .table-cell -->
        </div><!-- .table -->
      </a>

      <a class="right carousel-control-next" href="#carousel-post-<?php echo get_the_ID()?>"
         role="button" data-bs-slide="next">
        <div class="table">
          <div class="table-cell">
            <div class="preview-container">
              <span class="thumbnail-container background-image"></span>
              <span class="devsunset-icon devsunset-chevron-right" aria-hidden="true"></span>
              <span class="sr-only visually-hidden">Next</span>
            </div> <!--.preview-container-->
          </div><!-- .table-cell -->
        </div><!-- .table -->
      </a>

    </div> <!-- carousel-post-<post_ID>-->


    <?php
      /* === End of checking if the client is using mobile device */
    endif; // End of checking if there is a mobile device
    // ?>

    <div class="entry-excerpt">
      <?php the_excerpt(); ?>
    </div> <!-- entry-excerpt -->

    <div class="button-container text-center">
      <a href="<?php the_permalink(); ?>" class="btn btn-devsunset"><?php _e('Read More');?></a>
    </div> <!--.button-contain-->
  </div> <!--.entry-content-->


  <footer class="entry-footer">
    <?php echo devsunset_posted_footer(); ?>

  </footer>
</article>

