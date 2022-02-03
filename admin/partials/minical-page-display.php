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
    .iframe_page{
        margin: 15px 0px;
    }

    .minical_auth_div{
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

<?php require_once('minical_account.php'); ?>

<?php   $minical_api_key = get_option('minical_api_key');
        $minical_company_id = get_option('minical_company_id'); 

        $current_page = $_GET['minical-page'];

        if(!$minical_api_key && !$minical_company_id && $current_page == ''){ ?>
            <div id="form_builder" class="margin-top-10">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="input-part">
                            <form class="form-horizontal" action="/action_page.php">
                                <div class="form-group">
                                    <div class="minical_auth_div">
                                        <button type="button" id="minical_login" class="btn btn-primary minical_auth" name="">Login with miniCal</button>
                                        <br><br> <b>OR</b>
                                        <br><br>
                                        <button type="button" id="minical_reg" class="btn btn-primary minical_auth" name="">Create new Account on miniCal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
<?php   } else { ?>

<div class="main-wrp form_creation_div clearfix">

    <div class="iframe_page">
        <iframe src="<?php echo MINICAL_APP_URL.$current_page; ?>" id="minical-wp-iframe" style="width: 98%; height: 800px;"></iframe>
    </div>
    
</div>
<?php } ?>

