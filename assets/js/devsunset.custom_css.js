
// Specify the unique Id of editor
var editor = ace.edit("customCss");

editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/css");

jQuery(document).ready(function($){
  /** UpdatedCSS function will do:
   * 1. Update the value from submit button to the hidden form '#devsunset_css':
   * 2. Update all previous values of text editor.
   * **/
  var updatedCSS = function(){
    // Assign value from hidden textarea in custom CSS Editor
    // 1. Update the value from submit button to the hidden form '#devsunset_css':
    $('#devsunset_css').val( editor.getSession().getValue() );

    // 2. Update all previous values of text editor.
    // - This part is missing from the tutorial ?
    $('div.ace_layer.ace_text-layer .ace_line').val( editor.getSession().getValue() );

    /** Is there any additional value need to be retained on editor ? **/
  }

  // Update the hidden text area when users click 'saved'/submitted
  /*
  * - The save button belong to form
  * - When clicking to 'save' button, this function will update CSS in:
  * + textarea with ID '#devsunset_css'
  * + All rows in code editor
  * - All updated CSS are sent to the hidden text field "devsunset_css", then saved to the database.
  * */
  $("#save-ace-custom-css-form").submit( updatedCSS );
});

