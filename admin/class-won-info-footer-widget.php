<?php
/**
 * Widget to insert and display office contact info 
 * Widget prepared with fields for English and Amharic languages
 *
 * @package    Won_Info
 * @subpackage Won_Info/admin
 * @author     Wondwossen Birhanie <wonwosbr@yahoo.com>
 * 
 * @since      1.0.0
 */

/**
* Footer widget to display office contact info
* with English or Amharic languages
*
* refer in wp-includes/class-wp-widget.php
*/
class Won_Info_Footer_Widget extends WP_Widget {
	/**
	 * The ID of this plugin.
	 */
	private static $plugin_name = 'won-info';

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$widget_ops = array( 
			'classname' => 'won_info_footer',
			'description' => 'Display contact address details like city, address, phone, postal code etc',
		);
		
		parent::__construct( 
			// Base ID of your widget
			'won_info_footer_widget', 
			// Widget name will appear in UI
			__( 'Won Info Footer Widget', self::$plugin_name ), 
			$widget_ops 
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		// widget title
		$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

		// get current language
		$lang = get_locale();
		// start displaying widget
		echo $args['before_widget'];
		// widget title
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<ul class="contact-info">
		<?php
			// displaying site name
			if ( isset( $instance['display_site_name'] ) && 'on' === $instance['display_site_name'] ) : 
				?>
					<li>
						<i class="fa fa-home mr-2"></i>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
					</li>
				<?php 
				endif;
			// company location

			// language is not english
			if ( $lang == 'am_ET' && ! empty( $instance['city_country_am'] ) ) {

				$city_country_am = $instance['city_country_am'];
					?>
					<li><i class="fa fa-map mr-2"></i><?php echo $city_country_am; ?></li>
		
		<?php } else {
				if ( ! empty( $instance['city_country'] ) ) { 
					$city_country = $instance['city_country'];
					?>
					<li><i class="fa fa-map mr-2"></i><?php echo $city_country; ?></li>
				<?php }
			}

			// company address

			// when lang not english
			if ( $lang == 'am_ET' && ! empty( $instance['location_address_am'] ) ) {
				
				$loc_address_am = $instance['location_address_am'];
					?>
					<li><i class="fa fa-map-marker mr-2"></i><?php echo $loc_address_am; ?></li>

		<?php } else {
				if ( ! empty( $instance['location_address'] ) ) { 
					$loc_address = $instance['location_address'];
					?>
					<li><i class="fa fa-map-marker mr-2"></i><?php echo $loc_address; ?></li>
				<?php 
				} 
			}

			// email address
			if ( ! empty( $instance['email_addr'] ) ) { ?>
				<li><i class="fa fa-envelope mr-2"></i><a href="mailto:<?php echo $instance['email_addr']; ?>"><?php echo $instance['email_addr']; ?></a></li>
			<?php } ?>

			<?php if ( ! empty( $instance['phone_num_1'] ) ) { ?>
				<li><i class="fa fa-phone mr-2"></i><a href="tel:<?php echo $instance['phone_num_1']; ?>"><?php echo $instance['phone_num_1']; ?></a></li>
			<?php } ?>

			<?php if ( ! empty( $instance['phone_num_2'] ) ) { ?>
				<li><i class="fa fa-phone mr-2"></i><a href="tel:<?php echo $instance['phone_num_2']; ?>"><?php echo $instance['phone_num_2']; ?></a></li>
			<?php } ?>

			<?php if ( ! empty( $instance['fax'] ) ) { ?>
				<li><i class="fa fa-fax mr-2"></i><?php echo $instance['fax']; ?></li>
			<?php } ?>

			<?php if ( ! empty( $instance['postal_code'] ) ) { ?>
				<li><i class="fa fa-envelope-open mr-2"></i><?php echo $instance['postal_code']; ?></li>
			<?php } ?>
		</ul>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( ( array ) $instance, 
			array(
				'title'						=>	'',
				'city_country'				=>	'',
				'city_country_am'			=>	'',
				'location_address'			=>	'',
				'location_address_am'		=>	'',
				'email_addr'				=>	'',
				'phone_num_1'				=>	'',
				'phone_num_2'				=>	'',
				'fax'						=>	'',
				'postal_code'				=>	'',
				'display_site_name'			=>	false,
			) 
		);

		?>
		<div class="widget-content">
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
		            value="<?php echo esc_attr( $instance['title'] ); ?>" 
		            placeholder="<?php esc_html_e( 'New Title', self::$plugin_name ); ?>"
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'city_country' ) ); ?>"><?php esc_attr_e( 'Company location:', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'city_country' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'city_country' ) ); ?>"
		            value="<?php echo esc_attr( $instance['city_country'] ); ?>" 
		            placeholder="<?php esc_html_e( 'City, Country', self::$plugin_name ); ?>"
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'city_country_am' ) ); ?>"><?php esc_attr_e( 'የድርጅቱ መገኛ ቦታ:', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'city_country_am' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'city_country_am' ) ); ?>"
		            value="<?php echo esc_attr( $instance['city_country_am'] ); ?>" 
		            placeholder="<?php esc_html_e( 'የከተማ ስም፣ ሀገር', self::$plugin_name ); ?>"
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'location_address' ) ); ?>"><?php esc_attr_e( 'Company Address: ', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'location_address' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'location_address' ) ); ?>"
		            value="<?php echo esc_attr( $instance['location_address'] ); ?>"
		            placeholder="<?php esc_html_e( 'Company specific address', self::$plugin_name ); ?>"
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'location_address_am' ) ); ?>"><?php esc_attr_e( 'የድርጅቱ አድራሻ: ', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'location_address_am' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'location_address_am' ) ); ?>"
		            value="<?php echo esc_attr( $instance['location_address_am'] ); ?>"
		            placeholder="<?php esc_html_e( 'የድርጅቱ ልዩ አድራሻ', self::$plugin_name ); ?>"
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'email_addr' ) ); ?>"><?php esc_attr_e( 'Email:', self::$plugin_name ); ?></label>
		        <input
		            type="email"
		            id="<?php echo esc_attr( $this->get_field_id( 'email_addr' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'email_addr' ) ); ?>"
		            value="<?php echo esc_attr( $instance['email_addr'] ); ?>"
		            placeholder="email@example.com" 
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'phone_num_1' ) ); ?>"><?php esc_attr_e( 'Phone Number 1:', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'phone_num_1' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'phone_num_1' ) ); ?>"
		            value="<?php echo esc_attr( $instance['phone_num_1'] ); ?>"
		            placeholder="+251581111111" 
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'phone_num_2' ) ); ?>"><?php esc_attr_e( 'Phone Number 2:', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'phone_num_2' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'phone_num_2' ) ); ?>"
		            value="<?php echo esc_attr($instance['phone_num_2'] ); ?>"
		            placeholder="+251582222222" 
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>"><?php esc_attr_e( 'Fax Number:', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'fax' ) ); ?>"
		            value="<?php echo esc_attr( $instance['fax'] ); ?>"
		            placeholder="0582222222" 
		            class="widefat"
		        />
		    </p>

		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'postal_code' ) ); ?>"><?php esc_attr_e( 'Postal Code:', self::$plugin_name ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'postal_code' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'postal_code' ) ); ?>"
		            value="<?php echo esc_attr( $instance['postal_code'] ); ?>"
		            placeholder="1000" 
		            class="widefat"
		        />
		    </p>

		    <?php  
				$display_site_name = isset( $instance['display_site_name'] ) ? '1' : false;
			?>
		    <p>
		        <input
		            type="checkbox"
		            value="<?php echo esc_attr( $display_site_name ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'display_site_name' ) ); ?>"
		            id="<?php echo esc_attr( $this->get_field_id( 'display_site_name' ) ); ?>" 
					class="checkbox" 
					<?php checked( '1', $display_site_name ); ?> 
				/>
		        <label for="<?php echo esc_attr( $this->get_field_id( 'display_site_name' ) ); ?>"><?php _e( 'Display site name?', self::$plugin_name ); ?></label>
		    </p>
		</div><!-- .widget-content -->
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();

		$instance['title'] = ( isset( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';

		$instance['city_country'] = ( isset( $new_instance['city_country'] ) ) ? sanitize_text_field( $new_instance['city_country'] ) : '';

		$instance['city_country_am'] = ( isset( $new_instance['city_country_am'] ) ) ? sanitize_text_field( $new_instance['city_country_am'] ) : '';

		$instance['location_address'] = ( isset( $new_instance['location_address'] ) ) ? sanitize_text_field( $new_instance['location_address'] ) : '';

		$instance['location_address_am'] = ( isset( $new_instance['location_address_am'] ) ) ? sanitize_text_field( $new_instance['location_address_am'] ) : '';

		$instance['email_addr'] = ( isset( $new_instance['email_addr'] ) ) ? sanitize_email( $new_instance['email_addr'] ) : '';
		
		$instance['phone_num_1'] = ( isset( $new_instance['phone_num_1'] ) ) ? sanitize_text_field( $new_instance['phone_num_1'] ) : '';

		$instance['phone_num_2'] = ( isset( $new_instance['phone_num_2'] ) ) ? sanitize_text_field( $new_instance['phone_num_2'] ) : '';

		$instance['fax'] = ( isset( $new_instance['fax'] ) ) ? sanitize_text_field( $new_instance['fax'] ) : '';

		$instance['postal_code'] = ( isset( $new_instance['postal_code'] ) ) ? sanitize_text_field( $new_instance['postal_code'] ) : '';

		$instance['display_site_name'] = isset( $new_instance['display_site_name'] ) ? '1' : false;

		return $instance;
	}
}