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
   
    .minical_auth{
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


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div class="main-wrp form_creation_div clearfix">

    

    <div class="row">
        <div class="col-md-3"></div>
        <!-- <div class="col-md-6">
            <h2 class="text-center">Minical Setting</h2>
        </div> -->
    </div>

    <div id="form_builder" class="margin-top-10">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="input-part">
                    <form class="form-horizontal" action="/action_page.php">
                        
                        <div class="form-group">
                            
                            <div class="minical_auth">
                                <button type="button" class="btn btn-primary minical_login" name="">Login with miniCal</button>
                                <br><br> <b>OR</b>
                                <br><br>
                                <button type="button" class="btn btn-primary minical_reg" name="">Create new Account on miniCal</button>
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

