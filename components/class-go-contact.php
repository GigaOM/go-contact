<?php

class GO_Contact_Form
{
	public $slug     = 'go-contact';
	public $instance = 1;
	public $config;
	public $required_fields = array(
		'email',
		'name',
		'subject',
		'body',
	);

	/**
	 * Constructor!
	 */
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_ajax_go_contact_submit', array( $this, 'submit_ajax' ) );
		add_action( 'wp_ajax_nopriv_go_contact_submit', array( $this, 'submit_ajax' ) );
		add_shortcode( 'go_contact', array( $this, 'contact_shortcode' ) );
	} // END __construct

	/**
	 *	Hooked to init action to register a script
	 */
	public function init()
	{
		if ( is_admin() )
		{
			return;
		}//end if

		$script_config = apply_filters( 'go_config', array( 'version' => 1 ), 'go-script-version' );

		wp_register_script(
			$this->slug . '-js',
			plugins_url( 'js/' . $this->slug . '.js', __FILE__ ),
			array( 'jquery' ),
			$script_config['version'],
			TRUE
		);
	} // END init

	/**
	 *	Singleton for config data
	 */
	public function config()
	{
		if ( ! $this->config )
		{
			$this->config = (object) apply_filters(
				'go_config',
				array( 'secret' => 'this is a secret' ),
				$this->slug
			);
		} // END if

		return $this->config;
	} // END config

	/**
	 * Return a contact form for the given email address
	 */
	public function contact_shortcode( $attributes )
	{
		$attributes = shortcode_atts(
			array(
				'email'  => '',
				'form'   => 'default',
				'submit' => 'Submit',
			),
			$attributes
		);

		if ( ! is_email( $attributes['email'] ) )
		{
			return;
		} // END if

		$email_hash = $this->encrypt( sanitize_email( $attributes['email'] ) );

		// Using site_url to avoid JS cross site scripting issues
		$target = site_url( '/wp-admin/admin-ajax.php?action=go_contact_submit' );

		if ( ! wp_script_is( $this->slug . '-js', 'enqueued' ) )
		{
			wp_enqueue_script( $this->slug . '-js' );
		}

		ob_start();
		include __DIR__ . '/templates/' . sanitize_file_name( $attributes['form'] ) . '-form.php';
		$attributes['form'] = ob_get_clean();

		$this->instance++;

		return $attributes['form'];
	} // END contact_shortcode

	/**
	 * Admin ajax function to handle contact form submissions
	 */
	public function submit_ajax()
	{
		// Get the submitted instance
		$instance = is_numeric( key( $_POST[ $this->slug ] ) ) ? key( $_POST[ $this->slug ] ) : 1;
		$return   = array( 'instance' => $instance );

		// Check for submission data
		if ( ! isset( $_POST[ $this->slug ] ) )
		{
			$return['error'] = 'Submission data could not be found.';
			echo json_encode( $return );
			die;
		} // END if

		// Check the recipient email address
		if ( ! $recipient = is_email( $this->decrypt( $_POST[ $this->slug ][ $instance ]['hash'] ) ) )
		{
			$return['error'] = 'The hash is invalid.';
			echo json_encode( $return );
			die;
		} // END if

		// Get list of required fields if it exists
		if ( isset( $_POST[ $this->slug ][ $instance ]['required'] ) ) {
			$required = explode( ',', $_POST[ $this->slug ][ $instance ]['required'] );
			
			if ( ! empty( $required ) ) {
				$this->required_fields = $required;
			}
		}

		// Check for all of the necessary fields
		if ( $fields = $this->check_required( $instance ) )
		{
			$return['error']  = 'All of the required fields are not filled out.';
			$return['fields'] = $fields;

			echo json_encode( $return );
			die;
		} // END if

		// Check the from email address
		if ( ! is_email( $_POST[ $this->slug ][ $instance ]['email'] ) )
		{
			$return['error']    = 'The email address is not valid.';
			$return['fields'][] = 'email';
			echo json_encode( $return );
			die;
		} // END if

		// Validate the nonce
		if ( ! $this->verify_nonce( $instance ) )
		{
			$return['error'] = 'Permission failed.';
			echo json_encode( $return );
			die;
		} // END if

		// Validate the reCaptcha
		if ( function_exists( 'go_recaptcha' ) && ! go_recaptcha()->check_request() )
		{
			$return['error'] = go_recaptcha()->get_message_text();
			echo json_encode( $return );
			die;
		} // END if

		$from_email = sanitize_email( $_POST[ $this->slug ][ $instance ]['email'] );
		$from_name = wp_kses( $_POST[ $this->slug ][ $instance ]['name'], array() );
		$subject = wp_kses( $_POST[ $this->slug ][ $instance ]['subject'], array() );
		$message = wp_kses_post( $_POST[ $this->slug ][ $instance ]['body'] );
		$to = sanitize_email( $recipient );

		ob_start();
		include __DIR__ . '/templates/default-email.php';
		$message = ob_get_clean();

		if (
			$this->send_email(
				$to,
				$from_email,
				$from_name,
				$subject,
				$message
			)
		)
		{
			$return['success'] = 'Message sent!';
		} // END if
		else
		{
			$return['error'] = 'An unknown error occured.';
		} // END else

		echo json_encode( $return );
		die;
	} // END submit_ajax

	/**
	 * Send a contact form email
	 */
	public function send_email( $to, $from_email, $from_name, $subject, $message )
	{
		$headers = 'Reply-To: "' . wp_kses( $from_name, array() ) . '" <' . sanitize_email( $from_email ) . '>' . "\r\n";

		return wp_mail( sanitize_email( $to ), wp_kses( $subject, array() ), wp_kses_post( $message ), $headers );
	} // END send_email

	/**
	 * Check for values in the required contact form fields
	 */
	public function check_required( $instance )
	{
		$fields = array();

		foreach ( $this->required_fields as $field )
		{
			if ( ! isset( $_REQUEST[ $this->slug ][ $instance ][ $field ] ) || '' == $_REQUEST[ $this->slug ][ $instance ][ $field ] )
			{
				$fields[] = $field;
			} // END if
		} // END foreach

		if ( empty( $fields ) )
		{
			$fields = FALSE;
		} // END if

		return $fields;
	} // END check_required

	/**
	 * Get the id of a field by name
	 */
	public function get_field_id( $field_name, $instance = '' )
	{
		$instance = $instance ? $instance : $this->instance;
		return $this->slug . '-' . $instance . '-' . $field_name;
	} // END get_field_id

	/**
	 * Get the name of a field by name?
	 */
	public function get_field_name( $field_name, $instance = '' )
	{
		$instance = $instance ? $instance : $this->instance;
		return $this->slug . '[' . $instance . '][' . $field_name . ']';
	} // END get_field_name

	/**
	 * Create the nonce field
	 */
	public function nonce_field( $instance )
	{
		wp_nonce_field( $this->slug, $this->get_field_name( 'nonce' ) );
	} // END nonce_field

	/**
	 * Verify the nonce
	 */
	public function verify_nonce( $instance )
	{
		return wp_verify_nonce( $_REQUEST[ $this->slug ][ $instance ]['nonce'], $this->slug );
	} // END verify_nonce

	/**
	 * Decrypt hashed input
	 */
	public function decrypt( $hash )
	{
		global $wpdb;

		if ( function_exists( 'mcrypt_decrypt' ) )
		{
			$decrypted = trim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $this->config()->secret, base64_decode( $hash ), MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) );
		}//end if
		else
		{
			$decrypted = $wpdb->get_var( $wpdb->prepare( 'SELECT AES_DECRYPT( UNHEX( %s ), %s )', $hash, $this->config()->secret ) );
		}// end else

		return $decrypted;
	} // END decrypt

	/**
	 * encrypt input string
	 */
	public function encrypt( $string )
	{
		global $wpdb;

		if ( function_exists( 'mcrypt_encrypt' ) )
		{
			$encrypted = trim( base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $this->config()->secret, $string, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) ) );
		}//end if
		else
		{
			$encrypted = $wpdb->get_var( $wpdb->prepare( 'SELECT HEX( AES_ENCRYPT( %s, %s ) )', $string, $this->config()->secret ) );
		}// end else

		return $encrypted;
	} // END encrypt
}// END GO_Contact_Form

/**
 * Singleton
 */
function go_contact()
{
	global $go_contact_form;

	if ( ! isset( $go_contact_form ) || ! $go_contact_form )
	{
		$go_contact_form = new GO_Contact_Form();
	}//end if

	return $go_contact_form;
} // END go_contact