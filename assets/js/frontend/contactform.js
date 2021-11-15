jQuery(document).ready(function($) {

  /* 1. Contact form submissions */
  $('#devsunsetContactForm').on('submit', function(e){
    // Prevent default behaviors
    e.preventDefault();

    // Specify selector to indicate current form.
    var currentForm = $(this);  // this -> #devsunsetContactForm

    // Get the value of input fields - OK
    /*    var name = currentForm.find('#name').val();
    var email = currentForm.find('#email').val();
    var message = currentForm.find('#message').val();*/

    // Shorthand to declare multiple values:
    var name = currentForm.find('#name').val(),
        email = currentForm.find('#email').val(),
        message = currentForm.find('#message').val();

    /*
    * - By default,  WordPress already has mechanism to validate empty fields
    * */
    if( name === '' || email === '' || message === ''){
      return;
    }

    var ajaxUrl = currentForm.data('url');

    /** 1. AJAX data to submit to wp-admins/ajax-admin.php
     * - Submit with HTTP post.
     * - There are 5 main arguments parsing to ajax function:
     * + Destination AJAX URL
     * + HTTP type
     * + AJAX data information: name, email, message, and "ajax action at backend"
     * + Error case - error callback function
     * + Success case - success callback function
     * - The Form Data (parameters) specified in "data" field
     ***/
    $.ajax({
      url: ajaxUrl,
      type: 'post',
      data: {
        name : name,
        email : email,
        message : message,
        action : 'devsunset_save_user_contact_form'
      },
      error : function( response ){
        currentForm.find('.in-progress-response').css('display','block');

        /* Prevent users from click/edit the form which has been successfuly submitted */
        currentForm.find('input, textarea').attr('disabled','true');
        // Change status of submit button
        currentForm.find('.button-form-submission').attr('disabled','true');
        //currentForm.find('.button-form-submission').removeClass('btn-success');
        currentForm.find('.button-form-submission').addClass('btn-secondary');

        setTimeout(function(){
          //console.log('Error occur with response : ' + JSON.stringify(response));
          //currentForm.find('.in-progress-response').removeClass('form-control-message');
          currentForm.find('.failure-response').addClass('failure-status is-invalid');
          currentForm.find('.failure-response').css('display','block');

          currentForm.find('.in-progress-response').css('display','none');
        }, 1500);

      },
      success : function ( response ) {
        // If no response, display error message
        if( response === 0 ){
          currentForm.find('.in-progress-response').css('display','block');
          /* Prevent users from click/edit the form which has been successfuly submitted */
          currentForm.find('input, textarea').attr('disabled','true');

          currentForm.find('.button-form-submission').attr('disabled','true');
          //currentForm.find('.button-form-submission').removeClass('btn-success');
          currentForm.find('.button-form-submission').addClass('btn-secondary');

          setTimeout(function(){
            //console.log(' Cannot save your message. No response obtained from the server.');
            currentForm.find('.failure-response').addClass('failure-status is-invalid');
            currentForm.find('.failure-response').css('display','block');

            currentForm.find('.in-progress-response').css('display','none');
          }, 1500);
        }else{
          currentForm.find('.in-progress-response').css('display','block');
          /* Prevent users from click/edit the form which has been successfuly submitted */
          // Disable all inputted text field
          currentForm.find('input, textarea').attr('disabled','true');

          // Disable the submit button
          currentForm.find('.button-form-submission').attr('disabled','true');
          //currentForm.find('.button-form-submission').removeClass('btn-success');
          currentForm.find('.button-form-submission').addClass('btn-secondary');

          /* Proceed success - response status */
          setTimeout(function(){
            currentForm.find('.success-response').addClass('success-status is-valid');
            currentForm.find('.success-response').css('display','block');

            // console.log('Success. The response value is : ' + JSON.stringify(response));
            currentForm.find('.in-progress-response').css('display','none');

          }, 1500);

        }

        /*currentForm.find('#name').addClass('is-valid');
        currentForm.find('#email').addClass('is-valid');
        currentForm.find('#message').addClass('is-valid');
        console.log('Success. The response value is : ' + JSON.stringify(response));*/
      }
    });//.ajax({...})


  }); // $('#devsunsetContactForm').on('submit', function(e){

}); //jQuery(document).ready(function($){