<?php

/**
 *
 * @link              http://wontalksdesign.com
 * @since             1.0.0
 * @package           Won_Info
 *
 * @wordpress-plugin
 * Plugin Name:       Won Contact Info
 * Plugin URI:        http://wontalksdesign.com/won-info/
 * Description:       Displays company contact address details on different areas of your site. Creates widgets for inserting details of your company's physical address.
 * Version:           1.0.0
 * Author:            Wondwossen Birhanie
 * Author URI:        http://wontalksdesign.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       won-info
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WON_INFO_VERSION', '1.0.0' );

define( 'PLUGIN_NAME_PLUGIN_NAME', 'won-info' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-won-info-activator.php
 */
function activate_won_info() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-won-info-activator.php';
	Won_Info_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-won-info-deactivator.php
 */
function deactivate_won_info() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-won-info-deactivator.php';
	Won_Info_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_won_info' );
register_deactivation_hook( __FILE__, 'deactivate_won_info' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-won-info.php';

/**
 * Fire our WON_INFO Loaded hook.
 *
 * @since 1.0.0
 *
 * @internal Use `won_info_loaded` hook.
 */
function won_info_loaded() {
	/**
	 * Fires upon plugins_loaded WordPress hook.
	 *
	 * WON_INFO loads the required files on this hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'won_info_loaded' );
}
add_action( 'plugins_loaded', 'won_info_loaded' );

/**
 * Loading tana-estate externals
 * 
 * @since 1.0.0
 * @internal 
 */
function won_info_externals() {
	/**
	 * Loading file containing function to set up cdn for fontawesome
	 * 
	 * A function fa_custom_setup_cdn_webfont( $cdn_url, $integrity ) will load
	 * fontawesome files and enqueue wherever needed in the plugin
	 */
	require_once plugin_dir_path( __FILE__ ) . 'public/externals/fontawesome-cdn-setup.php';
}
add_action( 'won_info_loaded', 'won_info_externals' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
/**
 * This allow you to access plugin main class and its methods from anywhere
 *
 * the global variable will be used to reference public methods
 */
global $wi_won_info;
$wi_won_info = new Won_Info();
$wi_won_info->run();
