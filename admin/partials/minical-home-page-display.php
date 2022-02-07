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
    .start_guide {
        margin: 50px 50px;
    }
    .steps {
        margin-bottom: 15px;
    }


    /*****/

    *{
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 55%;
            margin: 5% auto;
            
        }
        .containt-box{
            background: white;
            margin-top: 5px;
            box-shadow: 0 0 5px dimgrey
        }
        .containt-box p{
            padding:2% 5% 3% 5%;
        }
        span{
            padding-right: 2%;
        }
        .main-heading{
            margin-bottom: 2%;
        }

    /*****/


</style>

<script type="text/javascript">
    var site_url = '<?php echo get_option('siteurl'); ?>';
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


<!-- <div class="start_guide">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-4">
            <h3 class="text-center">Your quick start guide</h3>
        </div>
        <div class="col-md-7"></div>
    </div>
                
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span alt="" title="">To start with Minical, please</span>:</h3>
                </div>
                <div class="panel-body">
                    <ul class="bullet-points">
                        <li class="steps">Step 1:  
                            <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app'; ?>">
                                Signup/login
                            </a> 
                            With Minical
                        </li>
                        <li class="steps">
                            Step 2:  Create <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app&minical-page=/settings/rates/rate_plans'; ?>">Rate Plans</a>, and set availability &amp; rates
                        </li>
                        <li class="steps">
                            Step 3:  Update your property information in <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app&minical-page=/settings/company/general'; ?>">Property Settings</a>
                        </li>
                        <li class="steps">
                            Step 4:  Set the appropriate rooms in <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app&minical-page=/settings/room_inventory/rooms'; ?>">Room Settings</a> to 'can be sold online'
                        </li>
                        <li class="steps">
                            Step 5:  Update your <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=online-booking-engine'; ?>">Online booking engine settings</a>
                        </li>
                        <li class="steps">
                            Step 6:  Copy shortcode and put on a page 
                        </li>
                        <li class="steps">
                            Step 7:  Test booking engine and start accepting bookings
                        </li>
                        <li class="steps">
                            Step 8:  Design to match with your theme
                        </li>
                    </ul>
              </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div> -->
     
<div class="container">
        <h3 class="main-heading">Your quick start guide</h3>
        <div class="containt-box">
            <p class="heading-one">To start with Minical, please following these steps:</p>
        </div>
        <div class="containt-box">
            <p><span>(1)</span><a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app'; ?>">
                                Signup / login
                            </a> 
                            with Minical</p>
            <p><span>(2)</span>Create <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app&minical-page=/settings/rates/rate_plans'; ?>">Rate Plans</a>, and set availability &amp; rates</p>
            <p><span>(3)</span>Update your property information in <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app&minical-page=/settings/company/general'; ?>">Property Settings</a></p>
            <p><span>(4)</span>Set the appropriate rooms in <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=minical-app&minical-page=/settings/room_inventory/rooms'; ?>">Room Settings</a> to 'can be sold online'</p>
            <p><span>(5)</span>Update your <a target="_blank" href="<?php echo home_url() . '/wp-admin/admin.php?page=online-booking-engine'; ?>">Online booking engine settings</a></p>
            <p><span>(6)</span>Copy shortcode from booking engine settings and put on a page</p>
            <p><span>(7)</span>Test booking engine and start accepting bookings</p>
            <p><span>(8)</span>Design to match with your theme</p>
        </div>
    </div>       
