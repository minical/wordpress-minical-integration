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
    $api_key = sanitize_key( get_option('minical_api_key') );
    $minical_company_id = intval( get_option('minical_company_id') );     
    $minical_company_name = sanitize_option( 'minical_company_name', get_option('minical_company_name') );     
?>

<div class="main-wrp form_creation_div clearfix">

    <div class="row">
        <div class="col-md-12">
            <h2 class="head-wep"><?php esc_html_e( 'Connect Minical Property' ); ?></h2>
        </div>
    </div>

    <div id="form_builder" class="card margin-top-10">
        <div class="row">
            <div class="col-md-6">
                <div class="input-part">
                    <form class="form-horizontal" action="">
                        
                        <div class="row">
                            <label class="col-form-label col-sm-3 h6" for="api_key"><?php esc_html_e( 'API Key' ); ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="minical_api_key" id="minical_api_key" autocomplete = "off" value="<?php echo esc_attr( $api_key ? $api_key : '' ); ?>" placeholder="Enter your minical api key" required>
                            </div>
                        </div>
                        <div class="row  <?php echo esc_attr( empty($minical_company_id) ? 'hidden' : '' ); ?>"  style="margin-top: 5px;">
                            <label class="col-form-label col-sm-3 h6" for="company_id"><?php esc_html_e( 'Company ID' ); ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="minical_company_id" id="minical_company_id" autocomplete = "off" value="<?php echo  esc_attr( $minical_company_id ? $minical_company_id : '' ); ?>" readonly>
                            </div>
                            <label class="col-form-label col-sm-3 h6" for="company_name"><?php esc_html_e( 'Company Name' ); ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="minical_company_name" id="minical_company_name" autocomplete = "off" value="<?php echo  esc_attr( $minical_company_name ? $minical_company_name : '' ); ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="api_key_submit_btn">
                                <button type="button" class="btn btn-primary update_minical_api_key" name=""><?php esc_html_e( 'Save Change' ); ?></button>
                            </div>
                       </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        
    </div>
    
</div>

