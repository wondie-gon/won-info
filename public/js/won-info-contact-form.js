(function( $ ) {
	'use strict';

	$(function() {

	 	$('#frm_contact_name, #frm_contact_email, #frm_contact_message').on('change', function(e) {
			$('.input-error-msg').remove();
		});
	 	
	 	/* Contact form submission */
		$('#won-info-contactform').on('submit', function(e) {

			e.preventDefault();

			$('.input-error-msg').remove();
			$('.js-form-feedback').removeClass('js-form-feedback');

			var form = $(this),
				frm_contact_name = form.find('#frm_contact_name').val(),
				frm_contact_email = form.find('#frm_contact_email').val(),
				frm_contact_message = form.find('#frm_contact_message').val(),
				nonce = form.find('#contact_nonce').val();
			
			// from wp_localize_script
			var ajax_url = wp_ajax_obj.ajaxurl;

			// Validating form input for browsers not supporting html5
			if (frm_contact_name.length < 1) {
				$('#frm_contact_name').after('<small class="input-error-msg">Your Name is Required</small>');
				$('#frm_contact_name').focus();
				return;
			} else if (frm_contact_name.length > 100) {
				$('#frm_contact_name').after('<small class="input-error-msg">Name should not exceed 100 characters.</small>');
				$('#frm_contact_name').focus();
				return;
			} else {
				var strAlphaRegEx = /^[a-z\s]+$/i;
				var isContactNameValid = strAlphaRegEx.test(frm_contact_name);
				if (!isContactNameValid) {
					$('#frm_contact_name').after('<small class="input-error-msg">Use only alphabets for your name.</small>');
					$('#frm_contact_name').focus();
					return;
				}
			}

			if (frm_contact_email.length < 1) {
				$('#frm_contact_email').after('<small class="input-error-msg">Email is Required</small>');
				$('#frm_contact_email').focus();
				return;
			} else {
				var emlRegEx = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
				var isEmailValid = emlRegEx.test(frm_contact_email);
				if (!isEmailValid) {
					$('#frm_contact_email').after('<small class="input-error-msg">Enter a valid email</small>');
					$('#frm_contact_email').focus();
					return;
				}
			}

			if (frm_contact_message.length < 1) {
				$('#frm_contact_message').after('<small class="input-error-msg">Some message is Required</small>');
				$('#frm_contact_message').focus();
				return;
			} else {
				var msgRegEx = /^[a-z0-9_\-\s\.\?]+$/i;
				var isMsgValid = msgRegEx.test(frm_contact_message);
				if (!isMsgValid) {
					$('#frm_contact_message').after('<small class="input-error-msg">Invalid characters are included in your message.</small>');
					$('#frm_contact_message').focus();
					return;
				}
			}

			// disabling the submit button after submission
			form.find('input, button, textarea').attr('disabled', 'disabled');
			$('.js-form-submission').addClass('js-form-feedback');

			$.ajax({
				url : ajax_url,
				type : 'post',
				data : {
					contactName : frm_contact_name,
					contactEmail : frm_contact_email,
					contactMessage : frm_contact_message,
					action : 'won_info_ajax_save_contact_form', 
					nonce: nonce
				},
				error :function(response) {
					$('.js-form-submission').removeClass('js-form-feedback');
					$('.js-form-error').addClass('js-form-feedback');
					form.find('input, button, textarea').removeAttr('disabled');
				},
				success : function(response) {
					setTimeout(function() {
						$('.js-form-submission').removeClass('js-form-feedback');
				 		$('.js-form-success').addClass('js-form-feedback');

				 		$('.js-form-feedback').slideDown(300, function(){
				 			form[0].reset();
				 		}).delay(1000).fadeOut(300);
				 		
				 		form.find('input, button, textarea').removeAttr('disabled');

					}, 500);
				}
			}); // end ajax
		});

	});

})( jQuery );