<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Won_Info
 * @subpackage Won_Info/admin
 * @author     Wondwossen Birhanie <wonwosbr@yahoo.com>
 * 
 * @since      1.0.0
 */
class Won_Info_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// -----------Admin Settings Page----------New added-------------------
		/**
		* get plugin admin setting options
		*/
		$this->won_info_options = get_option( $this->plugin_name );

		// -------------Footer Widget---------New added-------------------
		$this->load_plugin_main_admin_features();

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/won-info-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		// if we want to load script only in plugin settings page
		// if ( 'settings_page_won-info' == get_current_screen()->id ) {}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/won-info-admin.js', array( 'jquery' ), $this->version, true );

	}

	// -----------Admin Settings Page----------New added-------------------
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

	    /*
	     * Add a settings page for this plugin to the Settings menu.
	     *
	     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	     *
	     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
	     *
	     */
	    $plugin_screen_hook_suffix = add_options_page( __( 'Won Info Settings', $this->plugin_name ), 'Won Info', 'manage_options', $this->plugin_name, array( $this, 'display_plugin_setup_page' )
	    );
	}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links( $links ) {
	    /*
	    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	    */
	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
	   );

	   $links = array_merge( $links, $settings_link );

	   return $links;

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page() {
	    include_once( 'partials/won-info-admin-display.php' );
	}

	// -----------Admin Settings Page----------New added-------------------
	/**
	* Saving form updates
	*/
	 public function options_update() {
	    register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate_activation' ) );
	 }

	// -----------Admin Settings Page----------New added-------------------
	/**
	 * Validate and sanitize inputs from form
	 *
	 * @since    1.0.0
	 */
	public function validate_activation( $input ) {

	    // All checkboxes inputs        
	    $valid = array();

	    // contact form activation
	    $valid['contact_form'] = ( isset( $input['contact_form'] ) && ! empty( $input['contact_form'] ) ) ? 1 : 0;

	    // sidebar address widget activation
	    $valid['footer_address_widget'] = ( isset( $input['footer_address_widget'] ) && ! empty( $input['footer_address_widget'] ) ) ? 1 : 0;

	    return $valid;
	 }

	// -------------Footer Widget---------New added-------------------
	/**
	 * Load classes that performs main functionalities of the plugin here
	 *
	 * You can add other newly created classes to be loaded
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_plugin_main_admin_features() {
		/**
		 * The class for displaying widget to add contact info in footer area
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-won-info-footer-widget.php';

		/**
		 * The class for custom form
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-won-contact-form.php';
	}

	// -------------Footer Widget---------New added-------------------
	/**
	* Register 'Won_Info_Footer_Widget' which will be ready for use
	*
	* @since    1.0.0
	* @access   public
	*/
	public function register_footer_address_widget() {

		register_widget( 'Won_Info_Footer_Widget' );
	 	
	}

}
