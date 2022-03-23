<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wppb.me/
 * @since             1.0.0
 * @package           Minical
 *
 * @wordpress-plugin
 * Plugin Name:       Minical
 * Plugin URI:        https://minical.io
 * Description:       Minical is an opensource Property Management System (PMS) and Online Booking System for hotels.
 * Version:           1.0.1
 * Author:            Mradul Jain
 * Author URI:        https://minical.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       minical
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
// include 'classes/db_manager.php';


// function check_database_version() {
//   $db_manager = new STE_DB_Manager();
//   $db_manager->migrate();
// }

if ( ! defined( 'WPINC' ) ) {
	die;
}
// check_database_version();

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MHBP_PLUGIN_NAME_VERSION', '1.0.1' );
define( 'MHBP_STE_PLUGIN_DB_VERSION', '12' );
 
// define( 'MHBP_MINICAL_API_URL', 'https://demoapi.minical.io/v2' );
// define( 'MHBP_MINICAL_APP_URL', 'https://demo.minical.io' );

//define( 'MHBP_MINICAL_API_URL', 'http://localhost/minical/api/v2' );
//define( 'MHBP_MINICAL_APP_URL', 'http://localhost/minical/public' );

define( 'MHBP_MINICAL_API_URL', 'https://api.minical.io/v2' );
define( 'MHBP_MINICAL_APP_URL', 'https://app.minical.io' );

$x_api_key = sanitize_key( get_option('minical_api_key') );
define( 'MHBP_X_API_KEY', $x_api_key );

$company_id = intval( get_option('minical_company_id') );
define('MHBP_COMPANY_ID', $company_id);

define( 'MHBP_BOOKING_FIELD_NAME', '-1' );
define( 'MHBP_BOOKING_FIELD_EMAIL', '-2' );
define( 'MHBP_BOOKING_FIELD_PHONE', '-3' );
define( 'MHBP_BOOKING_FIELD_ADDRESS', '-4' );
define( 'MHBP_BOOKING_FIELD_CITY', '-5' );
define( 'MHBP_BOOKING_FIELD_REGION', '-6' );
define( 'MHBP_BOOKING_FIELD_COUNTRY', '-7' );
define( 'MHBP_BOOKING_FIELD_POSTAL_CODE', '-8' );
define( 'MHBP_BOOKING_FIELD_SPECIAL_REQUEST', '-9' );

define( 'MHBP_COMMON_BOOKING_ENGINE_FIELDS',
        json_encode(array(
            MHBP_BOOKING_FIELD_NAME => 'Full Name',
            MHBP_BOOKING_FIELD_EMAIL => 'Email',
            MHBP_BOOKING_FIELD_PHONE => 'Phone',
            MHBP_BOOKING_FIELD_ADDRESS => 'Address',
            MHBP_BOOKING_FIELD_CITY => 'City',
            MHBP_BOOKING_FIELD_REGION => 'State Region/Province',
            MHBP_BOOKING_FIELD_COUNTRY => 'Country',
            MHBP_BOOKING_FIELD_POSTAL_CODE => 'Zip/Postal Code',
            MHBP_BOOKING_FIELD_SPECIAL_REQUEST => 'Special Requests'
        ))
    );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-form-builder-form-activator.php
 */
function mhbp_activate_minical() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-minical-activator.php';
	MHBP_Minical_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-form-builder-form-deactivator.php
 */
function mhbp_deactivate_minical() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-minical-deactivator.php';
	MHBP_Minical_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'mhbp_activate_minical' );
register_deactivation_hook( __FILE__, 'mhbp_deactivate_minical' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-minical.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function mhbp_run_minical() {
    $plugin = new MHBP_Minical();
	$plugin->run();


}
mhbp_run_minical();

function mhbp_minical_online_booking_engine( $atts ) {
	$room_types_html = '';
	$user_info_html = '';
	$thank_you_page_html = '';
	$html = '<style>

			body {
			    font-size: 15px !important;
			}
			#minical-select-dates-rooms {
			    background: rgba(255,255,255,0.65);
			    padding: 40px;
			    float: none !important;
			    margin: auto;
			    width: 536px;
			    max-width: 90%;
			    text-align: center;
			}
			#minical-select-dates-rooms #booking-form ul li {
			    height: 32px;
			    line-height: 1;
			    position: relative;
			    -webkit-box-sizing: border-box;
			    box-sizing: border-box;
			    vertical-align: middle;
			    margin-bottom: 10px;
			    clear: both;
			}
			#booking-form ul {
			    list-style: none;
			    margin: 0 !important;
			    padding: 0 !important;
			}
			#minical-select-dates-rooms #booking-form input, #minical-select-dates-rooms #booking-form select {
			    width: 52%;
			    height: 32px;
			    float: right;
			    padding: 0 5px;
			    margin: 0;
			    border: 1px solid #c6c6c6;
			    border-radius: 2px;
			    font-weight: normal;
			    font-weight: 400;
			    font-size: 15px;
			    text-rendering: optimizeLegibility;
			    color: #545454;
			}
			#minical-select-dates-rooms #booking-form input[type="button"] {
			    background: #fea116 !important;
			    line-height: 0px;
			    color: #fff !important;
			    font-size: 15px;
			    text-transform: uppercase;
			    border: none !important;
			    outline: 6px solid rgba(26,89,135,0.2) !important;
			    outline-offset: -6px;
			    border-radius: 0 !important;
			    padding: 28px 0;
			    width: 100%;
			    margin: 12px auto 0 auto;
			    transition: all 0.5s linear;
			    -webkit-transition: all 0.5s linear;
			}
			#minical-select-dates-rooms #booking-form label {
			    color: #545454;
			    text-rendering: optimizeLegibility;
			    width: 44%;
			    height: 32px;
			    float: left;
			    text-align: right;
			    line-height: 1;
			    padding-top: 10px;
			    font-size: 15px;
			    margin-bottom: 6px;
			}
			#minical-select-dates-rooms #booking-form select {
			    color: #545454;
			    margin-bottom: 10px;
			}
			#minical-show-reservations, #minical-book-reservation, #minical-booking-engine-thankyou {
				max-width: 100% !important;
				display: none;
			}
			.room-type-img {
				max-width: 126px !important;
				height: 126px !important;
				margin-bottom: 0px !important;
			}
			.rt-small-img {
				display: inline-flex;
				padding: 0px 1px;
			}
			
			.room-type-small-img {
				max-width: 63px !important;
			    height: 65px !important;
			    padding: 5px 0px;
			    margin-bottom: -5px !important;
			}
			.panel {
			    margin-bottom: 20px;
			    background-color: #fff;
			    border: 1px solid transparent;
			    border-radius: 4px;
			    -webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
			    box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
			}
			.panel-rate-plan-listing {
			    border-radius: 0;
			    box-shadow: 0px 0px 3px rgb(200 206 210 / 50%);
			    border-color: #eaeaea;
			}
			.control-label {
				text-align: center !important;
    			font-size: 15px !important;
			}
			.input-form-control {
				padding: 6px 12px !important;
				line-height: 1.42857143 !important;
				color: #555 !important;
				border: 1px solid #ccc !important;
			    border-radius: 4px !important;
			}
			.start_over {
				padding: 5px 10px;
    			text-decoration: none !important;
			}
			.btn-primary {
			    color: #fff !important;
			    background-color: #286090 !important;
			}
			.thank_you_page {
				text-align: center;
    			font-size: 18px;
			}
			#minical-show-rooms{
				padding-top: 20px;
		    }
			</style>';
	
	$html .= '<div id="minical-select-dates-rooms" class="column small-12 medium-8" data-company_id="'.$atts['id'].'" data-api_key="'.$atts['key'].'"><form action="" method="post" target="_blank" id="booking-form">
	<ul>
		<li>
			<label for="check-in-date" >Check-in Date</label>
			<input class="check-in-date" autocomplete="off" id="check_in_date" name="check-in-date" size="13" type="text" value="" placeholder="Enter Check-in date">
		</li>
		<li>
			<label for="check-out-date" >Check-out Date</label>
			<input class="check-out-date" autocomplete="off" id="check_out_date" name="check-out-date" size="13" type="text" value="" placeholder="Enter Check-out date">
		</li>
		<li>
			<label id="adult_count" for="adult_count">Adults</label>
			<select name="adult_count" style="display:inline;">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			</select>
			
			<label for="children_count" id="children_count">Children</label> 
			<select name="children_count" style="display:inline;"> <option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>			
		</li></ul>
		<input name="number-of-rooms" value="1" hidden="hidden">
		 <input type="button" name="submit" id="find_rooms" value="Search"> </form></div>

		';

	$room_types_html .= '<div id="minical-show-reservations" class="" name="'.$atts['id'].'">
							<div class=""><h2>Select your accommodation</h2></div>
							<div class="col-md-12" id="minical-show-rooms">
							</div>
						</div>';
	
	$user_info_html .= '<div id="minical-book-reservation" class="" name="'.$atts['id'].'">
							<div class="col-md-12" id="minical-book-room">
							</div>
						</div>';

	$thank_you_page_html .= '<div id="minical-booking-engine-thankyou" class="" name="'.$atts['id'].'">
								<div class="col-md-12 thank_you_page" id="">
									<h2 class="">Thank you for staying with us</h2>
									<br />
									The online reservation has been requested to your company. Please check your email inbox for a booking confirmation email.
								</div>
							</div>';


	return $html.$room_types_html.$user_info_html.$thank_you_page_html; 
}

$shortcode = "minical-booking-form";

add_shortcode( $shortcode, 'mhbp_minical_online_booking_engine' );

?>
