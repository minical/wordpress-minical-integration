var $ = jQuery;

$(document).ready(function(){

$(document).on('click', '.update_minical_api_key', function (event) {
	var api_key = $('input[name="minical_api_key"]').val();
	if( $('input[name="minical_api_key"]').val() == ""){
        alert('Please enter a Minical API key ');
        return false;
    }
        update_minical_api_key(api_key,null);
    });
$(document).on('click', '.disconnect_minical', function (event) {
   var company_id = $(this).attr('data-company-id');
   
	$.ajax({
		    beforeSend: function(request) {
            if (!confirm('Are you sure, Disconnect with Minical !', true)) {
                return false;
            }
            },
			url:site_url+'/wp-admin/admin-ajax.php',
			type:'post',
			data:{
				action: 'deconfigure_minical', 
				company_id : company_id
			},
			dataType:'json',
			success:function(response){
				console.log(response);
				if(response.success){
					location.reload();
			    }
			}
	    });
 });


});

function update_minical_api_key(api_key, company_id)
{
	$.ajax({
			url:site_url+'/wp-admin/admin-ajax.php',
			type:'post',
			data:{
				action: 'update_minical_api_key', 
				api_key : api_key, 
				company_id : company_id
			},
			dataType:'json',
			success:function(response){
				if(response.data.success == true){
					location.reload();
			    }else{
			    	alert(response.data.msg);
			    }
			}
	    });
}

$(document).ready(function(){

	// update booking engine fields
	$('body').on("click", '#save-all-booking-fields-button', function () { 
	    saveAllBookingEngineFields();
	});

	// update booking engine fields
	$('body').on("click", '#save-all-booking-engine-setting', function (e) { 
	    saveAllBookingEngineSettings(e);
	});

});



function changeurl(url, title)
{
	var location = window.location.href;
	location = location.split('&minical-page=');

    var new_url = location[0] + url;
    window.history.pushState("data",title,new_url);
    document.title=url;
}
    
function saveAllBookingEngineFields() {
    var updatedBookingEngineFields = {};
    $(".booking-field-tr").each(function()
    {
        var bookingFieldTr = $(this);
        var bookingFieldId = bookingFieldTr.attr('id');
        
        updatedBookingEngineFields[bookingFieldId] = {
            id: bookingFieldId,
            show_on_booking_form: (bookingFieldTr.find('[name="show_on_booking_form"]').prop('checked')) ? 1 : 0,
            is_required: (bookingFieldTr.find('[name="is_required"]').prop('checked')) ? 1 : 0 
        };
    });

    $.ajax({
		url: site_url+'/wp-admin/admin-ajax.php',
		type: 'post',
		data: {
			action: 'update_booking_engine_fields', 
			updated_booking_engine_fields: updatedBookingEngineFields
		},
		dataType: 'json',
		success: function(response){
			console.log('response',response);
			if(response.success)
            {
                alert('All booking engine fields saved');
            }
		}
    });
}

function saveAllBookingEngineSettings(e) {
	e.preventDefault();
	var allow_same_day_check_in = $('.allow_same_day_check_in').val();
	var store_cc_in_booking_engine = $('.store_cc_in_booking_engine').val();
	var booking_engine_booking_status = $('.booking_engine_booking_status').val();
	var email_confirmation_for_booking_engine = $('.email_confirmation_for_booking_engine').prop('checked') ? 1 : 0;
	var booking_engine_tracking_code = $('.booking_engine_tracking_code').val();

    $.ajax({
		url: site_url+'/wp-admin/admin-ajax.php',
		type: 'post',
		dataType: 'json',
		data: {
			action: 'update_booking_engine_settings', 
			allow_same_day_check_in: allow_same_day_check_in,
			store_cc_in_booking_engine: store_cc_in_booking_engine,
			booking_engine_booking_status: booking_engine_booking_status,
			email_confirmation_for_booking_engine: email_confirmation_for_booking_engine,
			booking_engine_tracking_code: booking_engine_tracking_code
		},
		success: function(response){
			console.log('response',response);
			if(response.success)
            {
                alert('All booking engine settings saved');
            }
		}
    });
}