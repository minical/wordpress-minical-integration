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
        $baseUrl = MINICAL_API_URL;
        $xApiKey = X_API_KEY;  
        $data = array('company_id' => COMPANY_ID);
        $client = new ApiClient($xApiKey, $baseUrl);              
        $output = $client->sendRequest('/room_type/get_rooms', 'POST', $data);
        $rooms =  $output->data;

        $rooms = json_decode(json_encode($rooms, true),true);

 ?>

<div class="main-wrp form_creation_div clearfix">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2 class="text-center">Minical Rooms</h2>
        </div>
    </div>
</div>
<div style="padding:0;margin:0 auto;font-weight:200;width:100%!important">
    <table class="table table-hover" id="minical-rooms">
        <thead>
            <tr>
                <th class="text-center">Room Name</th>
                <th class="text-center">Room Type</th>
                <th class="text-center">Can be Sold Online</th>
            </tr>  
        </thead>     
    <?php 
        if (isset($rooms) && $rooms):
            foreach ($rooms as $room) : ?>
                <tbody>
                    <tr>
                        <td class="text-center"><?php echo $room['room_name']; ?></td>
                        <td class="text-center"><?php echo $room['room_type_name']; ?></td>
                        <td class="text-center"><?php echo $room['can_be_sold_online'] == 1 ? 'Yes' : 'No' ; ?></td>
                    </tr>
                </tbody>
    <?php 
            endforeach;
            else: ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-center">
                        <h3>No rooms found</h3>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

<?php 
endif;
    ?>
</table>
</div>