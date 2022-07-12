<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wontalksdesign.com
 * @since      1.0.0
 *
 * @package    Won_Info
 * @subpackage Won_Info/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Won_Info
 * @subpackage Won_Info/includes
 * @author     Wondwossen Birhanie <wonwosbr@yahoo.com>
 */
class Won_Info {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Won_Info_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	* Store plugin main class to allow public access.
	*
	* @since    1.0.0
	* @var object      The main class.
	*/
	public $main;

	/**
	* Store plugin public class to allow public access.
	*
	* @since    1.0.0
	* @var object      The public class.
	*/
	public $public;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WON_INFO_VERSION' ) ) {
			$this->version = WON_INFO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'won-info';

		// main class property
		$this->main = $this;

		// -----------Admin Settings Page----------New added-------------------
		$this->plugin_screen_hook_suffix = null;

		// -----------Admin Settings Page----------New added-------------------
		/**
		* get plugin admin setting options
		*/
		$this->won_info_options = get_option( $this->plugin_name );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Won_Info_Loader. Orchestrates the hooks of the plugin.
	 * - Won_Info_i18n. Defines internationalization functionality.
	 * - Won_Info_Admin. Defines all hooks for the admin area.
	 * - Won_Info_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-won-info-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-won-info-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-won-info-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-won-info-public.php';

		$this->loader = new Won_Info_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Won_Info_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Won_Info_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Won_Info_Admin( $this->get_won_info(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        
		// -----------Admin Settings Page----------New added-------------------
		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// -----------Admin Settings Page----------New added-------------------
		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// -----------Admin Settings Page----------New added-------------------
		// Save/Update our plugin options
		$this->loader->add_action( 'admin_init', $plugin_admin, 'options_update' );

		
		// -------------Footer Widget---------New added-------------------
		/**
		* Initializing widget
		*/
		if ( $this->won_info_options['footer_address_widget'] ) {
			$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_footer_address_widget' );
		}

		/***/
		if ( $this->won_info_options['contact_form'] ) {
			// contact form
			$plugin_cpt_cm = new Won_Info_Contact_Form( $this->get_won_info(), $this->get_version() );

			$this->loader->add_action( 'init', $plugin_cpt_cm, 'create_post_type' );
			$this->loader->add_action( 'add_meta_boxes', $plugin_cpt_cm, 'post_type_add_meta_box' );
			$this->loader->add_action( 'save_post', $plugin_cpt_cm, 'save_contact_email_metabox_data' );

			$this->loader->add_action( 'manage_contact-message_posts_custom_column', $plugin_cpt_cm, 'custom_post_type_column', 10, 2 );

			$this->loader->add_filter( 'manage_contact-message_posts_columns', $plugin_cpt_cm, 'custom_edit_post_type_columns' );
			$this->loader->add_filter( 'enter_title_here', $plugin_cpt_cm, 'post_type_enter_title_here', 10, 2 );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->public = new Won_Info_Public( $this->get_won_info(), $this->get_version(), $this->main );

		// load fontawesome style
		if ( $this->won_info_options['footer_address_widget'] ) {
			$this->public->load_fontawesome_public();
		}

		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_scripts' );

		if ( $this->won_info_options['contact_form'] ) {

			$this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_contact_form_scripts' );

			// -------------Contact Form---------New added-------------------
			// hook shortcode function
			$this->loader->add_action( 'init', $this->public, 'won_info_contact_form_add_shortcode' );

			/*
			*
			* The ajax_save_contact_form is the callback function.
			* wp_ajax_ is for authenticated users
			* wp_ajax_nopriv_ is for NOT authenticated users
			*/

			// hook ajax function to wp
			$this->loader->add_action( 'wp_ajax_won_info_ajax_save_contact_form', $this->public, 'won_info_ajax_save_contact_form' );

			$this->loader->add_action( 'wp_ajax_nopriv_won_info_ajax_save_contact_form', $this->public, 'won_info_ajax_save_contact_form' );
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_won_info() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Won_Info_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

