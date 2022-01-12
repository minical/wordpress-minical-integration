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

require_once('minical_account.php');

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!--<div class="drag-drop-form">test</div>-->
<!-- jQuery Modal -->


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<?php 
        $baseUrl = BASE_URL;  
        $xApiKey = X_API_KEY;  
        $data = array('company_id' => COMPANY_ID);
        $client = new ApiClient($xApiKey, $baseUrl);               
        $output = $client->sendRequest('/booking/show_bookings', 'POST', $data);
        $bookings =  $output->data;

        $bookings = json_decode(json_encode($bookings, true),true);

 ?>

<div class="main-wrp form_creation_div clearfix">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2 class="text-center">Minical Bookings</h2>
        </div>
    </div>
</div>
<div style="padding:0;margin:0 auto;font-weight:200;width:100%!important">
    <table class="table table-hover" id="minical-bookings">
        <thead>
            <tr>
                <th class="text-center">Booking ID</th>
                <th class="text-center">Room</th>
                <th class="text-center">Check-In</th>
                <th class="text-center">Check-Out</th>
                <th>Customer</th>
            </tr>
        </thead>     
    <?php 
        if (isset($bookings) && $bookings):
            foreach ($bookings as $booking) : 
                if (isset($booking['booking_id'])):
    
    ?>
                <tbody>
                    <tr class='booking <?php 
                        echo "state".$booking['state'];
                    ?>' data-booking-id='<?php echo $booking['booking_id']; ?>'

                    <?php 
                        if (isset($booking['color']))
                        {
                            if ($booking['color'])
                            {
                                echo " style='background-color: #".$booking['color'].";' "; 
                            }
                        }
                    ?>

                    >
                        
                        <td class="text-center"><?php echo $booking['booking_id']; ?></td>
                        <td class="text-center"><?php echo $booking['room_name'] ? $booking['room_name'] : 'Not Assigned'; ?></td>
                        <td class="text-center"><?php echo $booking['check_in_date']; ?></td>
                        <td class="text-center"><?php echo $booking['check_out_date']; ?></td>
                        <td><?php 
                            $customer_name = isset($booking['customer_name'])?$booking['customer_name']:'';
                            $guest_name = isset($booking['guest_name'])?$booking['guest_name']:'';
                            $number_of_staying_guests = isset($booking['guest_count'])?$booking['guest_count']:'';
                            
                            //$this->load->helper('customer_name');
                            echo $customer_name;
                        ?></td>
                    </tr>
                </tbody>
                
    <?php 
                endif;
            endforeach;
            else: ?>
                <tr class='booking' data-booking-id=''>
                    <td></td>
                    <td></td>
                    <td class="text-center">
                        <h3>No bookings found</h3>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

<?php 
endif;
    ?>
</table>
</div>
