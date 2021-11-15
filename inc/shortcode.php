<?php

/**

@package DevSunsettheme

===========================================
SHORTCODE OPTIONS
===========================================

 **/


/**
 * Bootstrap 5 tooltip shortcode *
 * [bstooltip placement="<position>" title="<title_string>"] This is the tooltip's content [/bstooltip]
 */
add_shortcode('bstooltip','devsunset_simple_bs_tooltip');
function devsunset_simple_bs_tooltip( $atts, $content = null): string
{

  /* 1. Get the attributes from series of input arguments
  * - Parse arguments to shortcode
   *
   * * The arguments parse to shortcode_atts():
   * - Array contain parsing values
   * - Shortcode attributes obtain from this function's input arguments
   * - Shortcode name
   * */
  $atts = shortcode_atts(
    array(
      'placement' => 'top',
      'title'     => '',
    ),
    $atts,
    'bstooltip'
  );
  /* 2. Generate the dedicated HTML data for this short code
   * - Refer to Bootstrap 5.x documents for detail HTML data of tooltip :
   * https://getbootstrap.com/docs/5.0/components/tooltips/
   * */
  $dataFormat = <<<HDSTR
  <span class="devsunset-tooltip" 
    data-bs-toggle="tooltip" data-bs-placement="%s" title="%s">
    %s
  </span>
  HDSTR;

  $titleAtt = $atts['title'] == '' ? $content : $atts['title'];

  return sprintf($dataFormat, $atts['placement'], $titleAtt, $content );
}

/**
 * Bootstrap 5 popover shortcode *
 * [bspopover placement="<position>" title="<title_string>" message="<message_sentences>"] This is the tooltip's content [/bspopover]
 */
add_shortcode('bspopover', 'devsunset_simple_bs_popover');
function devsunset_simple_bs_popover( $atts, $content = null): string
{
  /* 1. Parsing the attributes to the shortcode */
  $atts = shortcode_atts(
    array(
      'placement' => 'top',
      'title'     => '',
      'message'   => '',
      'trigger'   => 'click'
    ),
    $atts,
    'bspopover'
  );

  /* 2. Render the HTML data
  * Sample value for popover
  * - Placement: top
  * - Popover Title: Popover title
  * - Popover Content: Top popover
  * - Button content: (often is the markup string)
  */
  $popoverTitle = $atts['title'] == '' ? 'Default title' : $atts['title'];
  $popoverMessage = $atts['message'] == '' ? 'This is the default popover message' : $atts['message'];

  $dataFormat = <<<HDSTR
  <span class="devsunset-popover" 
          data-bs-container="body" data-bs-toggle="popover" data-bs-trigge="%s"
          data-bs-placement="%s" title="%s" data-bs-content="%s">
  %s
  </span>
  HDSTR;


  return sprintf($dataFormat, $atts['trigger'], $atts['placement'], $popoverTitle, $popoverMessage, $content );
}

/* 3. Shortcode to activate contact form */
add_shortcode('devsunset_contact_form', 'devsunset_contact_form');
function devsunset_contact_form( $atts, $content = null ): string
{
  $atts = shortcode_atts(
    array(),
    $atts,
    'devsunset_contact_form'
  );

  ob_start();
  include 'templates/devsunset-contact-form-frontend.php';
  return ob_get_clean();
}
