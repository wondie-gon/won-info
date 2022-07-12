<?php
/**
 * Form fields to activate different features of the plugin
 *
 * @package    Won_Info
 * 
 * @since      1.0.0
 */ 
// -----------Admin Settings Page----------New added-------------------
?>

    <td>
        <fieldset>
            <legend class="screen-reader-text"><span><?php esc_attr_e( 'Activate features', $this->plugin_name ); ?></span></legend>

            <p>
                <label for="<?php echo $this->plugin_name; ?>-contact_form">
                    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-contact_form" name="<?php echo $this->plugin_name; ?>[contact_form]" value="1" <?php checked( $contact_form, 1 ); ?> /s>
                    <?php esc_attr_e( 'Contact form', $this->plugin_name ); ?>
                </label>
                <span class="description"><?php esc_attr_e( 'Activate and display contact form at contact us page or any page needed.', $this->plugin_name ); ?></span>
            </p>

            <p>
                <label for="<?php echo $this->plugin_name; ?>-footer_address_widget">
                    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-footer_address_widget" name="<?php echo $this->plugin_name; ?>[footer_address_widget]" value="1" <?php checked( $footer_address_widget, 1 ); ?> />
                    <?php esc_attr_e( 'Footer contact address', $this->plugin_name ); ?>
                </label>
                <span class="description"><?php esc_attr_e( 'Activate footer widget to display contact address at footer', $this->plugin_name ); ?></span>

            </p>
        </fieldset>
    </td>
    