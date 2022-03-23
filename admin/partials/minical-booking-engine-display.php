<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wppb.me/
 * @since      1.0.0
 *
 * @package    Minical
 * @subpackage Minical/admin/partials
 */


?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<script type="text/javascript">
   var site_url = '<?php echo esc_url( get_option('siteurl') ); ?>';
</script>

<?php   

$minical_api_key = sanitize_key( get_option('minical_api_key') );
$minical_company_id = intval( get_option('minical_company_id') ); 
$current_page = isset($_GET['minical-page']) && $_GET['minical-page']  ? $_GET['minical-page'] : '';

$get_booking_engine_fields = sanitize_option( 'booking_engine_fields_', get_option('booking_engine_fields_'.MHBP_COMPANY_ID) );
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
        'company_id' => MHBP_COMPANY_ID,
        'show_on_booking_form'=> ($id == MHBP_BOOKING_FIELD_NAME) ? 1 : (($get_common_booking_engine_fields && isset($get_common_booking_engine_fields[$id]) && isset($get_common_booking_engine_fields[$id]['show_on_booking_form'])) ? $get_common_booking_engine_fields[$id]['show_on_booking_form'] : 1),
        'is_required' => $is_required
    );
}

$get_booking_engine_settings = sanitize_option( 'booking_engine_settings_', get_option('booking_engine_settings_'.MHBP_COMPANY_ID) );
$get_booking_engine_settings = json_decode($get_booking_engine_settings, true);

if($get_booking_engine_settings && count($get_booking_engine_settings) > 0) {

} else {
    $company_data = array(
        'allow_same_day_check_in'               => 1,
        'store_cc_in_booking_engine'            => 0,
        'booking_engine_booking_status'         => 0,
        'email_confirmation_for_booking_engine' => 0,
        'booking_engine_tracking_code'          => null
    );

    $setting_data = json_encode($company_data);

    add_option('booking_engine_settings_'.MHBP_COMPANY_ID, $setting_data);

    $booking_engine_settings = sanitize_option( 'booking_engine_settings_',get_option('booking_engine_settings_'.MHBP_COMPANY_ID) );
    $booking_engine_settings = json_decode($booking_engine_settings, true);

    $get_booking_engine_settings = $booking_engine_settings;
}

if($minical_api_key != '' && $minical_company_id != '' && $current_page == ''){ ?>

    <div class="main-wrp form_creation_div clearfix">
        <div class="row">
            <div class="col-md-12"><h2 class="head-wep"><?php esc_html_e( 'Minical Booking Engine Settings' ); ?></h2></div>  
        </div>

        <div id="form_builder" class="margin-top-10">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><span alt="online_reservation_work_properly" title="online_reservation_work_properly"><?php esc_html_e( 'Shortcode:' ); ?></span></h5>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-12">
                                <input type="text" class="form-control " name="booking-engine-shortcode" id="booking-engine-shortcode" autocomplete = "off" value="[minical-booking-form key='<?php echo esc_attr( MHBP_X_API_KEY ); ?>' id='<?php echo esc_attr( MHBP_COMPANY_ID ); ?>']" readonly>
                            </div>
                            <div class="booking_engine_shortocde_msg">
                                <strong><h6><?php esc_html_e( 'Use this shortcode on any page to accept bookings with Minical online booking engine.' ); ?></h6></strong>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><span alt="online_reservation_work_properly" title="online_reservation_work_properly"><?php esc_html_e( 'For online reservations to work properly:' ); ?></span></h5>
                        </div>
                        <div class="card-body">
                            <ul class="bullet-points">
                                <li>
                                    <?php esc_html_e( 'Create' ); ?> <a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/settings/rates/rate_plans' ); ?>"><?php esc_html_e( 'Rate Plans' ); ?></a><?php esc_html_e( ', and set availability &amp; rates.' ); ?>
                                </li>
                            <li>
                                <?php esc_html_e( 'Update your property information in' ); ?> <a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/settings/company/general' ); ?>"><?php esc_html_e( 'Property Settings' ); ?></a>
                            </li>
                            <li>
                                <?php esc_html_e( 'Set the appropriate rooms in' ); ?> <a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/settings/room_inventory/rooms' ); ?>"><?php esc_html_e( 'Room Settings ' ); ?></a><?php esc_html_e( "to 'can be sold online'." ); ?>
                            </li>
                        </ul>
                      </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><?php esc_html_e( 'Online booking engine fields:' ); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table id="booking-engine-fields" class="table">
                                <tr>
                                    <th><?php esc_html_e( 'Field name' ); ?></th>
                                    <th class="text-center"><?php esc_html_e( 'Show on booking form' ); ?></th>
                                    <th class="text-center"><?php esc_html_e( 'Is a required field' ); ?></th>
                                </tr>

                            <?php if(isset($booking_engine_fields)): 
                                foreach($booking_engine_fields as $booking_field) : ?>      
                                <tr class="booking-field-tr" id="<?php echo esc_attr( $booking_field['id'] ); ?>">
                                    <td>
                                        <input name="name" class="form-control" type="text" value="<?php echo esc_attr( $booking_field['field_name'] ); ?>" maxlength="45" style="width:250px" disabled/>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="show_on_booking_form" autocomplete="off"
                                                    <?php
                                                    if ($booking_field['show_on_booking_form'] == 1) {
                                                        echo esc_attr( 'checked="checked"' );
                                                    }
                                                    if ($booking_field['id'] == MHBP_BOOKING_FIELD_NAME) {
                                                        echo esc_attr( 'disabled checked="checked"' );
                                                    }
                                                    ?>
                                                />
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="is_required" autocomplete="off"
                                                    <?php
                                                        if ($booking_field['id'] == MHBP_BOOKING_FIELD_NAME) {
                                                            echo esc_attr( 'disabled checked="checked"' );
                                                        }    
                                                        if ($booking_field['is_required'] == 1) {
                                                            echo esc_attr( 'checked="checked"' );
                                                        }
                                                    ?>
                                                />
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else : ?> 
                                <h3><?php esc_html_e( 'No booking field have been found.' ); ?></h3>
                                <?php endif; ?>
                            </table>
                            <br />
                            <button id="save-all-booking-fields-button" class="btn btn-primary"><?php esc_html_e( 'Save All' ); ?></button>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                    <form class="form-horizontal" method="post" action="" autocomplete="off">
                        <div class="row">
                            <label for="website_uri" class="col-sm-4 col-form-label">
                                <p class="h6"><?php esc_html_e( 'Allow same day check-in' ); ?> </p>
                                <p class="help-block"><?php esc_html_e( "If this feature is disabled, online reservations' check-in date must be tomorrow or later." ); ?></p>
                            </label>
                            <div class="col-sm-8">
                                <select name="allow_same_day_check_in" class="form-select allow_same_day_check_in">
                                    <option value="1" <?php echo esc_attr( ($get_booking_engine_settings['allow_same_day_check_in'] == '1') ? 'SELECTED=SELECTED':'' ); ?> >
                                        <?php echo esc_attr( 'Enabled' ); ?>
                                    </option>
                                    <option value="0" <?php echo esc_attr( ($get_booking_engine_settings['allow_same_day_check_in'] == '0') ? 'SELECTED=SELECTED':'' ); ?> >
                                        <?php echo esc_attr( 'Disabled' ); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label for="website_uri" class="col-sm-4 col-form-label">
                                <p class="h6"><?php esc_html_e( 'Require credit card information' ); ?></p>
                                <p class="help-block"><?php esc_html_e( "To enable this feature, at least one  payment gateway must be enabled on Minical." ); ?></p>
                            </label>

                             <div class="col-sm-8">
                                <select name="store_cc_in_booking_engine" class="form-select store_cc_in_booking_engine">
                                    <option value="1" <?php echo esc_attr( ($get_booking_engine_settings['store_cc_in_booking_engine'] == '1') ? 'SELECTED=SELECTED' : '' ); ?> >
                                        <?php echo esc_attr( 'Enabled' ); ?>
                                    </option>
                                    <option value="0" <?php echo esc_attr( ($get_booking_engine_settings['store_cc_in_booking_engine'] == '0') ? 'SELECTED=SELECTED' : '' ); ?> >
                                        <?php echo esc_attr( 'Disabled' ); ?>
                                    </option>
                                </select>

                            </div> 
                        </div>
                        
                        <div class="row">
                            <label for="mark-reservation" class="col-sm-4 col-form-label">
                                <p class="h6"><?php esc_html_e( 'Mark reservations from booking engine' ); ?> </p>  
                            </label>
                            <div class="col-sm-8">
                                <select name="booking_engine_booking_status" class="form-select booking_engine_booking_status">
                                    <option value="1" <?php echo esc_attr( ($get_booking_engine_settings['booking_engine_booking_status'] == '1') ? 'SELECTED=SELECTED':'' ); ?> >
                                        <?php esc_html_e( 'Yes' ); ?>
                                    </option>
                                    <option value="0" <?php echo esc_attr( ($get_booking_engine_settings['booking_engine_booking_status'] == '0') ? 'SELECTED=SELECTED':'' ); ?> >
                                        <?php esc_html_e( 'No' ); ?>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <label for="mark-reservation" class="col-sm-4 col-form-label">
                                <p class="h6"><?php esc_html_e( 'Do not send email confirmation for Online Booking Engine' ); ?></p>
                            </label>
                            <div class="col-sm-8">
                                <input type="checkbox" name="email_confirmation_for_booking_engine" class="email_confirmation_for_booking_engine" <?php echo esc_attr( $get_booking_engine_settings['email_confirmation_for_booking_engine'] == 1 ? 'checked=checked' : '' );?> value="1" style="margin: 10px 0px;" />
                            </div>
                        </div>

                        <div class="row">
                            <label for="mark-reservation" class="col-sm-4 col-form-label">
                               <p class="h6"> <?php esc_html_e( 'Tracking/Analytics code' ); ?>
                                </p>
                               <p class="help-block"> <?php esc_html_e( 'It will be embedded inside head tag on booking engine pages.' ); ?></p>
                                
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control booking_engine_tracking_code" rows="5" name="booking_engine_tracking_code"><?php echo html_entity_decode($get_booking_engine_settings['booking_engine_tracking_code']); ?>
                                </textarea>
                            </div>
                        </div>

                        <div class="row" style="margin-top:5px">
                            <div class="col-sm-12 ">
                                <button id="save-all-booking-engine-setting" class="btn btn-primary"><?php esc_html_e( 'Update' ); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>

    <div class="main-wrp form_creation_div clearfix">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h3 class="text-center" style="margin-top: 20px;"><?php esc_html_e( 'You have to signup/login with minical first.' ); ?></h3>
            </div>
        </div>
    </div>
<?php } ?>
