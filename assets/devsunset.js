// noinspection DuplicatedCode,ES6ConvertVarToLetConst

jQuery(document).ready(function($){
  /* Generic declaration */
  var themeRootDir = '/wp-content/themes/devsunset';
  /**=================================================**/

  /**=================================================**/

  /** 1. Menu section **/
  /*** Hover effect for lvl 1 - 2nd item - Main Menu ***/
  var menuScriptPath = themeRootDir.concat('/assets/js/frontend/menu.js');
  var menuScript = document.createElement("script");
  menuScript.src = menuScriptPath;  // OK
  document.head.appendChild(menuScript);

  /** 2. Contact form section**/
  var contactFormScriptPath = themeRootDir.concat('/assets/js/frontend/contactform.js');
  var contactFormScript = document.createElement("script");
  contactFormScript.src = contactFormScriptPath;
  document.head.appendChild(contactFormScript);

  /**=================================================**/
  /*** Sidebar section ***/
  /**=================================================**/
  // Toggle open & close sidebar:

  $(document).on('click','.js-toggleSidebar', function(){
    $('.devsunset-sidebar').toggleClass('sidebar-closed');

    /** 1. Toggle the div sidebar overlay
     * - This is to prevent users from click anything when work with sidebar
     * + The sidebar must be closed by default to make this work.
     * + Toggle the display:none of the sidbar
     * **/
    $('.sidebar-overlay').fadeToggle(350);

    /* 2. Toggle class no-scroll to prevent user from scroll when displaying overlay */
    $('body').toggleClass('no-scroll');
  });



  /**=========================================================================**/
  /*** Javascript/jQuery to handle animation effects for Gallery Post format ***/
  /**=========================================================================**/

  /** === Reveal the beginning posts loaded === **/
  reveal_posts();

  /** Preload the detail components in the web content
   *  - Shortcode elements**/
  preload_generic_content_components();

  /** === Initialize the carousel of post gallery format if exist === **/
  devsunset_init_post_gallery_carousel();
  /** === Single posts effect === */

  /** 1. tooltip **/

  // Trigger all tooltip manually
  // $('[data-bs-toggle="tooltip"]').tooltip();
  // Store every time check & update container.
  var lastScroll = 0;

  /*******************Helper functions for the post gallery ********************/
  function devsunset_init_post_gallery_carousel(){
    // 1. Find next event when normal
    var postGalleryList = devsunset_get_post_gallery_list_classname();

    /* 2. jQuery apply all functions for gallery post formats */
    for (var i = 0; i < postGalleryList.length; i++){
      let currentSlide = postGalleryList[i];

      devsunset_get_current_thumbs(currentSlide);

      $(currentSlide).on('slide.bs.carousel', function(){
        devsunset_get_bs_thumbs_when_sliding(currentSlide);
      });
    }
  }

  function devsunset_get_post_gallery_list_classname(){
    let genericPostGalleryClassname = '.devsunset-carousel-thumb';

    let postGallerylist = [];

    $(genericPostGalleryClassname).each(function(){
      let currentCarousel = $(this).attr('class');
      let classNames = currentCarousel.split(" ");
      let lastClassname = '.' + classNames[classNames.length - 1 ];
      //console.log('Current item class names : ' + classNames); // OK
      //console.log('Last class name : ' + lastClassnames);
      postGallerylist.push(lastClassname);
    });

    return postGallerylist;
  }

  function devsunset_get_bs_thumbs_when_sliding(carouselPost){
    var activeCarouselSelector = carouselPost.concat(
      '.devsunset-carousel-thumb',
      ' div.carousel-inner',
      ' div.carousel-item.active'
    );

    var allCarouselSelector = carouselPost.concat(
      '.devsunset-carousel-thumb',
      ' div.carousel-inner',
      ' div.carousel-item'
    );

    var totalItems = $(allCarouselSelector).length;

    var maxIndex = totalItems - 1;
    var currentIndex = $(activeCarouselSelector).index();
    var prevIndex = (currentIndex === 0) ? maxIndex : currentIndex - 1;
    var nextIndex = (currentIndex === maxIndex) ? 0 : currentIndex + 1;

    var prevCarouselSelector = allCarouselSelector.concat('.index-' + prevIndex);
    var nextCarouselSelector = allCarouselSelector.concat('.index-' + nextIndex);

    // Wrong value
    var prevImg = $(prevCarouselSelector).find('.previous-image-preview').data('image');
    var nextImg = $(nextCarouselSelector).find('.next-image-preview').data('image');

    var leftControlSelector = carouselPost + '.devsunset-carousel-thumb' + ' a.left.carousel-control-prev';
    var rightControlSelector = carouselPost + '.devsunset-carousel-thumb' + ' a.right.carousel-control-next';


    // Set images OK
    $(leftControlSelector).find('.thumbnail-container').css({
      'background-image':'url('+prevImg+')'
    });
    $(rightControlSelector).find('.thumbnail-container').css({
      'background-image':'url('+nextImg+')'
    });
  } // devsunset_get_bs_thumbs_when_sliding

  /* === These features are working ===
  * But not update when sliding to the next slide*/
  function devsunset_get_current_thumbs(carouselPost){
    var activeCarouselSelector = carouselPost.concat(
      '.devsunset-carousel-thumb',
      ' div.carousel-inner',
      ' div.carousel-item.active'
    );

    var leftControlSelector = carouselPost + '.devsunset-carousel-thumb' + ' a.left.carousel-control-prev';
    var rightControlSelector = carouselPost + '.devsunset-carousel-thumb' + ' a.right.carousel-control-next';

    $(leftControlSelector).hover(
      function(){
        var prevImg = $(activeCarouselSelector).find('.previous-image-preview').data('image');

        $(leftControlSelector).find('.thumbnail-container').css({
          'background-image':'url('+prevImg+')'
        });
      }
    );

    $(rightControlSelector).hover(
      function(){
        var nextImg = $(activeCarouselSelector).find('.next-image-preview').data('image');

        $(rightControlSelector).find('.thumbnail-container').css({
          'background-image':'url('+nextImg+')'
        });
      }
    );
  }// devsunset_get_current_thumbs

  /**=========================================================================**/
  /*** AJAX - Load more post ***/
  /**=========================================================================**/

  /** 1. Callback function for "load more next posts" button :
   * 1.1. Load next posts
   * 1.2. Load previous posts/
   * - At some points, it is logic to combine in the same callback functions
   * **/
  $(document).on('click','.devsunset-load-more-next-btn:not(.loading)', function(){
    // Declare this variable to call in another scope
    var that = $(this); // this can be change depend on various scope

    // Next page parameters
    var queryNextPage = $(this).data('next-page');
    var archivePage = $(this).data('archive');
    var newNextPage = queryNextPage + 1;
    var ajaxUrl = $(this).data('url');

    // Hide the "Load More" button
    $(this).addClass('loading').find('.text').slideUp(350);
    // Rotate the "Load more" button icons.
    $(this).find('.devsunset-icon').addClass('spin');

    // console.log('archived page is ' + archivePage);
    // console.log('Dumped object : ' + JSON.stringify(that));
    //console.log('ajaxUrl : ' + ajaxUrl);
    if(typeof archivePage === 'undefined'){
      archivePage = 0;
    }

    /** 1. AJAX data to submit to wp-admins/ajax-admin.php
     * - Submit with HTTP post.
     * + The Form Data (parameters) specified in "data" field  **/
    $.ajax({
      url : ajaxUrl,
      type: 'post',
      data : {
        queryNextPage : queryNextPage,
        archivePage : archivePage,
        action : 'devsunset_load_more_next'
      },
      error : function( response ){
        console.log('Error occurs with response : ' + JSON.stringify(response));
      },
      success : function( response ){
        //console.log('response : ' + JSON.stringify(response));
        //console.log('response type : ' + typeof response);


        if( response === "" ){
          let noMorePosts = `
          <div class="text-center no-more-next-notification">
            <h3>No more next post</h3>
            <p>There is no more next post to be loaded. </p>
          </div>
          `;
          $('.devsunset-posts-container').append( noMorePosts );

          //that.removeClass('loading').find('.text');
          that.find('.devsunset-icon').removeClass('spin');
          $('.container.container-load-next').css('display:none;');
          that.slideUp(350);  //Hide the load more button behind the "no more post notification" div
        } else {
          // Wait for 1.5 seconds to implement all actions
          setTimeout(function(){
            // Store the new "next page" value
            that.data('next-page', newNextPage);
            // Update the new response
            $('.devsunset-posts-container').append( response );

            // Show the "Load more" button when complete
            that.removeClass('loading').find('.text').slideDown(350);
            // Stop loading
            that.find('.devsunset-icon').removeClass('spin');

            // Apply transition effect to all new articles:
            // $('article').addClass('reveal');
            reveal_posts();

          }, 1000);
        }

      }
    });//. ajax statement

  }); // $(document).on('click','.devsunset-load-more-next-btn:not(.loading)', function(){


  /** 2. Callback function for "load more previous posts" button :
   * 1.1. Load next posts
   * 1.2. Load previous posts/
   * - At some points, it is logic to combine in the same callback functions
   * **/
  $(document).on('click','.devsunset-load-more-prev-btn:not(.loading)', function(){
    // Declare this variable to call in another scope
    var that = $(this); // this can be change depend on various scope

    var ajaxUrl = $(this).data('url');

    // Previous pages parameters
    var queryPrevPage = that.data('prev-page');
    var archivePage = $(this).data('archive');  // archive page
    var newPrevPage;

    // Check & qualify the query previous page index
    if ( (typeof queryPrevPage == 'undefined') || (queryPrevPage === 0) ){
      queryPrevPage = 0;
      newPrevPage = 0;
    } else {
      newPrevPage = queryPrevPage - 1;
    }

    // Check & qualify the archive page value:
    if (typeof archivePage === 'undefined'){
      archivePage = 0;
    }

    // Hide the "Load More" button
    $(this).addClass('loading').find('.text').slideUp(350);
    // Rotate the "Load more" button icons.
    $(this).find('.devsunset-icon').addClass('spin');

    // console.log('Dumped object : ' + JSON.stringify(that));
    //console.log('ajaxUrl : ' + ajaxUrl);

    /** 1. AJAX data to submit to wp-admins/ajax-admin.php
     * - Submit with HTTP post.
     * + The Form Data (parameters) specified in "data" field  **/
    $.ajax({
      url : ajaxUrl,
      type: 'post',
      data : {
        queryPrevPage : queryPrevPage,
        archivePage : archivePage,
        action : 'devsunset_load_more_previous'
      },
      error : function( response ){
        console.log('Error occurs with response : ' + JSON.stringify(response));
      },
      success : function( response ){

        // Check if there is no more posts loaded
        if ( queryPrevPage === 0 ){
          let noMorePosts = `
          <div class="text-center no-more-prev-notification">
            <h3>No more previous post</h3>
            <p>There is no more previous post to be loaded. </p>
          </div>
          `;
          $('.devsunset-posts-container').prepend( noMorePosts );

          //that.removeClass('loading').find('.text');
          that.find('.devsunset-icon').removeClass('spin');
          $('.container.container-load-previous').css('display:none;');
          //$('span.devsunset-loading').css('display:none;');
          that.slideUp(350);  //Hide the load more button behind the "no more post notification" div

        } else{
          // Wait for 1.5 seconds to implement all actions
          // console.log('Response : ' + JSON.stringify(response));
          setTimeout(function(){
            // Store the new "next page" value
            that.data('prev-page', newPrevPage);
            // Update the new response
            $('.devsunset-posts-container').prepend( response );

            // Show the "Load more" button when complete
            that.removeClass('loading').find('.text').slideDown(350);
            // Stop loading
            that.find('.devsunset-icon').removeClass('spin');

            // Apply transition effect to all new articles:
            // $('article').addClass('reveal');
            reveal_posts();

          }, 1000);
        }
      } //success callback
    });//.ajax({...})

  }); // $(document).on('click','.devsunset-load-more-btn:not(.loading)', function(){
  /****************************************/
  /** === Helper functions for AJAX === **/
  /****************************************/
  // 1. Display all posts that not have class "reveal"
  // Every 2 millisecond, call each post item
  function reveal_posts(){
    // Select all DOM elements that not contain "reveal" class
    var posts = $('article:not(.reveal)');

    var i = 0;
    /***
     * 1. Reveal posts
     * 2. Update JS for carousel */
    setInterval(function(){
      // if index is greater than number of post, return false
      if( i >= posts.length)  {
        return false;
      }

      var postItem = posts[i];  // ~el
      /*
      * 1. Add class to reveal new post item
      * 2. Trigger Bootstrap carousel once more time*/
      $(postItem).addClass('reveal').find('.devsunset-carousel-thumb').carousel();
      // Re-initialize the carousel if exists
      devsunset_init_post_gallery_carousel();

      i++;
    }, 200);


  } // reveal_posts


  /** === 2. Scroll function === **/
  /*
  * Execute the function when the Windows scrolls
  * */
  $(window).scroll(function(){
    // The current pixel away from top browsers. This var is dynamically updated
    var scrollFromTop = $(window).scrollTop();
    //console.log('scrollFromTop : ' + JSON.stringify(scrollFromTop));

    /* Check the scrollFromTop - lastScroll > 10% * WindowHeight
    * 1.1. If true:
    * - Update last scroll . */
    if( Math.abs(scrollFromTop - lastScroll) > $(window).height()*0.1 ){
      lastScroll = scrollFromTop;

      // Check each container of ".page-limit"
      $('.page-limit').each(function(index){

        var that = $(this);

        if( isVisible( $(this) )){
          /**
           * Append the suffix :"/page/<page_number> to the current url: http://url.domain
           * **/
          //console.log('Called isVisible success !! ');
          //console.log('Curent data-page : ' + that.attr('data-page'));

          history.replaceState(null, null, that.attr('data-page'));
          return false;
        }
      });
    }

  }); // $(window).scroll(function(){

  /* Helper function */
  /** Check if the post is inside the viewport of the users.
   * - the post is not at the top, or bottom. **/
  function isVisible(element){
    // Pos ~ position
    var scrollPos = $(window).scrollTop();
    var windowHeight = $(window).height();

    var elementPosTop = $(element).offset().top;
    var elementHeight = $(element).height();
    var elementPosBottom = elementPosTop + elementHeight;

    return ( elementPosBottom - elementHeight*0.25 > scrollPos) && ( elementPosTop < (scrollPos + 0.5*windowHeight) );
  }

  /** Function to initialize content components **/
  function preload_generic_content_components(){
    // 1. Trigger all tooltip manually
    $('[data-bs-toggle="tooltip"]').tooltip();

    // 2. Trigger the popover
    $('[data-bs-toggle="popover"]').popover();
  }

}); //jQuery(document).ready(function($){



