<?php


class Devsunset_Menu_Walker extends Walker_Nav_Menu {

  private bool $has_schemas = false;

  public function __construct(){

    // $this->$has_schemas = false;
  }

  public function add_schemas_to_navbar_ul($args){
    $wrap = $args['items_wrap'];

    /*
     * - Check if $args['items_wrap'] does not have 'SiteNavigationElement'
     * + Replace the certain patter with value: ' itemscope itemtype="http://www.schema.org/SiteNavigationElement"$0'
     * ***/
    if (strpos($wrap, 'SiteNavigationElement') === false) {
      $args['items_wrap'] = preg_replace('/(>).*>?\%3\$s/', ' itemscope itemtype="http://www.schema.org/SiteNavigationElement"$0', $wrap);
    }

    return $args;
  }
}