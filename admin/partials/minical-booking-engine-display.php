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
        margin: 50px 115px;
        color: black;
    }
</style>

<script type="text/javascript">
    site_url = '<?php echo get_option('siteurl'); ?>';
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div class="main-wrp form_creation_div clearfix">

    

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2 class="text-center">Book Your Room</h2>
        </div>
    </div>

    <div id="form_builder" class="margin-top-10">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="input-part">
                    <form class="form-horizontal" action="">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Shortcode</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="booking-engine-shortcode" id="booking-engine-shortcode" autocomplete = "off" value="[minical-online-booking-engine]" readonly>
                            </div>
                            <div class="booking_engine_shortocde_msg">
                                <strong>Use this shortcode on any page to accept bookings with Minical online booking engine.</strong>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        
    </div>
    
</div>

