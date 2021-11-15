
jQuery(document).ready(function($){

  var mediaUploader;    // represent the file obtained in Media Uploader
  /** 1. Specify "upload profile picture" feature for 'upload profile picture' button **/
  $('#upload-profile-button').on('click', function(e){
    e.preventDefault();

    if( mediaUploader){
      mediaUploader.open();
      return;
    }

    // assign value from the WP Media Uploader global variables
    /**
     * multiple: false - select only 1 file.
     * **/
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Custom Uploader - Choose Profile Picture',
      button: {
        text: 'Select Or Choose Picture'
      },
      multiple: false
    });

    // check if something has ben selected on upload profile button
    mediaUploader.on('select', function(){
      // 1. Convert to JSON array of information of a picture that users click to
      let attachment = mediaUploader.state().get('selection').first().toJSON();
      // 2. Assign URL to the input field
      $('#profile-picture').val(attachment.url);
      // 3. Styling the div #profile-picture-preview with background-image selected in Upload Profile Picture button
      $('#profile-picture-preview').css('background-image','url(' + attachment.url + ')');
    });

    mediaUploader.open();
/*
    /!**These code are working **!/
    mediaUploader.open();

    // check if something has ben selected on upload profile button
    mediaUploader.on('select', function(){
      // Convert to JSON array of information of a picture that users click to
      var uploadedImage = mediaUploader.state().get('selection').first();
      // get the Image URL
      var imageUrl = uploadedImage.toJSON().url;
      // Assign URL to the input field
      $('#profile-picture').val(imageUrl);
    });
*/

  });


  /** 2. Specify "remove profile picture" feature for 'upload profile picture' button **/
  $('#remove-profile-button').on('click', function(e){
    e.preventDefault();
    // Display pop-up alert to confirm the removal process of profile picture:
    let answer = confirm("Are you sure to remove your profile picture preview ?");

    if ( answer === true ) {
      // Empty the value of #profile-picture element
      $('#profile-picture').val('');
      // Submit the change of the current form. force the page to refresh
      $('.devsunset-general-form').submit();
    }
   /* if( answer === true ){
      console.log("Yes , continue remove it ! ");
      // $('#profile-picture').val('');
    } else {
      console.log("No, dont");
    }*/

    // return; // no need to use in void function
  });


});


