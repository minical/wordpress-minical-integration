<?php
//include(get_site_url().'/wp_load.php');
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wppb.me/
 * @since      1.0.0
 *
 * @package    Minical
 * @subpackage Minical/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Minical
 * @subpackage Minical/admin
 * @author     Jaydeep Golait <jaydeep.golait@gmail.com>
 */
class Minical_Admin {
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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        /**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Form_Builder_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Form_Builder_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        //wp_enqueue_style( "bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "font-awesome-min", plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "jquery-ui", plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( "minical-datatable", plugin_dir_url( __FILE__ ) . 'css/minical-datatable.min.css', array(), '1.10.19', 'all' );
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
		 * defined in Form_Builder_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Form_Builder_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		 
		wp_enqueue_script( "jquery-1.12", plugin_dir_url( __FILE__ ) . 'js/jquery-1.12.4.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "jquery-ui", plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/minical-backend.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "minical-datatable", plugin_dir_url( __FILE__ ) . 'js/minical-datatable.min.js', array( 'jquery' ), '1.10.19', false );
	}
	
    function minical_setup_menu() {
		add_menu_page(
			'minicalplugin', 
			'Minical', 
			'manage_options', 
			'minical-plugin', 
			array( 
				$this, 'minical_settings' 
			) 
		);

		$minical_api_key = get_option('minical_api_key');

		if($minical_api_key){

			add_submenu_page(
				'minical-plugin', 
				'Online Booking Engine', 
				'Online Booking Engine', 
				'manage_options',
				'online-booking-engine',
				array( 
					$this, 'show_online_booking_engine' 
				) 
			);

			add_submenu_page(
				'minical-plugin', 
				'Bookings', 
				'Bookings', 
				'manage_options',
				'bookings',
				array( 
					$this, 'show_bookings' 
				) 
			);

			add_submenu_page(
				'minical-plugin', 
				'Rooms', 
				'Rooms', 
				'manage_options',
				'rooms',
				array( 
					$this, 'show_rooms' 
				) 
			);

			add_submenu_page(
				'minical-plugin', 
				'Room Types', 
				'Room Types', 
				'manage_options',
				'room-types',
				array( 
					$this, 'show_room_types' 
				) 
			);
		}
	}
	
	function minical_settings() {
		require_once('partials/minical-admin-display.php');
	}

	function show_bookings() {
		require_once('partials/minical-bookings-display.php');
	}

	function show_rooms() {
		require_once('partials/minical-rooms-display.php');
	}

	function show_room_types() {
		require_once('partials/minical-room-types-display.php');
	}

	function show_online_booking_engine() {
		require_once('partials/minical-booking-engine-display.php');
	}

	function update_minical_api_key() {
		
		$api_key = get_option('minical_api_key');
		$minical_api_key = $_POST['api_key'];

		if(empty( $api_key )){
			add_option( 'minical_api_key', $minical_api_key);
		} else {
			update_option( 'minical_api_key', $minical_api_key);
		}

		echo json_encode(array('success' => true));
		die;
	}
}