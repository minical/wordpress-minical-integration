$(document).ready(function(){

	$(document).on('click', '#find_rooms', function (event) {
        check_room_type_availability();
    });
});

$( function() {

	// get today
    var currentDate = new Date(new Date().getTime());
    var dd = currentDate.getDate().toString();
    var mm = (currentDate.getMonth() + 1).toString();
    var yyyy = currentDate.getFullYear();
    var today = yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);

    // get tomorrow
    var currentDate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    var dd = currentDate.getDate().toString();
    var mm = (currentDate.getMonth() + 1).toString();
    var yyyy = currentDate.getFullYear();
    var tomorrow = yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
	
	

	$( "#check_in_date" ).datepicker();
	$( "#check_out_date" ).datepicker();

	$( "#check_in_date, #check_out_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

	$( "#check_in_date" ).val(today);
	$( "#check_out_date" ).val(tomorrow);

	$(document).on('click', '#check_in_date, #check_out_date', function (event) {
        $('#ui-datepicker-div').css('width','20%');
    });
});

function check_room_type_availability()
{
	var check_in_date = $('input[name="check-in-date"]').val();    
	var check_out_date = $('input[name="check-out-date"]').val();    
	var adult_count = $('select[name="adult_count"]').val();    
	var children_count = $('select[name="children_count"]').val();

	$.ajax({
		url:site_url+'/wp-admin/admin-ajax.php',
		type:'post',
		data:{
				action: 'check_room_type_availability',
				start_date : check_in_date,
				end_date : check_out_date,
				adult_count : adult_count,
				children_count : children_count
			},
		dataType:'json',
		success:function(response){
			console.log('response', response);
			if(response && response.result && response.result.status){

				$('#minical-select-dates-rooms').hide();
				$('#minical-show-reservations').show();

				var html_content = '';
				var image_content = '';
				var max_adult_content = '';
				var daily_rate_content = '';
				var description = '';

				var company_id = response.result.company_id;
				company_data = response.result.data.company_data;
				data = response.result.data.view_data;
				var images = data.available_rate_plans.images;
				var is_rooms_available = false;

				// html_content += '<div class="panel-rate-plan-listing panel panel-success">'+
				// 					'<div class="panel-body" style="padding: 0 !important;">'+
				// 						'Check-in'+'<br/>'+
				// 						data.check_in_date+
				// 					'</div>'+
				// 				'</div>';
								

				$.each(data.available_rate_plans, function(index, value) {

					var is_room_type_unavailable = false;
                	var is_room_bookable = true;

                	var rate_plan_id = value.rate_plan_id;
					var rate_plan_selected_count = 0; 
					var max_adult_content = '';

					if(value.average_daily_rate != 0 || (company_data.allow_free_bookings != 0 || 
						(!value.charge_type_id || value.charge_type_id == 0))){
						is_rooms_available = true;
					}

					if(value && value.images && value.images.length > 0){
						$.each(value.images, function(i, v) {
							image_content = '<a href="https://inngrid.s3.amazonaws.com/'+company_id+'/'+v.filename+'"'+
												'data-lightbox="rate_plan_id" >'+
													'<img class="room-type-img" src="https://inngrid.s3.amazonaws.com/'+company_id+'/'+v.filename+'"/>'+
												'</a>';
						});
					} else {
						image_content = '<div class="panel panel-default text-center">'+
											'<div class="h4 text-muted">Photo not available</div>'+
										'</div>';
					}

					if((value.max_adults).length > 0) {
                        for(var i = 0; i < value.max_adults; i++) {
                            max_adult_content += '<i class="fa fa-male" aria-hidden="true" style="margin-right: 3px;"></i>';
                        }
                    }

                    var average_daily_rate = (value.average_daily_rate).toFixed(2);
                    if (company_data.allow_free_bookings && average_daily_rate == 0){
                        // do not show rate if it's 0
                    } else { 
                    	daily_rate_content = 	'<div class="daily-rate" style="font-size: 32px;">'+
                            					average_daily_rate+
                            					'<span style="font-size: 16px;color: gray;padding-left: 3px;">'+value.currency_code+'</span>'+
                        					'</div>'+
                        					'<span style="color: gray;">Average rate per night</span>';
                    }

                    if(value.description != ''){
                    	description = value.description;
                    }
                    

					html_content += '<div class="panel-rate-plan-listing panel panel-success">'+
										'<div class="panel-body" style="padding: 0 !important;">'+
											'<form action="" method="post">'+
												'<input type="hidden" value="'+company_id+'" name="company-id">'+
												'<input type="hidden" value="'+data.check_in_date+'" name="check-in-date">'+
												'<input type="hidden" value="'+data.check_out_date+'" name="check-out-date">	'+		
												'<input type="hidden" value="'+data.adult_count+'" name="adult_count">'+
												'<input type="hidden" value="'+data.children_count+'" name="children_count">'+			
												'<input type="hidden" value="'+rate_plan_id+'" name="rate-plan-selected-ids[]">'+
												'<div class="col-md-2" style="padding: 0;">'+
													image_content+
												'</div>'+
												'<div class="col-md-10">'+
													'<div class="col-md-7">'+
					                                    '<h3 class="panel-title">'+
					                                        '<div class="h4" style="color: #145291; font-size: 22px; margin-top: 0;">'+value.room_type_name+'</div>'+
					                                        '<div>'+value.rate_plan_name+'</div>'+
					                                        '<div style="font-size: 12px; color: gray; margin-top: 15px;">'+
					                                        	max_adult_content+
					                                        '</div>'+
					                                    '</h3>'+
					                                    '<br/>'+
					                                '</div>'+
					                                '<div class="col-md-5" style="padding: 0;">'+
					                                    '<div class="text-right" style="margin-bottom: 25px;padding: 0 20px;">'+
					                                        daily_rate_content+
					                                    '</div>'+
					                                '</div>'+
					                                '<div class="col-md-12" style="min-height: 55px;">'+
					                                    description+
					                                '</div>'+
					                            '</div>'+
                            					'<input type="button" id="'+rate_plan_id+'" name="submit" value="Book" class="btn btn-primary btn-lg book_reservation" style="width: 200px;float: right; border-radius: 0; padding: 7px;" />'+
					                        '</form>'+
										'</div>'+
									'</div>';
								});

						$('#minical-show-rooms').html(html_content);

			} else {
				alert(response.result && response.result.msg);
			}
		}
    });
}

$(document).on('click', '.book_reservation', function (event) {
    
    selected_rate_plan_id = $(this).attr('id');
    $('#minical-show-reservations').hide();
    $('#minical-book-reservation').show();
    var html_content = '';
    var charge_calculation = '';
    var show_charges = '';
    var tax_calculation = '';
    var daily_rate_content = '';
    average_daily_rate = 0;

    $.each(data.available_rate_plans, function(index, value) {
    	if(selected_rate_plan_id == value.rate_plan_id){

    		average_daily_rate = (value.average_daily_rate).toFixed(2);
            if (company_data.allow_free_bookings && value.average_daily_rate == 0){
                // do not show rate if it's 0
            } else { 
            	daily_rate_content = 	'<dt>'+
                    						'Average Daily Rate:'+
                    					'</dt>'+
                    					'<dd>'+
                    						average_daily_rate+
                						'</dd>';
            }

    		show_charges += 	'<div class="panel panel-default">'+
                    				'<div class="panel-heading">'+
                        				value.rate_plan_name+
                    				'</div>'+
			                        '<div class="panel-body">'+
			                        	'<dl class="dl-horizontal">'+
								            '<dt>'+
								                'Check in Date:'+
								            '</dt>'+
								            '<dd>'+
								                data.check_in_date+
								            '</dd>'+
								            '<dt>'+
								                'Check out Date:'+
								            '</dt>'+
								            '<dd>'+
								                data.check_out_date+
								            '</dd>'+

								            '<dt>'+
								                'Adults Count:'+
								            '</dt>'+
								            '<dd>'+
								                data.adult_count+
								            '</dd>'+

								            '<dt>'+
								                'Children Count:'+
								            '</dt>'+
								            '<dd>'+
								                data.children_count+
								            '</dd>'+
								            '<dt>'+
								                'Currency:'+
								            '</dt>'+
								            '<dd>'+
								                data.default_currency.currency_name+
								            '</dd>'+
								        '</dl>'+
								        '<br/>'+
			                            '<dl class="dl-horizontal">'+
			                                '<dt>'+
			                                    'Room:'+
			                                '</dt>'+
			                                '<dd>'+
			                                    value.room_type_name+
			                                '</dd>'+
			                                daily_rate_content+
			                            '</dl>'+
			                        '</div>'+
			                    '</div>';
        }
    });


    $.ajax({
		url:site_url+'/wp-admin/admin-ajax.php',
		type:'post',
		data:{
				action: 'charge_calculation',
				view_data: data,
				rate_plan_id: selected_rate_plan_id
			},
		dataType:'json',
		success:function(resp){
			// console.log('resp', resp);

			var result = resp.result.data.view_data;

			if (company_data.allow_free_bookings && average_daily_rate == 0){
	            //// do not show rate if it's 0
	        } else {

	        	if (result.tax_amount > 0) {
					tax_calculation =   '<dt>'+
						                    'Tax:'+
								        '</dt>'+
						                '<dd class="text-right text-muted">'+
						                    (result.tax_amount).toFixed(2);
						                '</dd>';
					            }

	        	charge_calculation = 	'<dl class="h3 dl-horizontal">'+
								            '<dt>'+
								                'Total Charge:'+
								            '</dt>'+
								            '<dd class="text-right text-muted">'+
								                (result.sub_total).toFixed(2)+
								            '</dd>'+
								            	tax_calculation+
								            '<dt>'+
								                'Total:'+
								            '</dt>'+
								            '<dd class="text-right text-muted">'+
								                (result.total).toFixed(2)+
								            '</dd>'+
								        '</dl>';

			    $('#charge_calculation').html(charge_calculation);
			}
		}

	});

	$.ajax({
		url:site_url+'/wp-admin/admin-ajax.php',
		type:'post',
		data:{
				action: 'get_customer_info_form',
			},
		dataType:'json',
		success:function(fields){
			console.log('fields', fields);

			if(fields.result.status){
				$('#guest-information-form').html(fields.result.booking_form);
			}
		}
	});

	html_content += '<div class="col-md-4">'+
						'<div class="page-header h3">'+
            				'Booking Information'+
            				'<a href="javascript:" class="start_over btn btn-default btn-sm pull-right">'+
				                'Start Over'+
				            '</a>'+
				        '</div>'+
				        show_charges+
				        '<span id="charge_calculation"></span>'+
					'</div>'+
					'<div class="col-md-8">'+
    					'<div class="panel panel-default">'+
				            '<div class="panel-heading">'+
				                'Please enter your information'+
				            '</div>'+
			            	'<div class="panel-body">'+
				                '<form id="guest-information-form"'+
				                    'action=""'+
				                    'method="post"'+
				                    'class="form-horizontal">'+
		                        '</form>'+
				            '</div>'+
				        '</div>'+
				    '</div>';

		$('#minical-book-room').html(html_content);

});

$(document).on('click', '#book_room', function (event) {

	var el = $('#guest-information-form').find('.form-group').find('input');
	var validate = '';
	el.each(function () {

        var required_field = $(this).attr('required');
        var field_name = $(this).attr('name');
        if(field_name == 'special_requests') {
        	var field_value = $('textarea[name="'+field_name+'"]').val();
        } else {
        	var field_value = $('input[name="'+field_name+'"]').val();
        }
        

        if(field_value == '' && required_field != undefined)
        {
        	var id = $(this).attr('id');
        	var data_error = $(this).attr('data-error');
        	$('#error_'+id).css({'color':'red','font-size':'14px'});
        	$('#error_'+id).text(data_error);
        	validate = 'error';
        }
	});


    if(validate == '') {

    	$(this).prop('disabled', true);

    	var form = $('#guest-information-form').find('.form-group').find('input');
    	var form_data = {};
    	form.each(function () {

    		var field_name = $(this).attr('name');
	        if(field_name == 'special_requests') {
	        	var field_value = $('textarea[name="'+field_name+'"]').val();
	        } else {
	        	var field_value = $('input[name="'+field_name+'"]').val();
	        }

	        if(field_name != undefined && field_value != undefined)
	        {
	        	form_data[field_name] = field_value
	        }

	        form_data = Object.assign({}, form_data);
	        
		});

    	console.log('form_data',form_data);

    	$.ajax({
			url:site_url+'/wp-admin/admin-ajax.php',
			type:'post',
			data:{
					action: 'book_room',
					form_data: form_data,
					rate_plan_id: selected_rate_plan_id,
					view_data: data,
					company_data: company_data,
					average_daily_rate: average_daily_rate
				},
			dataType:'json',
			success:function(response){
				console.log('response', response);
				if(response && response.result && response.result.status){

					$('#minical-booking-engine-thankyou').show();
    				$('#minical-book-reservation').hide();

				} else {
					alert(response.result && response.result.msg);
				}
			}
		});
    }
});

$(document).on('click','.start_over', function(){
	location.reload();
});