<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wppb.me/
 * @since      1.0.0
 *
 * @package    Minical
 * @subpackage Minical/includes
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
 * @package    Minical
 * @subpackage Minical/includes
 * @author     Jaydeep Golait <jaydeep.golait@gmail.com>
 */
class MHBP_Minical {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Form_Builder_Form_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MHBP_PLUGIN_NAME_VERSION' ) ) {
			$this->version = MHBP_PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'minical';

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
	 * - Form_Builder_Form_Loader. Orchestrates the hooks of the plugin.
	 * - Form_Builder_Form_i18n. Defines internationalization functionality.
	 * - Form_Builder_Form_Admin. Defines all hooks for the admin area.
	 * - Form_Builder_Form_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-minical-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-minical-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-minical-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-minical-public.php';

		$this->loader = new MHBP_Minical_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Form_Builder_Form_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MHBP_Minical_i18n();

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

		$plugin_admin = new MHBP_Minical_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'mhbp_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'mhbp_admin_enqueue_scripts' );		
		/*hooks made by devloper*/
		$this->loader->add_action( 'wp_ajax_update_minical_api_key', $plugin_admin, 'mhbp_update_minical_api_key' );
		$this->loader->add_action( 'wp_ajax_update_booking_engine_fields', $plugin_admin, 'mhbp_update_booking_engine_fields' );
		$this->loader->add_action( 'wp_ajax_update_booking_engine_settings', $plugin_admin, 'mhbp_update_booking_engine_settings' );
		$this->loader->add_action( 'wp_ajax_deconfigure_minical', $plugin_admin, 'mhbp_deconfigure_minical' );
	
		$this->loader->add_action('admin_menu', $plugin_admin, 'mhbp_minical_setup_menu');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new MHBP_Minical_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mhbp_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mhbp_enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_check_room_type_availability', $plugin_public, 'mhbp_check_room_type_availability' );
		$this->loader->add_action( 'wp_ajax_charge_calculation', $plugin_public, 'mhbp_charge_calculation' );
		$this->loader->add_action( 'wp_ajax_book_room', $plugin_public, 'mhbp_book_room' );
		$this->loader->add_action( 'wp_ajax_get_customer_info_form', $plugin_public, 'mhbp_get_customer_info_form' );
		$this->loader->add_action( 'wp_ajax_nopriv_check_room_type_availability', $plugin_public, 'mhbp_check_room_type_availability' );
		$this->loader->add_action( 'wp_ajax_nopriv_charge_calculation', $plugin_public, 'mhbp_charge_calculation' );
		$this->loader->add_action( 'wp_ajax_nopriv_book_room', $plugin_public, 'mhbp_book_room' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_customer_info_form', $plugin_public, 'mhbp_get_customer_info_form' );

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
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Form_Builder_Form_Loader    Orchestrates the hooks of the plugin.
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