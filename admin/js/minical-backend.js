$(document).ready(function(){

    $(document).ready(function() {
        $('#minical-rooms').DataTable();
        $('#minical-room-types').DataTable();
        $('#minical-bookings').DataTable();
    } );

$(document).on('click', '.update_minical_api_key', function (event) {
        update_minical_api_key();
    });
});


// Add an event listener for minical_login
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

// Listen to message from child window
eventer(messageEvent,function(e) {
    var key = e.message ? "message" : "data";
    var data = e[key];

    if(data['minical-api-key']){
	    console.log('key', data['minical-api-key']);
	    console.log('company', data['minical-company-id']);
	    update_minical_api_key(data['minical-api-key'], data['minical-company-id']);
	}
},false);


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
				if(response.success){
			    }
			}
	    });
}


    
function iframeURLChange(iframe, callback) {
    var lastDispatched = null;

    var dispatchChange = function () {
        var newHref = iframe.contentWindow.location.href;

        if (newHref !== lastDispatched) {
            callback(newHref);
            lastDispatched = newHref;
        }
    };

    var unloadHandler = function () {
        // Timeout needed because the URL changes immediately after
        // the `unload` event is dispatched.
        setTimeout(dispatchChange, 0);
    };

    function attachUnload() {
        // Remove the unloadHandler in case it was already attached.
        // Otherwise, there will be two handlers, which is unnecessary.
        iframe.contentWindow.removeEventListener("unload", unloadHandler);
        iframe.contentWindow.addEventListener("unload", unloadHandler);
    }

    iframe.addEventListener("load", function () {
        attachUnload();

        // Just in case the change wasn't dispatched during the unload event...
        dispatchChange();
    });

    attachUnload();
}

// Usage:

$(document).ready(function(){
	if(document.getElementById("minical-wp-iframe")) {
		iframeURLChange(document.getElementById("minical-wp-iframe"), function (newURL) {
		    console.log("URL changed:", newURL);

		    if(newURL.indexOf("localhost") ){
		    	newURL = newURL.split('public');
		    }
		    else {
		    	newURL = newURL.split('io');
		    }
		    
		    console.log("URL NEW", newURL);
		    newURL = '&minical-page='+newURL[1];
		    
		    changeurl(newURL, 'minical');

		});
	}

	$('body').on('click', '.minical_auth', function(){
		var buttonID = $(this).attr('id');
		if(buttonID == 'minical_login'){
			var location = window.location.href;
			var newURL = '&minical-page=/auth/login';
		    window.location.href = location + newURL;
		}
		else if(buttonID == 'minical_reg'){
			var location = window.location.href;
			var newURL = '&minical-page=/auth/register';
		    window.location.href = location + newURL;
		}
	});


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