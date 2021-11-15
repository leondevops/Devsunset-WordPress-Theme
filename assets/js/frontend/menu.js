
// noinspection DuplicatedCode

jQuery(document).ready(function($) {
  /**=================================================**/
  /** Menu section
   * - Need to apply root selector of main nav menu for all sub-items
   * - Declare all menu elements that have sub-menus **/
  /**=================================================**/
  var rootMenuSelector = '.devsunset-main-nav-menu ul.nav.navbar-nav';
  var menu_item_2 = rootMenuSelector.concat(' li.custom-menu-item-2');
  var menu_item_2_1 = menu_item_2.concat(' ul.dropdown-menu li.custom-menu-item-2-1');
  var menu_item_2_2 = menu_item_2.concat(' ul.dropdown-menu li.custom-menu-item-2-2');

  var menu_item_3 = rootMenuSelector.concat(' li.menu-item.custom-menu-item-3');
  var menu_item_3_1 = menu_item_3.concat(' ul.dropdown-menu li.custom-menu-item-3-1');
  var menu_item_3_2 = menu_item_3.concat(' ul.dropdown-menu li.custom-menu-item-3-2');

  /*** Hover effect for lvl 0 - 2nd item - Main Menu ***/
  $(menu_item_2).hover(
    function() {
      // $(this).children().css("display","block"); // Apply css OK. But leave hover hide all ???
      $(menu_item_2 + ' > ul')
        .addClass('show')
        .css({
          "position": "absolute",
          "top": "100%",
          "min-width": 0

        });
      $(menu_item_2 + ' > a').addClass('show' );

    }, function(){
      $(menu_item_2 + ' > ul').removeClass('show');
      $(menu_item_2 + ' > a').removeClass('show');
    }
  );

  /*** Hover effect for lvl 2 - 1st item - Main Menu ***/
  $(menu_item_2_1).hover(
    function() {
      // $(this).children().css("display","block"); // Apply css OK. But leave hover hide all ???
      $(menu_item_2_1 + ' > ul')
        .addClass('show')
        .css({
          "position": "absolute",
          "left": "100%",
          "top": "0"

        });
      $(menu_item_2_1 + ' > a').addClass('show' );

    }, function(){
      $(menu_item_2_1 + ' > ul').removeClass('show');
      $(menu_item_2_1 + ' > a').removeClass('show');
    }
  );

  /*** Hover effect for lvl 2 - 2nd item - Main Menu ***/


  $(menu_item_2_2).hover(
    function() {
      // $(this).children().css("display","block"); // Apply css OK. But leave hover hide all ???
      $(menu_item_2_2 + ' > ul')
        .addClass('show')
        .css({
          "position": "absolute",
          "left": "100%",
          "top": "0"

        });
      $(menu_item_2_2 + ' > a').addClass('show' );

    }, function(){
      $(menu_item_2_2 + ' > ul').removeClass('show');
      $(menu_item_2_2 + ' > a').removeClass('show');
    }
  );

  /*** Hover effect for lvl 1 - 3rd item - Main Menu ***/


  $(menu_item_3).hover(
    function() {
      // $(this).children().css("display","block"); // Apply css OK. But leave hover hide all ???
      $(menu_item_3 + ' > ul')
        .addClass('show')
        .css({
          "position": "absolute",
          "top": "100%",
          "min-width" : "0"

        });
      $(menu_item_3 + ' > a').addClass('show' );

    }, function(){
      $(menu_item_3 + ' > ul').removeClass('show');
      $(menu_item_3 + ' > a').removeClass('show');
    }
  );

  let menuItem2_listWidth = [$(menu_item_2).width(), $(menu_item_2_1).width(), $(menu_item_2_2).width()];
  let menuItem2_maxWidth = Math.max.apply(Math, menuItem2_listWidth);
  let menuItem2_finalWidth = ( menuItem2_maxWidth + 8 ) + 'px';  // Add additional 8 px

  $(menu_item_2).css('width', menuItem2_finalWidth);
  $(menu_item_2).children('a').css('width', menuItem2_finalWidth);
  $(menu_item_2).children('ul').css('width', menuItem2_finalWidth);

  $(menu_item_2_1).css('width', menuItem2_finalWidth);
  $(menu_item_2_1).children('a').css('width', menuItem2_maxWidth);
  $(menu_item_2_2).css('width', menuItem2_finalWidth);
  $(menu_item_2_2).children('a').css('width', menuItem2_maxWidth);

  /** Working - only trigger event when clicking to parent HTML element **/
  $(menu_item_2).click(function(e){
    window.open($(menu_item_2 + ' > a').attr('href'),'_self');
  });

  $(menu_item_2 + ' > ul').click(function(e){
    e.stopPropagation();
  });

// Menu item 2-1.
  $(menu_item_2_1).click(function(){
    window.open($(menu_item_2_1 + ' > a').attr('href'),'_self');
  });

  $(menu_item_2_1 + ' > ul').click(function(e){
    e.stopPropagation();
  });

// Menu item 2-2.
  $(menu_item_2_2).click(function(){
    window.open($(menu_item_2_2 + ' > a').attr('href'),'_self');
  });

  $(menu_item_2_2 + ' > ul').click(function(e){
    e.stopPropagation();
  });



  /** 2. Recalculate width of menu item 3 **/
  let menuItem3_listWidth = [$(menu_item_3).width(), $(menu_item_3_1).width(), $(menu_item_3_2).width()];
  let menuItem3_maxWidth = Math.max.apply(Math, menuItem3_listWidth);
  let menuItem3_finalWidth = ( menuItem3_maxWidth + 8 ) + 'px';  // Add additional 4 px

  $(menu_item_3).css('width', menuItem3_finalWidth);
  $(menu_item_3).children('a').css('width', menuItem3_finalWidth);
  $(menu_item_3).children('ul').css('width', menuItem3_finalWidth);

  $(menu_item_3_1).css('width', menuItem2_finalWidth);
  $(menu_item_3_1).children('a').css('width', menuItem3_maxWidth);
  $(menu_item_3_2).css('width', menuItem2_finalWidth);
  $(menu_item_3_2).children('a').css('width', menuItem3_maxWidth);

  $(menu_item_3).click(function(){
    window.open($(menu_item_3 + ' > a').attr('href'),'_self');
  });

  $(menu_item_3 + ' > ul').click(function(e){
    e.stopPropagation();
  });

  /** Open menu has children **/

  /* 1st approach - OK */

 /* var itemsHasChildren = document.getElementsByClassName('menu-item-has-children');
  for(var i = 0; i < itemsHasChildren.length; i++){
    // console.log('Menu item has children : ' + JSON.stringify(itemsHasChildren[i]));
    //console.log('Menu item has children : ' + itemsHasChildren[i]);
    $(itemsHasChildren[i]).on('click', function(e){
      console.log('Clicked on the item has children');
      $(this).children('ul').css({
        "position": "relative",
        "transform" : "none"
      });
    });
  }// end for itemsHasChildren*/

  /* 2nd approach - OK. Need to assign direct CSS */
 $('li.menu-item-lvl-0.menu-item-has-children').each(function(e){
    // console.log('current element is : ' + );
    let itemHasChildrenLvl0 = this;

    $(itemHasChildrenLvl0).on('click', function(){
      //console.log('Clicking to the item ' + JSON.stringify(this));
      $(this).children('ul').css({
        "position": "relative",
        "transform" : "none"
      });
    });//$(itemHasChildrenLvl0).on('click', function()

  });// End of $('li.menu-item-lvl-0.menu-item-has-children').each


  // Not work.
  /*$('li.menu-item-lvl-0.menu-item-has-children').each(function(e){
    // console.log('current element is : ' + );
    let itemHasChildrenLvl0 = this;

    $(itemHasChildrenLvl0).on('click', function(){
      //console.log('Clicking to the item ' + JSON.stringify(this));
      $(this).children('ul').toggleClass('JS-open-submenu-lvl-0');
    });//$(itemHasChildrenLvl0).on('click', function()

  });// End of $('li.menu-item-lvl-0.menu-item-has-children').each*/

  $('li.menu-item-lvl-1.menu-item-has-children').each(function(e){
    // console.log('current element is : ' + );
    //e.preventDefault();


    let itemHasChildrenLvl1 = this;

    $(itemHasChildrenLvl1).on('click', function(e){
      //e.preventDefault();
      e.stopPropagation();

      let subMenuStatus = $(this).children('span.dropdown-toggle').attr('submenu-open-status');
      /**
       * 1. Toggle open / close submenu status :
       * - Status: 0 - close; 1 - open
       * - Open submenu if close **/
      if( subMenuStatus === '0' ){
        $(this).children('span.dropdown-toggle').attr('submenu-open-status', '1');
        $(this).children('ul').css({
          "position": "relative",
          "display" : "block"
        });
      } else {
        $(this).children('span.dropdown-toggle').attr('submenu-open-status', '0');
        $(this).children('ul').css({
          "position": "relative",
          "display" : "none"
        });
      } // end if toggle

     /* $(this).children('ul').css({
        "position": "relative",
        "display" : "block"
      }); // OK */
    });//$(itemHasChildrenLvl0).on('click', function()

  });// End of $('li.menu-item-lvl-0.menu-item-has-children').each
  //$('li.menu-item-has-children').each(function({}));

}); //jQuery(document).ready(function($){
