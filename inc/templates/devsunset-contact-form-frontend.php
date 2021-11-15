
<form id="devsunsetContactForm" class="devsunset-contact-form" action="#" method="post"
      data-url="<?php echo admin_url('admin-ajax.php');?>">
  <div class="devsunset-form-icon">

  </div>
  <div class="form-group">
    <input class="form-control devsunset-form-control" type="text"
           placeholder="Your Name" id="name" name="name" required="required">
  </div><!--form-group-->

  <div class="form-group">
    <input class="form-control devsunset-form-control" type="email"
           placeholder="Your Email" id="email" name="email" required="required">
  </div><!--form-group-->

  <div class="form-group">
    <textarea class="form-control devsunset-form-control" placeholder="Your Message"
              id="message" name="message" required="required"></textarea>
  </div><!--form-group-->

  <div class="response-message mb-4">
    <span class="form-control-message in-progress-response text-center mb-2">Submission is in progress. Please wait ...</span>
    <span class="form-control-message success-response text-center mb-2">Your contact information has been successfully submitted !</span>
    <span class="form-control-message failure-response text-center mb-2">Error ! Please check your network connection or contact administrator !</span>
  </div><!--response-message -->
  <div class="text-center devsunset-button-submit-form">
    <button type="submit" class="btn btn-default btn-lg button-form-submission">Submit</button>
  </div><!--devsunset-button-submit-form-->

</form>
