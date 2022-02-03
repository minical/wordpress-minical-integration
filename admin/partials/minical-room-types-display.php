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
        $output = $client->sendRequest('/room_type/get_room_types', 'POST', $data);
        $room_types =  $output->data;

        $room_types = json_decode(json_encode($room_types, true),true);

 ?>

<div class="main-wrp form_creation_div clearfix">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2 class="text-center">Minical Room Types</h2>
        </div>
    </div>
</div>
<div style="padding:0;margin:0 auto;font-weight:200;width:100%!important">
    <table class="table table-hover" id="minical-room-types">
        <thead>
            <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Acronym</th>
                <th class="text-center">Show On Website</th>
                <th class="text-center">Min / Max Occupancy</th>
                <th class="text-center">Maximum Adults</th>
                <th class="text-center">Maximum Children</th>
            </tr>
        </thead>    
    <?php 
        if (isset($room_types) && $room_types):
            foreach ($room_types as $room_type) : ?>
                <tbody>
                    <tr>
                        <td class="text-center"><?php echo $room_type['name']; ?></td>
                        <td class="text-center"><?php echo $room_type['acronym']; ?></td>
                        <td class="text-center"><?php echo $room_type['can_be_sold_online'] == 1 ? 'Yes' : 'No' ; ?></td>
                        <td class="text-center"><?php echo $room_type['min_occupancy'].' / '.$room_type['max_occupancy']; ?></td>
                        <td class="text-center"><?php echo $room_type['max_adults']; ?></td>
                        <td class="text-center"><?php echo $room_type['max_children']; ?></td>
                    </tr>
                </tbody>
    <?php 
            endforeach;
            else: ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-center">
                        <h3>No room types found</h3>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

<?php 
endif;
    ?>
</table>
</div>
