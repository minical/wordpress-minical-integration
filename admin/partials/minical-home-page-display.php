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


$api_key = sanitize_key( get_option('minical_api_key') );
$minical_company_id = intval( get_option('minical_company_id') );     
$minical_company_name = sanitize_option( 'minical_company_name', get_option('minical_company_name') );
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<script type="text/javascript">
    var site_url = '<?php echo esc_url( get_option('siteurl') ); ?>';
</script>
 <div class="minical-firstsetup-wrap">
    <div class="minical-firstsetup-container">
        <div class="minical-firstsetup-head">
            <h3><?php esc_html_e( 'Minical Hotel Booking Plugin ' ); ?></h3>
           
        </div>

        <div class="minical-body <?php echo esc_attr( empty($minical_company_id) ? '' : 'hidden' ); ?>">
           
                 <h5><?php esc_html_e( 'Initial Setup' ); ?></h5>
                 <h5><?php esc_html_e( 'Complete the configuration of the following tasks to get start with Minical.' ); ?></h5>
                 <h6><?php esc_html_e( 'Step 1: Signup/Login with Minical -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL ); ?></a></h6>  
                 <h6><?php esc_html_e( 'Step 2: Get API access Key -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/settings/api_access' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/settings/api_access' ); ?></a></h6>
                 <h6><?php esc_html_e( 'Step 3: Configure API Key -> ' ); ?><a href="<?php echo esc_url( home_url() . '/wp-admin/admin.php?page=minical-app' ); ?>"><?php esc_html_e( home_url() . '/wp-admin/admin.php?page=minical-app' ); ?></a></h6>
                
            
        </div>
        <div class="minical-body-connected <?php echo esc_attr( empty($minical_company_id) ? 'hidden' : '' ); ?>" > 
            <h5><?php esc_html_e( 'Connected with minical' ); ?></h5>
            <h6><?php esc_html_e( 'Property Name: ' ); ?> <a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL ); ?>"> <?php echo esc_html($minical_company_name); ?></a> <?php echo esc_html('( Id: '.$minical_company_id.' )');?> <a href="javascript:void(0)" class="disconnect_minical" data-company-id="<?php echo esc_attr( $minical_company_id ); ?>"><?php esc_html_e( 'Disconnect with minical ' ); ?> </a></h6>  
            
         </div>

         <div class="minical-body">
           <h5><?php esc_html_e( 'Manage minical property' ); ?></h5>  
            <h6><?php esc_html_e( 'Rate Plans -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/settings/rates/rate_plans' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/settings/rates/rate_plans' ); ?></a></h6> 
            <h6><?php esc_html_e( 'Property Settings -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/settings/company/general' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/settings/company/general' ); ?></a></h6>
            <h6><?php esc_html_e( 'Room Settings -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/settings/room_inventory/rooms' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/settings/room_inventory/rooms' ); ?></a></h6> 
         </div>
         <div class="minical-body">
          <h5><?php esc_html_e( 'Online booking engine settings' ); ?></h5>
          <h6><?php esc_html_e( 'Copy shortcode from booking engine settings and put on a page' ); ?></h6>
          <h6><?php esc_html_e( 'Online booking engine -> ' ); ?><a href="<?php echo esc_url( home_url() . '/wp-admin/admin.php?page=online-booking-engine' ); ?>"><?php esc_html_e( home_url() . '/wp-admin/admin.php?page=online-booking-engine' ); ?></a></h6>
         </div>  
         
         <div class="minical-body">
          <h5><?php esc_html_e( 'Minical Reports' ); ?></h5>
          <h6><?php esc_html_e( 'Summary Report -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/reports/ledger/show_ledger_summary_report' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/reports/ledger/show_ledger_summary_report' ); ?></a></h6> 
          <h6><?php esc_html_e( 'Charges Report -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/reports/ledger/show_monthly_charge_report' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/reports/ledger/show_monthly_charge_report' ); ?></a></h6>
          <h6><?php esc_html_e( 'Payments Report -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/reports/ledger/show_monthly_payment_report' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/reports/ledger/show_monthly_payment_report' ); ?></a></h6>
          <h6><?php esc_html_e( 'Taxes Report -> ' ); ?><a target="_blank" href="<?php echo esc_url( MHBP_MINICAL_APP_URL.'/reports/ledger/show_monthly_tax_report' ); ?>"><?php esc_html_e( MHBP_MINICAL_APP_URL.'/reports/ledger/show_monthly_tax_report' ); ?></a></h6>
         </div> 
        </div>
    </div>
</div> 

 
