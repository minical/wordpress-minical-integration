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
    .form_builder .nav-sidebar {
        width: 100% !important;
        padding: 8px 0 !important;
        border-right: 1px solid #ddd !important;
    }

    .form_builder .nav-sidebar a {
        color: #333 !important;
        -webkit-transition: all 0.08s linear !important;
        -moz-transition: all 0.08s linear !important;
        -o-transition: all 0.08s linear !important;
        transition: all 0.08s linear !important;
        -webkit-border-radius: 4px 0 0 4px !important;
        -moz-border-radius: 4px 0 0 4px !important;
        border-radius: 4px 0 0 4px !important;
    }

    .form_builder .nav-sidebar .active a {
        cursor: default !important;
        background-color: #428bca !important;
        color: #fff !important;
        text-shadow: 1px 1px 1px #666 !important;
    }

    .form_builder .nav-sidebar .active a:hover {
        background-color: #428bca !important;
    }

    .form_builder .nav-sidebar .text-overflow a,
    .form_builder .nav-sidebar .text-overflow .media-body {
        white-space: nowrap !important;
        overflow: hidden !important;
        -o-text-overflow: ellipsis !important;
        text-overflow: ellipsis !important;
    }
    /* Right-aligned sidebar */

    .form_builder .nav-sidebar.pull-right {
        border-right: 0 !important;
        border-left: 1px solid #ddd !important;
    }

    .form_builder .nav-sidebar.pull-right a {
        -webkit-border-radius: 0 4px 4px 0 !important;
        -moz-border-radius: 0 4px 4px 0 !important;
        border-radius: 0 4px 4px 0 !important;
    }

    .form_builder ul {
        font-size: 18px !important;
        display: block !important;
    }

    .form_builder ul li {
        padding: 10px !important;
        cursor: pointer !important;
        padding: 18px 12px !important;
    }

    .form_builder ul li a {
        text-decoration: none !important;
        cursor: pointer !important;
    }

    .form_builder_field_preview {
        padding: 10px !important;
        width:55% !important;
    }

    .form_builder_field {
        padding: 10px !important;
        width:55% !important;
    }

    .form_builder_field:hover {
        cursor: pointer;
        background: #e5fbd1 !important;
    }

    .form_builder .bal_builder {
        padding: 0 !important;
    }

    .margin-top-10 {
        margin-top: 60px !important;
    }

    .form_builder_field:hover button.remove_bal_field {
        visibility: visible;
    }

    .form-radio-field {
        margin-right: 8px !important;
    }

    .form-checkbox-field {
        margin-right: 8px !important;
    }

    .placeholder {
        width: 100% !important;
        background-color: #bfb !important;
        border: 1px dashed #666 !important;
        margin-bottom: 5px !important;
    }

    .form_builder_area {
        width: 100% !important;
        min-height: 50px !important;
        position: absolute
    }

    .api_key_submit_btn{
        margin: 50px 115px;
    }

    #api_key_updation{
        font-size: 20px;
        margin: 10px 100px;
        color: green;
    }


</style>

<script type="text/javascript">
    var site_url = '<?php echo get_option('siteurl'); ?>';
</script>

<?php require_once('minical_account.php'); ?>

<?php   $api_key = get_option('minical_api_key');

        $minical_company_id = get_option('minical_company_id'); 

        if(empty($minical_company_id) && !empty($api_key)){
            $baseUrl = BASE_URL;  
            $xApiKey = X_API_KEY;  
            $data = array('x_api_key' => $xApiKey);
            $client = new ApiClient($xApiKey, $baseUrl);               
            $output = $client->sendRequest('/company/get_company_id', 'POST', $data);
            $company_id =  $output->data;

            if(!empty($company_id))
                add_option( 'minical_company_id', $company_id);

            $minical_company_id = get_option('minical_company_id'); 
        }

?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div class="main-wrp form_creation_div clearfix">

    

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2 class="text-center">Minical Setting</h2>
        </div>
    </div>

    <div id="form_builder" class="margin-top-10">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="input-part">
                    <form class="form-horizontal" action="/action_page.php">
                        <div class="updated_base gform_editor_status" id="api_key_updation" style="display: none;">
                            <strong>API Key updated successfully.</strong>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">API Key</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="minical_api_key" id="minical_api_key" autocomplete = "off" value="<?php echo $api_key ? $api_key : ''; ?>" placeholder="Enter your minical api key" required>
                            </div>
                            <div class="api_key_submit_btn">
                                <button type="button" class="btn btn-success update_minical_api_key" name="">Update</button>
                            </div>
                        </div>

                        <div class="form-group minical_company_id_div <?php echo empty($minical_company_id) ? 'hidden' : ''; ?>" >
                            <label class="control-label col-sm-2" for="name">Company ID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="minical_company_id" id="minical_company_id" autocomplete = "off" value="<?php echo $minical_company_id ? $minical_company_id : ''; ?>" readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        
    </div>
    
</div>

