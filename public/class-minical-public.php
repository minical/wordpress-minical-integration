<?php

require_once('partials/minical_account.php');
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
class Minical_Public {

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
		wp_enqueue_style( "bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "font-awesome-min", plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "jquery-ui", plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/minical-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/datepicker.css', array(), $this->version, 'all' );
    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_enqueue_script( "bootstrap", plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "jquery-ui", plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "bootbox", plugin_dir_url( __FILE__ ) . 'js/bootbox.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/minical-public.js', array( 'jquery' ), $this->version, false );
		?>
		<script type="text/javascript">
		    site_url = '<?php echo get_option('siteurl'); ?>';
		</script>
	<?php }

	function check_room_type_availability(){
		$check_in_date = $_POST['start_date'];
        $check_out_date = $_POST['end_date'];
        $adult_count = $_POST['adult_count'];
        $children_count = $_POST['children_count'];

        $baseUrl = BASE_URL;  
        $xApiKey = X_API_KEY;  
        $data = array(
        	'company_id' => COMPANY_ID,
        	'start_date' => $check_in_date,
        	'end_date' => $check_out_date,
        	'adult_count' => $adult_count,
        	'children_count' => $children_count
        );
        // print_r($data);
        $client = new ApiClientPublic($xApiKey, $baseUrl);               
        $output = $client->sendRequest('/booking/check_room_type_availability', 'POST', $data);
        
        $resp =  $output->data;

        echo json_encode(array('result' => $resp), true);
        die;
	}

	function charge_calculation(){
		$view_data['data'] = $_POST['view_data'];
		$view_data['rate_plan_id'] = $_POST['rate_plan_id'];
		$view_data['company_id'] = COMPANY_ID;

		$baseUrl = BASE_URL;  
        $xApiKey = X_API_KEY;

		$client = new ApiClientPublic($xApiKey, $baseUrl);               
        $output = $client->sendRequest('/booking/booking_engine_charge_calculation', 'POST', $view_data);
        $resp =  $output->data;

        echo json_encode(array('result' => $resp), true);
        die;
	}

	function book_room(){
		$data['form_data'] = $_POST['form_data'];
		$data['company_id'] = COMPANY_ID;
		$data['rate_plan_id'] = $_POST['rate_plan_id'];
		$data['view_data'] = $_POST['view_data'];
		$data['company_data'] = $_POST['company_data'];
		$data['average_daily_rate'] = $_POST['average_daily_rate'];

		$baseUrl = BASE_URL;  
        $xApiKey = X_API_KEY;

		$client = new ApiClientPublic($xApiKey, $baseUrl);               
        $output = $client->sendRequest('/booking/create_booking', 'POST', $data);
        $resp =  $output->data;

        echo json_encode(array('result' => $resp), true);
        die;
	}

	function get_customer_info_form(){

		$data['company_id'] = COMPANY_ID;

		$baseUrl = BASE_URL;  
        $xApiKey = X_API_KEY;

		$client = new ApiClientPublic($xApiKey, $baseUrl);               
        $output = $client->sendRequest('/booking/get_customer_info_form', 'POST', $data);
        $resp =  $output->data;

        echo json_encode(array('result' => $resp), true);
        die;
	}
}