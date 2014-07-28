var go_contact_form = {};

( function($) {

	go_contact_form.submit_form = function( e ) {
	  e.preventDefault();

	  var $form = $( this );
		// Mark the form as active and remove any previous error or info states
		$form.addClass( 'active' );
		$form.find( 'input, textarea, .info' ).removeClass( 'error' );
		$form.find( '.info' ).hide();

		// Submit form
		$.post(
			$form.attr( 'action' ),
			$form.serialize(),
			function( data ) {
				var form_data = $.parseJSON( data );

				// Remove the 'active' class from the form
				$form.removeClass( 'active' );

				if ( 'undefined' != typeof form_data.success ) {
					go_contact_form.submission_succeeded( form_data );
				} else if ( 'undefined' != typeof form_data.error ) { //end if
					go_contact_form.submission_failed( form_data );
				} //end else if
			}
		);
	};

	go_contact_form.submission_failed = function( form_data ) {
		form_data = form_data || {};
		form_data.fields = form_data.fields || [];

		// Show error message
		var $form_data_info = $( '#go-contact-' + form_data.instance + ' .info' );
		$form_data_info.html( form_data.error );
		$form_data_info.addClass( 'error' );
		$form_data_info.fadeIn( 'slow' );

		// Add error class to appropriate inputs
		for ( var n = 0; n < form_data.fields.length; n++ ) {
			$( '#go-contact-' + form_data.instance + ' .' + form_data.fields[n] ).addClass( 'error' );
		} //end for

		// Refresh reCaptcha
		if ( 'object' == typeof Recaptcha ) {
			Recaptcha.reload();
		} //end if
	};

	go_contact_form.submission_succeeded = function( form_data ) {
		var form_data_instance = form_data.instance;
		// Hide form
		$( '#go-contact-' + form_data_instance + ' *' ).hide();
		// Show success message
		var $form_data_info = $( '#go-contact-' + form_data_instance + ' .info' );
		$form_data_info.html( form_data.success );
		$form_data_info.addClass( 'success' );
		$form_data_info.fadeIn( 'slow' );
	};

	$( document ).on( 'submit', '.go-contact', go_contact_form.submit_form );

	//allows templates with email icon to toggle the form open/closed
	$( function() {
		$( '.go-contact' ).each( function() {
			// get the form
			var $contact_form = $( this );
			// get the icon
			var $link_icon = $contact_form.siblings( '.contact-form-icon' );

			//find the recaptcha form
			var recaptcha_form = $( '.go-recaptcha:not(:empty)' ).html();

			// bind to click event
			$( $link_icon ).on( 'click', function( event ) {
				event.preventDefault();
				// hide/show form
				$contact_form.toggle();
				// toggle class for social icons ($link_icon included)
				$link_icon.parent().toggleClass( 'form-open' );

				if( $link_icon.parent().hasClass( 'form-open' ) ) {
					// hide other forms
					$( '.go-contact:not(#' + $contact_form.attr( 'id' ) + ')' ).each( function() {
						$( this ).parent().removeClass( 'form-open' );
						//empty their recaptcha(s)
						$( this ).find( '.go-recaptcha' ).empty();
						$( this ).hide();
					} );
					$contact_form.find( '.go-recaptcha' ).html( recaptcha_form );
				} //end if
			} );

			//we need the form to NOT have display:none set for custom recaptcha template to work...
			//so we start with it set off the page to the left to prevent some of the FOUC
			$contact_form.hide();
			$contact_form.removeClass( 'in-plain-sight' );
		} ); //end each
	} );

} )(jQuery);