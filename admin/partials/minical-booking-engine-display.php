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
<!--<div class="drag-drop-form">test</div>-->
<!-- jQuery Modal -->

<style>
    .margin-top-10 {
        margin-top: 60px !important;
    }

    .booking_engine_shortocde_msg{
        font-size: 12px;
        margin: 40px 15px;
        color: black;
    }
</style>

<script type="text/javascript">
    site_url = '<?php echo get_option('siteurl'); ?>';
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<?php   $get_booking_engine_fields = get_option('booking_engine_fields_'.COMPANY_ID);
        $get_booking_engine_fields = json_decode($get_booking_engine_fields, true);

        $common_booking_engine_fields = json_decode(COMMON_BOOKING_ENGINE_FIELDS, true);
        $get_common_booking_engine_fields = $get_booking_engine_fields;

        $booking_engine_fields = array();

        foreach($common_booking_engine_fields as $id => $name)
        {
            $is_required = 1;
            if ($id == BOOKING_FIELD_NAME) {
                $is_required = 1;
            } else if ($get_common_booking_engine_fields && isset($get_common_booking_engine_fields[$id]) && isset($get_common_booking_engine_fields[$id]['is_required'])) {
                $is_required = $get_common_booking_engine_fields[$id]['is_required'];
            } else if ($id == BOOKING_FIELD_POSTAL_CODE || $id == BOOKING_FIELD_SPECIAL_REQUEST) {
                $is_required = 0;
            }

            $booking_engine_fields[] = array(
                'id' => $id,
                'field_name' => $name,
                'company_id' => COMPANY_ID,
                'show_on_booking_form'=> ($id == BOOKING_FIELD_NAME) ? 1 : (($get_common_booking_engine_fields && isset($get_common_booking_engine_fields[$id]) && isset($get_common_booking_engine_fields[$id]['show_on_booking_form'])) ? $get_common_booking_engine_fields[$id]['show_on_booking_form'] : 1),
                'is_required' => $is_required
            );
        }

        $get_booking_engine_settings = get_option('booking_engine_settings_'.COMPANY_ID);
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

            add_option('booking_engine_settings_'.COMPANY_ID, $setting_data);

            $booking_engine_settings = get_option('booking_engine_settings_'.COMPANY_ID);
            $booking_engine_settings = json_decode($booking_engine_settings, true);

            $get_booking_engine_settings = $booking_engine_settings;
        }

?>

<div class="main-wrp form_creation_div clearfix">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2 class="text-center">Book Your Room</h2>
        </div>
    </div>

    <div id="form_builder" class="margin-top-10">
        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span alt="online_reservation_work_properly" title="online_reservation_work_properly">Shortcode</span>:</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="booking-engine-shortcode" id="booking-engine-shortcode" autocomplete = "off" value="[minical-online-booking-engine]" readonly>
                        </div>
                        <div class="booking_engine_shortocde_msg">
                            <strong>Use this shortcode on any page to accept bookings with Minical online booking engine.</strong>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span alt="online_reservation_work_properly" title="online_reservation_work_properly">For online reservations to work properly</span>:</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="bullet-points">
                            <li>
                                Create <a href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-plugin&minical-page=/settings/rates/rate_plans'; ?>">Rate Plans</a>, and set availability &amp; rates.
                            </li>
                        <li>
                            Update your property information in <a href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-plugin&minical-page=/settings/company/general'; ?>">Property Settings</a>
                        </li>
                        <li>
                            Set the appropriate rooms in <a href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-plugin&minical-page=/settings/room_inventory/rooms'; ?>">Room Settings</a> to 'can be sold online'.
                        </li>
                    </ul>
                  </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo 'Online booking engine fields'; ?>:</h3>
                    </div>
                    <div class="panel-body">
                        <table id="booking-engine-fields" class="table">
                            <tr>
                                <th><?php echo 'Field name'; ?></th>
                                <th class="text-center"><?php echo 'Show on booking form'; ?></th>
                                <th class="text-center"><?php echo 'Is a required field'; ?></th>
                            </tr>

                        <?php if(isset($booking_engine_fields)): 
                            foreach($booking_engine_fields as $booking_field) : ?>      
                            <tr class="booking-field-tr" id="<?php echo $booking_field['id']; ?>">
                                <td>
                                    <input name="name" class="form-control" type="text" value="<?php echo $booking_field['field_name']; ?>" maxlength="45" style="width:250px" disabled/>
                                </td>
                                <td class="text-center">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="show_on_booking_form" autocomplete="off"
                                                <?php
                                                if ($booking_field['show_on_booking_form'] == 1) {
                                                    echo 'checked="checked"';
                                                }
                                                if ($booking_field['id'] == BOOKING_FIELD_NAME) {
                                                    echo 'disabled checked="checked"';
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
                                                    if ($booking_field['id'] == BOOKING_FIELD_NAME) {
                                                        echo 'disabled checked="checked"';
                                                    }    
                                                    if ($booking_field['is_required'] == 1) {
                                                        echo 'checked="checked"';
                                                    }
                                                ?>
                                            />
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else : ?> 
                            <h3><?php echo 'No booking field have been found.'; ?></h3>
                            <?php endif; ?>
                        </table>
                        <br />
                        <button id="save-all-booking-fields-button" class="btn btn-primary"><?php echo 'Save All'; ?></button>
                    </div>
                </div>

                <form class="form-horizontal" method="post" action="" autocomplete="off">
                    <div class="form-group">
                        <label for="website_uri" class="col-sm-4 control-label">
                            <?php echo 'Allow same daye checkins'; ?>
                            <p class="help-block h6"><?php echo "If this feature is disabled, online reservations' check-in date must be tomorrow or later"; ?></p>
                        </label>
                        <div class="col-sm-8">
                            <select name="allow_same_day_check_in" class="form-control allow_same_day_check_in">
                                <option value="1" <?php echo ($get_booking_engine_settings['allow_same_day_check_in'] == '1')?'SELECTED=SELECTED':''; ?> >
                                    <?php echo 'Enabled'; ?>
                                </option>
                                <option value="0" <?php echo ($get_booking_engine_settings['allow_same_day_check_in'] == '0')?'SELECTED=SELECTED':''; ?> >
                                    <?php echo 'Disabled'; ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="website_uri" class="col-sm-4 control-label">
                            <?php echo 'Require credit card information'; ?>
                            <p class="help-block h6"></p>
                        </label>

                         <div class="col-sm-8">
                            <select name="store_cc_in_booking_engine" class="form-control store_cc_in_booking_engine">
                                <option value="1" <?php echo ($get_booking_engine_settings['store_cc_in_booking_engine'] == '1') ? 'SELECTED=SELECTED' : ''; ?> >
                                    <?php echo 'Enabled'; ?>
                                </option>
                                <option value="0" <?php echo ($get_booking_engine_settings['store_cc_in_booking_engine'] == '0') ? 'SELECTED=SELECTED' : ''; ?> >
                                    <?php echo 'Disabled'; ?>
                                </option>
                            </select>

                        </div> 
                    </div>
                    
                    <div class="form-group">
                        <label for="mark-reservation" class="col-sm-4 control-label">
                            <?php echo 'Mark reservations from booking engine'; ?>
                            <p class="help-block h6"></p>
                        </label>
                        <div class="col-sm-8">
                            <select name="booking_engine_booking_status" class="form-control booking_engine_booking_status">
                                <option value="1" <?php echo ($get_booking_engine_settings['booking_engine_booking_status'] == '1')?'SELECTED=SELECTED':''; ?> >
                                    <?php echo 'Yes'; ?>
                                </option>
                                <option value="0" <?php echo ($get_booking_engine_settings['booking_engine_booking_status'] == '0')?'SELECTED=SELECTED':''; ?> >
                                    <?php echo 'No'; ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mark-reservation" class="col-sm-4 control-label">
                            <?php echo 'Do Not Send Email Confirmation For Online Booking Engine'; ?>
                            <p class="help-block h6"></p>
                        </label>
                        <div class="col-sm-8">
                            <input type="checkbox" name="email_confirmation_for_booking_engine" class="email_confirmation_for_booking_engine" <?=$get_booking_engine_settings['email_confirmation_for_booking_engine'] == 1 ? 'checked=checked' : '';?> value="1" style="margin: 10px 0px;" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mark-reservation" class="col-sm-4 control-label">
                            <?php echo 'Tracking/Analytics code'; ?>
                            <p class="help-block h6">
                                <?php echo 'It will be embedded inside head tag on booking engine pages'; ?>
                            </p>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control booking_engine_tracking_code" rows="5" name="booking_engine_tracking_code"><?php echo html_entity_decode($get_booking_engine_settings['booking_engine_tracking_code']); ?>
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <button id="save-all-booking-engine-setting" class="btn btn-light"><?php echo 'Update'; ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</div>

