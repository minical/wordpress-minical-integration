<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wppb.me/
 * @since      1.0.0
 *
 * @package    Minical
 * @subpackage Minical/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Minical
 * @subpackage Minical/public
 * @author     Jaydeep Golait <jaydeep.golait@gmail.com>
 */
class MHBP_Minical_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mhbp_enqueue_styles() {

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
		wp_enqueue_style( "font-awesome-min", plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "datepicker", plugin_dir_url( __FILE__ ) . 'css/datepicker.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/minical-public.css', array(), $this->version, 'all' );
    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mhbp_enqueue_scripts() {

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
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( "bootbox", plugin_dir_url( __FILE__ ) . 'js/bootbox.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/minical-public.js', array( 'jquery' ), time(), false );
		?>
		<script type="text/javascript">
		   var site_url = '<?php echo esc_url(get_option('siteurl')); ?>';
		</script>
	<?php }

	function mhbp_check_room_type_availability(){
		$check_in_date = sanitize_text_field( $_POST['start_date'] );
        $check_out_date = sanitize_text_field( $_POST['end_date'] ); 
        $adult_count = intval( $_POST['adult_count'] );
        $children_count = intval( $_POST['children_count'] );
        $company_id = intval( $_POST['company_id'] );
        //$api_key = sanitize_key( $_POST['api_key'] );

        $api_key = sanitize_option( 'minical_api_key', get_option('minical_api_key') );

        $is_ajax_wp = sanitize_text_field( $_POST['is_ajax_wp'] );

        $baseUrl = MHBP_MINICAL_API_URL;
        $xApiKey = $api_key;  
        $data = array(
        	'company_id' => $company_id,
        	'start_date' => $check_in_date,
        	'end_date' => $check_out_date,
        	'adult_count' => $adult_count,
        	'children_count' => $children_count,
        	'is_ajax_wp' => $is_ajax_wp
        );

        $output = wp_remote_post(
			esc_url_raw( $baseUrl.'/booking/check_room_type_availability' ),
			array(
				'sslverify' => FALSE,
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-KEY' => $xApiKey
					),
				'cookies' => array(),
				'body' => json_encode($data),
			)
		);

        $result = json_decode(json_encode($output), true);
        
		$resp = json_decode($result['body'], true);

        echo wp_json_encode(array('result' => $resp), true);
        die;
	}

	function mhbp_charge_calculation(){
		$view_data['data'] =  sanitize_post( $_POST['view_data'] );
		$view_data['rate_plan_id'] = intval( $_POST['rate_plan_id'] );

		$company_id = intval( $_POST['company_id'] );
        //$api_key = sanitize_key( $_POST['api_key'] );
        $api_key = sanitize_option( 'minical_api_key', get_option('minical_api_key') );
		$view_data['company_id'] = $company_id;

		$baseUrl = MHBP_MINICAL_API_URL; 
        $xApiKey = $api_key;

        $output = wp_remote_post(
			esc_url_raw( $baseUrl.'/booking/booking_engine_charge_calculation' ),
			array(
				'sslverify' => FALSE,
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-KEY' => $xApiKey
					),
				'cookies' => array(),
				'body' => json_encode($view_data),
			)
		);

        $result = json_decode(json_encode($output), true);
        
		$resp = json_decode($result['body'], true);

        echo wp_json_encode(array('result' => $resp), true);
        die;
	}

	function mhbp_book_room(){
		$data['form_data'] =  sanitize_post( $_POST['form_data'] );
		$data['rate_plan_id'] = intval( $_POST['rate_plan_id'] );
		$data['view_data'] =  sanitize_post( $_POST['view_data'] );
		$data['company_data'] =  sanitize_post( $_POST['company_data'] );
		$data['average_daily_rate'] = sanitize_text_field( $_POST['average_daily_rate'] );
        
		$data['public_url'] = MHBP_MINICAL_APP_URL;
       
		$company_id = intval( $_POST['company_id'] );
        //$api_key = sanitize_key( $_POST['api_key'] );
        $api_key = sanitize_option( 'minical_api_key', get_option('minical_api_key') );

        $data['company_id'] = $company_id;

		$get_booking_engine_settings = sanitize_option( 'booking_engine_settings_', get_option('booking_engine_settings_'.$company_id) );

    	$get_booking_engine_settings = json_decode($get_booking_engine_settings, true);

    	if(isset($get_booking_engine_settings['email_confirmation_for_booking_engine']) && $get_booking_engine_settings['email_confirmation_for_booking_engine']){
    		$data['send_confirmation_email'] = false;
    	} else {
    		$data['send_confirmation_email'] = true;
    	}

    	if(isset($get_booking_engine_settings['booking_engine_booking_status']) && $get_booking_engine_settings['booking_engine_booking_status']){
    		$data['booking_engine_booking_status'] = true;
    	} else {
    		$data['booking_engine_booking_status'] = false;
    	}

		$baseUrl = MHBP_MINICAL_API_URL;  
        $xApiKey = $api_key;

        $output = wp_remote_post(
			esc_url_raw( $baseUrl.'/booking/create_booking' ),
			array(
				'sslverify' => FALSE,
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-KEY' => $xApiKey
					),
				'cookies' => array(),
				'body' => json_encode($data),
			)
		);
        $result = json_decode(json_encode($output), true);
        
		$resp = json_decode($result['body'], true);

        echo wp_json_encode(array('result' => $resp), true);
        die;
	}

	function mhbp_get_customer_info_form(){

		$company_id = intval( $_POST['company_id'] );
        //$api_key = sanitize_key( $_POST['api_key'] );
        $api_key = sanitize_option( 'minical_api_key', get_option('minical_api_key') );
		$data['company_id'] = $company_id;

        $get_booking_engine_fields = sanitize_option( 'booking_engine_fields_', get_option('booking_engine_fields_'.$company_id) );
        $get_booking_engine_fields = json_decode($get_booking_engine_fields, true);

        $common_booking_engine_fields = json_decode(MHBP_COMMON_BOOKING_ENGINE_FIELDS, true);
        $get_common_booking_engine_fields = $get_booking_engine_fields;

        $booking_engine_fields = array();

        foreach($common_booking_engine_fields as $id => $name)
        {
            $is_required = 1;
            if ($id == MHBP_BOOKING_FIELD_NAME) {
                $is_required = 1;
            } else if ($get_common_booking_engine_fields && isset($get_common_booking_engine_fields[$id]) && isset($get_common_booking_engine_fields[$id]['is_required'])) {
                $is_required = $get_common_booking_engine_fields[$id]['is_required'];
            } else if ($id == MHBP_BOOKING_FIELD_POSTAL_CODE || $id == MHBP_BOOKING_FIELD_SPECIAL_REQUEST) {
                $is_required = 0;
            }

            $booking_engine_fields[] = array(
                'id' => $id,
                'field_name' => $name,
                'company_id' => $company_id,
                'show_on_booking_form'=> ($id == MHBP_BOOKING_FIELD_NAME) ? 1 : (($get_common_booking_engine_fields && isset($get_common_booking_engine_fields[$id]) && isset($get_common_booking_engine_fields[$id]['show_on_booking_form'])) ? $get_common_booking_engine_fields[$id]['show_on_booking_form'] : 1),
                'is_required' => $is_required
            );
        }

        if(count($booking_engine_fields) > 0) 
        {
            foreach ($booking_engine_fields as $key => $value) 
            {
                if($value['id'] == MHBP_BOOKING_FIELD_NAME){
                    $name = 'customer_name';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_EMAIL){
                    $name = 'email';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_PHONE){
                    $name = 'phone';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_ADDRESS){
                    $name = 'address';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_CITY){
                    $name = 'city';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_REGION){
                    $name = 'region';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_COUNTRY){
                    $name = 'country';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_POSTAL_CODE){
                    $name = 'postal_code';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                } else if($value['id'] == MHBP_BOOKING_FIELD_SPECIAL_REQUEST){
                    $name = 'special_requests';
                    $is_required = $value['is_required'] ? 'required' : '';
                    $show = $value['show_on_booking_form'] ? '' : 'hidden';
                }


                $field_name = ucfirst($value["field_name"]);

                if($is_required == "required"){
                    $is_required_span = '<span style="color:red;">*</span>';
                } else {
                    $is_required_span = '';
                }

                $data_error = esc_html( 'Please enter your '.strtolower($field_name) );
                
                if($value['id'] == MHBP_BOOKING_FIELD_SPECIAL_REQUEST){ 

                    $booking_form .= '<div class="form-group '. $show .'">
                                    <label for="customer-name" class="col-sm-3 control-label">'. $field_name . ' ' . $is_required_span . '
                                    </label>
                                    <div class="col-sm-9">
                                        <textarea 
                                            name="'.$name.'"
                                            class="input-form-control form-control"
                                            rows = "5"
                                            data-error="' .$data_error. '"
                                            ' .$is_required. '
                                        ></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>';
                } else {
                    $booking_form .= '<div class="form-group '. $show .'">
                                    <label for="customer-name" class="col-sm-3 control-label">'. $field_name . ' ' . $is_required_span . '
                                    </label>
                                    <div class="col-sm-9">
                                        <input 
                                            name="'.$name.'"
                                            class="input-form-control form-control"
                                            type="text"
                                            data-error="' .$data_error. '"
                                            ' .$is_required. '
                                            id="'.$value['id'].'"
                                        />
                                        <div id="error_'.$value['id'].'" class="help-block with-errors"></div>
                                    </div>
                                    </div>';
                } 
            } 

            $get_booking_engine_settings = sanitize_option( 'booking_engine_settings_', get_option('booking_engine_settings_'.$company_id) );
        	$get_booking_engine_settings = json_decode($get_booking_engine_settings, true);

        	if(isset($get_booking_engine_settings['store_cc_in_booking_engine']) && $get_booking_engine_settings['store_cc_in_booking_engine']){

        		$booking_form .= '<div class="form-group cc_details">
			                            <label for="birthday" class=" col-md-3 control-label">Credit card
			                                <span style="color:red;">*</span>
			                            </label>

			                            <div class="col-md-6">
			                                <input 
			                                    class="input-form-control form-control"
			                                    name="cc_number" 
			                                    type="text"
			                                    placeholder="•••• •••• •••• ••••"
			                                    data-error= "Please enter CC number" 
			                                    id="cc_number"
			                                    required
			                                />
			                                <div class="help-block with-errors"></div>
			                                <div id="error_cc_number" class="help-block with-errors"></div>
			                            </div>

			                            <div class="form-group col-md-3" >
			                                <input class="input-form-control form-control"
			                                       name="cc_expiry"
			                                       placeholder="MM / YY"
			                                       data-expiry="expiry"
			                                       maxlength="7"
			                                       autocomplete="false"
			                                       id="cc_expiry"
			                                       data-error= "Please enter CC Expiry Date" 
			                                       required
			                                    >

			                                <div id="error_cc_expiry" class="help-block with-errors"></div>
			                            </div>
			                        </div>
			                        <div class="form-group cc_details cvc_code">
			                            <label for="cvc" class="col-lg-3 control-label"> CVC 
			                                <span style="color:red;">*</span>
			                            </label>
			                            <div class="col-sm-3">
			                                <input class="hidden" type="password" />
			                                <input type="password"
			                                       class="input-form-control form-control"
			                                       name="cc_cvc"
			                                       data-cvc="cvc"
			                                       placeholder="***"
			                                       maxlength="4"
			                                       autocomplete="false"
			                                       id="cc_cvc"
			                                       data-error= "Please enter CVV number" 
			                                       required
			                                    >
			                                <div id="error_cc_cvc" class="help-block with-errors"></div>
			                            </div>
			                        </div>';
        	}

            $booking_form .= '<input type="button" id="book_room" value="Book Now" class="btn btn-success btn-lg pull-right"/>';
        }

        

        $result['booking_form'] = $booking_form;
        $result['store_cc_in_booking_engine'] = isset($get_booking_engine_settings['store_cc_in_booking_engine']) && $get_booking_engine_settings['store_cc_in_booking_engine'] ? 1 : 0;
        $result['status'] = true;

        echo wp_json_encode(array('result' => $result), true);
        die;
	}
}