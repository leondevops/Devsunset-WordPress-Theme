
jQuery(document).ready(function($) {

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


}); //jQuery(document).ready(function($){