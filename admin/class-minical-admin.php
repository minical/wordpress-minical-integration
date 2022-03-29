<?php
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
class MHBP_Minical_Admin {
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
	public function mhbp_admin_enqueue_styles($hook_suffix) {
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
        if(($hook_suffix != 'toplevel_page_minical-plugin') && ($hook_suffix != 'minical_page_online-booking-engine') && ($hook_suffix != 'minical_page_minical-app')) {
        	return;
        }
        wp_enqueue_style( "bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "font-awesome-min", plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "minical-datatable", plugin_dir_url( __FILE__ ) . 'css/minical-datatable.min.css', array(), '1.10.19', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/minical-admin-page.css', array(), $this->version, 'all' );
    }
    /**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function mhbp_admin_enqueue_scripts($hook_suffix) {
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
		if(($hook_suffix != 'toplevel_page_minical-plugin') && ($hook_suffix != 'minical_page_online-booking-engine') && ($hook_suffix != 'minical_page_minical-app')) {
        	return;
        } 
        
        wp_enqueue_script('jquery');
		wp_enqueue_script( "bootstrap", plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/minical-backend.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( "minical-datatable", plugin_dir_url( __FILE__ ) . 'js/minical-datatable.min.js', array( 'jquery' ), '1.10.19', false );                                              
	}
	
    function mhbp_minical_setup_menu() {
		

		add_menu_page(
			'minicalplugin', 
			'Minical', 
			'manage_options', 
			'minical-plugin', 
			array( 
				$this, 'mhbp_minical_home_page' 
			) 
		);
		
		add_submenu_page(
			'minical-plugin', 
			'Minical App', 
			'Minical App', 
			'manage_options',
			'minical-app',
			array( 
				$this, 'mhbp_minical_page' 
			) 
		);

		add_submenu_page(
			'minical-plugin', 
			'Online Booking Engine', 
			'Online Booking Engine', 
			'manage_options',
			'online-booking-engine',
			array( 
				$this, 'mhbp_show_online_booking_engine' 
			) 
		);

		$minical_api_key = sanitize_key( get_option('minical_api_key') );
		
	}
	
	function mhbp_minical_home_page() {
		require_once('partials/minical-home-page-display.php');
	}

	function mhbp_minical_page() {
		require_once('partials/minical-admin-display.php');	
	}

	function mhbp_show_online_booking_engine() {
		require_once('partials/minical-booking-engine-display.php');
	}

	function mhbp_update_minical_api_key() {
		
		$minical_api_key = sanitize_key( $_POST['api_key'] );
	
        $is_valid_creds = false;
		
         if(!empty($minical_api_key)){
            $baseUrl = MHBP_MINICAL_API_URL;    
            $data = array('x_api_key' => $minical_api_key);

            $output = wp_remote_post(
                esc_url_raw( $baseUrl.'/company/get_company_id' ),
                array(
                    'sslverify' => FALSE,
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'X-API-KEY' => $minical_api_key
                        ),
                    'cookies' => array(),
                    'body' => json_encode($data),
                )
            );

            $result = json_decode(json_encode($output), true);
            $company_data = json_decode($result['body'], true);
           
            if(isset($company_data['error']) && $company_data['error']){
              $is_valid_creds = false; 
            } else {
            	update_option( 'minical_api_key', $minical_api_key);
                update_option( 'minical_company_id', $company_data['company_id']);
                update_option( 'minical_company_name', $company_data['company_name']);
                $is_valid_creds = true;
            }        
        }
	
		if($is_valid_creds){
			$msg = esc_html( 'Successfully added API key and company id' ); 
			$result = array('success' => true, 'msg' => $msg);
	     	return wp_send_json_success($result);
	     	
		} else {
			$msg = esc_html( 'Please enter a valid API key' );
			$result = array('success' => false, 'msg' => $msg);
	        return wp_send_json_success($result);	
		}
		
	}

    function mhbp_deconfigure_minical(){
	    $minical_company_id = sanitize_key( $_POST['company_id'] );
	    if($minical_company_id){
	    	delete_option( 'minical_api_key' );
            delete_option( 'minical_company_id' );
            delete_option( 'minical_company_name' );

	    }
	    $result = array('success' => true);
	     return wp_send_json_success($result);
    }

	function mhbp_update_booking_engine_fields(){

		$updated_booking_engine_fields = sanitize_post($_POST['updated_booking_engine_fields']);
		
		$common_booking_engine_fields = json_decode(MHBP_COMMON_BOOKING_ENGINE_FIELDS, true);

		$fields_data = array();
		foreach($updated_booking_engine_fields as $updated_booking_field)
        {
            $booking_engine_field_id = sanitize_text_field( $updated_booking_field['id'] );
            
            if(isset($common_booking_engine_fields[$booking_engine_field_id]))
            {
                $fields_data[$booking_engine_field_id] = array(
                    'id' => $booking_engine_field_id,
                    'company_id' => MHBP_COMPANY_ID,
                    'show_on_booking_form' => sanitize_text_field( $updated_booking_field['show_on_booking_form'] ),
                    'is_required' => sanitize_text_field( $updated_booking_field['is_required'] )
                );
            }
        }

        $fields_data = json_encode($fields_data);

        update_option('booking_engine_fields_'.MHBP_COMPANY_ID, $fields_data);

        $booking_engine_fields =  sanitize_option( 'booking_engine_fields_', get_option('booking_engine_fields_'.MHBP_COMPANY_ID) );
        
        $booking_engine_fields = json_decode($booking_engine_fields, true);

        $result = array('success' => true, 'result' => $booking_engine_fields);
	     return wp_send_json_success($result);
	}

	function mhbp_update_booking_engine_settings(){
		$company_data = array(
            'allow_same_day_check_in'               => sanitize_text_field( $_POST['allow_same_day_check_in'] ),
            'store_cc_in_booking_engine'            => sanitize_text_field( $_POST['store_cc_in_booking_engine'] ),
            'booking_engine_booking_status'         => sanitize_text_field( $_POST['booking_engine_booking_status'] ),
            'email_confirmation_for_booking_engine' => sanitize_text_field( $_POST['email_confirmation_for_booking_engine'] ),
            'booking_engine_tracking_code'          => sanitize_textarea_field( htmlentities($_POST['booking_engine_tracking_code']) )
        );
        $setting_data = json_encode($company_data);

        update_option('booking_engine_settings_'.MHBP_COMPANY_ID, $setting_data);

        $booking_engine_settings =  sanitize_option( 'booking_engine_settings_', get_option('booking_engine_settings_'.MHBP_COMPANY_ID) );

        $booking_engine_settings = json_decode($booking_engine_settings, true);

        $result = array('success' => true, 'result' => $booking_engine_settings);
	    return wp_send_json_success($result);
	}
}


