<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Won_Info
 * @subpackage Won_Info/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php  
	// display contact form
	require_once( plugin_dir_path( dirname(__FILE__) ) . 'partials/contact-form.php' );
?>
