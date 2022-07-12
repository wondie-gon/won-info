<?php

/**
 * Custom contact form
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Won_Info
 * @subpackage Won_Info/admin
 * @author     Wondwossen Birhanie <wonwosbr@yahoo.com>
 */

// class contact form
class Won_Info_Contact_Form {
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
	}

	/**
	 * Create custom post type
	 *
	 * @since    1.0.0
	 */
	public function create_post_type() {
		$labels = array(
	 		'name'					=>	esc_html__( 'Messages', $this->plugin_name ),
	 		'singular_name'			=>	esc_html__( 'Message', $this->plugin_name ),
	 		'add_new'				=>	esc_html__( 'Add New', $this->plugin_name ),
	 		'add_new_item'			=>	esc_html__( 'Add New Message', $this->plugin_name ),
	 		'edit_item'				=>	esc_html__( 'Edit Message', $this->plugin_name ),
	 		'new_item'				=>	esc_html__( 'New Message', $this->plugin_name ),
	 		'all_items'				=>	esc_html__( 'All Messages', $this->plugin_name ),
	 		'view_item'				=>	esc_html__( 'View Message', $this->plugin_name ),
	 		'search_items'			=>	esc_html__( 'Search Messages', $this->plugin_name ),
	 		'not_found'				=>	esc_html__( 'No Messages Found', $this->plugin_name ),
	 		'not_found_in_trash'	=>	esc_html__( 'No Messages Found in Trash', $this->plugin_name ),
	 		'parent_item_colon'		=>	'',
	 		'menu_name'				=>	esc_html__( 'Messages', $this->plugin_name ),
	 		'name_admin_bar'		=>	esc_html__( 'Message', $this->plugin_name ),
	 		);

	 	$args = array(
	 		'labels'			=>	$labels,
	 		'show_ui'			=>	true,
	 		'show_in_menu'		=>	true,
	 		'capability_type'	=>	'post',
	 		'hierarchical'		=>	false,
	 		'menu_position'		=>	27,
	 		'menu_icon'			=>	'dashicons-email-alt',
	 		'supports'			=>	array( 'title', 'editor' )
	 		);

	 	// register cpt
	 	register_post_type( 'contact-message', $args );
	}

	/**
	 * Recreate custom post type's edit post list columns
	 *
	 * @param array $columns modified edit post columns
	 * @since    1.0.0
	 */
	public function custom_edit_post_type_columns( $columns ) {

		$columns = array(
	 		'title'		=>	esc_html__( 'Full Name', $this->plugin_name ),
	 		'message'	=>	esc_html__( 'Message', $this->plugin_name ),
	 		'email'		=>	esc_html__( 'Email', $this->plugin_name ),
	 		'date'		=>	esc_html__( 'Date', $this->plugin_name ),
		);

	 	return $columns;

	}

	/**
	* Function for what to display on edit post list custom columns
	*
	* @param $column assigned column
	* @param WP_Post $post_id
	*/
	public function custom_post_type_column( $column, $post_id ) {

		switch ( $column ) {

			case 'message' :

				echo get_the_excerpt();

				break;

			case 'email' :
				// get email
				$email = get_post_meta( $post_id, '_contact_email_value_key', true );
				echo '<a href="mailto:' . $email . '">' . $email . '</a>';
				break;

		}
	}

	/**
	* 
	* Add custom meta box
	*/
	public function post_type_add_meta_box() {
		
		add_meta_box( 
			'contact_email', 
			__( 'User Email', $this->plugin_name ), 
			array( $this, 'meta_box_contact_email_callback' ), 
			'contact-message', 
			'normal' 
		);
	}

	/**
	* Callback function rendering custom metabox
	*
	* @param WP_Post $post
	* @return renders meta box
	*/
	public function meta_box_contact_email_callback( $post ) {
		
		wp_nonce_field( array( $this, 'save_contact_email_metabox_data' ), 'contact_email_meta_box_nonce' );

		$value = get_post_meta( $post->ID, '_contact_email_value_key', true );

		echo '<label for="contact_email_mb">' . esc_html__( 'User email address: ', $this->plugin_name ) . '</label>';
		echo '<input type="email" class="form-control" id="contact_email_mb" name="contact_email_mb" value="' . esc_attr( $value ) . '" placeholder="' . esc_html__( 'Email', $this->plugin_name ) . '" size="25" />';
	}

	/**
	* Updates metabox field value
	*
	* @param WP_Post $post_id
	* @return null
	*
	*/
	public function save_contact_email_metabox_data( $post_id ) {

		if ( ! isset( $_POST['contact_email_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['contact_email_meta_box_nonce'], array( $this, 'save_contact_email_metabox_data' ) ) ) {
			return;
		}
		if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( ! isset( $_POST['contact_email_mb'] ) ) {
			return;
		}

		$contact_email_data = sanitize_email( $_POST['contact_email_mb'] );

		update_post_meta( $post_id, '_contact_email_value_key', $contact_email_data );

	}

	/**
	 * Filter: Modifies the standard placeholder text
	 * @param string $title
	 * @param WP_Post $post
	 * @return string
	 */
	public function post_type_enter_title_here( $title, $post ) {

	    if ( 'contact-message' == $post->post_type ) {

	        $title = __( 'Add contact name', $this->plugin_name );

	    }
	    
	    return $title;

	}

}
