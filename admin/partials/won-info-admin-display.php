<?php
/**
 * This is a template for plugin's settings page where options are set
 * 
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Won_Info
 * 
 * @since      1.0.0
 */ 
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php  
// -----------Admin Settings Page----------New added-------------------
?>
<div class="wrap">

    <div id="icon-options-general" class="icon32"></div>

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <h2 class="nav-tab-wrapper">
        <a href="#main-setting-content" class="nav-tab nav-tab-active"><?php _e( 'Site Options', $this->plugin_name ); ?></a>
        <a href="#plugin-support" class="nav-tab nav-tab"><?php _e( 'Plugin support', $this->plugin_name ); ?></a>
    </h2>

    <div id="main-setting-content">
        
        <h3><?php _e( 'Check features to activate', $this->plugin_name ); ?></h3>

        <form id="won_info-settings" method="post" name="site_options" action="options.php">

        <?php

            // get options
            $options = get_option( $this->plugin_name );

            // activated features
            $contact_form = $options['contact_form'];
            $footer_address_widget = $options['footer_address_widget'];

            // hidden fields of settings
            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name ); 
        ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php _e( 'Activate features', $this->plugin_name ); ?></th>
                        <th scope="row"><?php _e( 'Customize features', $this->plugin_name ); ?></th>
                        <?php

                            // activation form fields
                            require_once( 'features-activation-form.php' );

                            // customization fields
                            require_once( 'features-customization.php' );

                        ?>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <?php

                    // submit button to save changes
                    submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE );
                ?>
            </p>
        </form>

    </div><!-- #main-setting-content -->

    <?php

        // plugin support
        require_once( 'plugin-support.php' );

    ?>

</div><!-- .wrap -->
