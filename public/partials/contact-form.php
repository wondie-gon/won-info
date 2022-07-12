<?php
/**
* Template for displaying custom contact form
*
* @link       http://example.com
* @since      1.0.0
*
* @package    Won_Info
* @subpackage Won_Info/public/partials
*/
if ( isset( $_POST['submit'] ) ) {
	// get values
	$title = $_POST["frm_contact_name"];
	$email = $_POST["frm_contact_email"];
	$message = $_POST["frm_contact_message"];
}
?>
	<h4 class="frm-contact-header"><?php _e( 'Use the following form to send your message', $this->plugin_name ); ?></h4>
	<form id="won-info-contactform" method="post">
		<div class="form-group">
	      <label for="frm_contact_name"><?php esc_html_e( 'Your name', $this->plugin_name ); ?></label>
	          <input type="text" class="form-control" id="frm_contact_name" name="frm_contact_name" value="<?php echo $title ? $title : ''; ?>" placeholder="<?php esc_html_e( 'Your Name', $this->plugin_name ); ?>" />          
	    </div>
	    <div class="form-group mt-3">
	      <label for="frm_contact_email"><?php esc_html_e( 'Email', $this->plugin_name ); ?></label>
	          <input type="email" class="form-control" id="frm_contact_email" name="frm_contact_email" value="<?php echo $email ? $email : ''; ?>" placeholder="email@example.com" />        
	    </div>
	    <div class="form-group mt-3">
          <label for="frm_contact_message"><?php esc_html_e( 'Your message', $this->plugin_name ); ?></label>
          <textarea type="text" id="frm_contact_message" class="form-control" name="frm_contact_message" placeholder="<?php esc_html_e( 'Write your message here.', $this->plugin_name ); ?>" rows="8"><?php echo $message ? $message : ''; ?></textarea>
        </div>
        <div class="form-group mt-3">
        	<?php
				// create nonce when user clicks btn  
				$nonce = wp_create_nonce( 'send_btn_clicked' );
			?>
            <input type="hidden" name="nonce" id="contact_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
            <small class="form-control-msg js-form-submission"><?php esc_html_e( 'Submission in process, please wait...', $this->plugin_name ); ?></small>
            <small class="form-control-msg js-form-success"><?php esc_html_e( 'Message was successfully sent, thank you!', $this->plugin_name ); ?></small>
            <small class="form-control-msg js-form-error"><?php esc_html_e( 'Something went wrong while sending message, please try again.', $this->plugin_name ); ?></small>
            <input type="submit" name="submit" class="btn btn-primary submit-btn" value="<?php _e( 'Send', $this->plugin_name ); ?>" />
        </div>
	</form>
