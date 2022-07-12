<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Won_Info
 * @subpackage Won_Info/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Won_Info
 * @subpackage Won_Info/public
 * @author     Wondwossen Birhanie <wonwosbr@yahoo.com>
 */
class Won_Info_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	* Store plugin main class to allow public access.
	*
	* @since    1.0.0
	* @var object      The main class.
	*/
	public $main;

	public $errors;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param 	   object 	 $plugin_main 		Reference to main plugin class
	 */
	public function __construct( $plugin_name, $version, $plugin_main ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->main = $plugin_main;

		// -----------Admin Settings Page----------New added-------------------
		/**
		* get plugin admin setting options
		*/
		$this->won_info_options = get_option( $this->plugin_name );

	}

	/**
	 * Load, enqueue all style of fontawesome from cdn
	 *
	 * @since    1.0.0
	 */
	public function load_fontawesome_public() {
		/**
		* load style
		*
		* You can change the params and use the latest versions by going to the link
		* see:
		* @link https://cdnjs.com/libraries/font-awesome
		*/
		$url = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css';
		$integrity = 'sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==';

		// won_info_fa_custom_setup_cdn_webfont( $url );
		if ( function_exists( 'won_info_fa_custom_setup_cdn_webfont' ) ) {
			won_info_fa_custom_setup_cdn_webfont( $url, $integrity );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Won_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Won_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/won-info-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Won_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Won_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/won-info-public.js', array( 'jquery' ), $this->version, true );

		// localizing vars
		wp_localize_script( $this->plugin_name, 'wp_ajax_obj',
	        array( 
	            'ajaxurl' => admin_url( 'admin-ajax.php' ),
	            // 'nonce' => wp_create_nonce( 'send_btn_clicked' ),
	        )
	    );

	}

	/**
	 * Register the JavaScript for contact form
	 *
	 * @since    1.0.0
	 */
	public function enqueue_contact_form_scripts() {
		/**
		* Enqueueing script for contact form
		*/
		wp_enqueue_script( $this->plugin_name . '-contact-form', plugin_dir_url( __FILE__ ) . 'js/won-info-contact-form.js', array( 'jquery' ), $this->version, false );
	}

	// Registering shortcode
	public function won_info_contact_form_add_shortcode() {
		// Register contact form function to shortcode tag
		add_shortcode( 'won_info_contact_form', array( $this, 'won_info_display_contact_form' ), 10, 2 );
	}

	// Shortcode for contact form
	public function won_info_display_contact_form( $atts, $content = null ) {

		// [won_info_contact_form]
		
		// get the attributes
		$atts = shortcode_atts(
				array(),
				$atts,
				'won_info_contact_form'
			);

		// Return contact form html
		ob_start();

		include( plugin_dir_path( dirname( __FILE__ ) ) . "public/partials/won-info-public-display.php" );


		return ob_get_clean();
	}


	/**
	* method to carry out ajax call action from contact form
	*/
	public function won_info_ajax_save_contact_form() {

		$result = '';
		/**
		* verify legitimate form submission with nonce
		*
		* @link https://codex.wordpress.org/Function_Reference/wp_verify_nonce
		*/

		if ( ! wp_verify_nonce( $_POST['nonce'], 'send_btn_clicked' ) ) {
		      exit( 'No naughty business please' );
		}


		// grab input from form
		$title = sanitize_text_field( $_POST['contactName'] );
		$email = sanitize_email( $_POST['contactEmail'] );
		$message = sanitize_textarea_field( wp_strip_all_tags( $_POST['contactMessage'] ) );

		$args = array(
			'post_type'		=>	'contact-message',
			'post_title'	=>	$title,
			'post_content'	=>	$message,
			'post_status'	=>	'publish',
			'post_date' 	=>	wp_date( get_option( 'date_format' ), get_post_timestamp() ),
			'meta_input'	=>	array(
				'_contact_email_value_key'	=>	$email
				)
			);
		

		// insert into custom post type database
		$post_id = wp_insert_post( $args );

		// Email the message to admin
		if ( $post_id != 0 ) {
			
			$to = get_bloginfo( 'admin_email' );
			$subject = 'Site Contact Form-' . $title;
			$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . $to . '>';
			$headers[] = 'Reply-To: ' . $title . ' <' . $email . '>';
			$headers[] = 'Content-Type: text/html: charset=UTF-8';

			wp_mail( $to, $subject, $message, $headers );

			$result = $post_id;

		} else {
			$this->errors = new WP_Error();
			$this->errors->add( 'contact-form-error', __( 'Something went wrong.', $this->plugin_name ) );
			// $result = $post_id->get_error_message();
		}

		if ( $this->errors->get_error_codes() ) {
			$result = $this->show_errors();
		}

		wp_die( $result );
		
	}
	/**
	 * Display any errors returned by the plugin
	 */
	public function show_errors() {
		
        if (!is_wp_error($this->errors)) {
            return;
        }
        $codes = $this->errors->get_error_codes();
        ?>
		<div class="error">
			<p>
			<?php 
				foreach ($codes as $code) {
					echo $this->errors->get_error_message($code);
			?>
			<br />
			<?php } ?>
			</p>
		</div>
		<?php 
    }

}
