$(document).ready(function(){

	$(document).on('click', '#find_rooms', function (event) {
        check_room_type_availability();
    });

	$(document).on('keyup', '#cc_expiry', function(){
		var ccMonth = $(this).val();
		if(ccMonth.length == 2 && (ccMonth >= 1 && ccMonth <= 12)){
			$(this).val(ccMonth + ' / ');
		}
	});

	// $(document).on('keyup', '#cc_number', function() {
	// 	var ccNum = $.trim($(this).val());
	// 	console.log('ccNum',ccNum);
	// 	console.log('ccNumLen',ccNum.length);
	// 	if(ccNum.length == 4 || ccNum.length == 9 || ccNum.length == 14) {
	// 		$(this).val(ccNum + ' ');
	// 	}
	// });

	$(document).on('blur', '#cc_number', function() {
		var ccNum = $.trim($(this).val());
        if (ccNum.length != 0) {
            var check_card = /^X.*.{1,15}$/;
            var re16digit = /^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/;
            if (check_card.test(ccNum)) {

            } else if (!re16digit.test(ccNum)) {
                alert("Please enter valid card number");
            }
        }
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

	var checkInDate = today;
    var checkOutDate = tomorrow;

    $(document).on('click', '#check_in_date, #check_out_date, .ui-icon-circle-triangle-w, .ui-icon-circle-triangle-e', function (event) {
        $('#ui-datepicker-div').css('width','20%');
    });

	// $('input[name=check-in-date]').val(checkInDate);
 //    $('input[name=check-out-date]').val(checkOutDate);

	$("input[name=check-in-date]").datepicker({
		minDate: 0,
        dateFormat: 'yy-mm-dd',
        beforeShow: customRange
    });

    $("input[name=check-out-date]").datepicker({
        dateFormat: 'yy-mm-dd',
        beforeShow: customRange
    });


    function customRange(input) {
        var dateMin = null;
        var dateMax = null;

        if (input.name == "check-in-date") {
            if ($("[name='check-out-date']").val() != '') {
                dateMax = $("[name='check-out-date']").val();
            }
        } else if (input.name == "check-out-date") {
            if ($("[name='check-in-date']").val() != '') {
                dateMin = $("[name='check-in-date']").val();
            }
        }

        return {
            minDate: dateMin,
            maxDate: dateMax
        };
    }

});



function check_room_type_availability()
{
	var check_in_date = $('input[name="check-in-date"]').val();    
	var check_out_date = $('input[name="check-out-date"]').val();    
	var adult_count = $('select[name="adult_count"]').val();    
	var children_count = $('select[name="children_count"]').val();

	if(check_in_date == ''){
		alert('Please enter check-in date');
		return false;
	}
	if(check_out_date == ''){
		alert('Please enter check-out date');
		return false;
	}

	$.ajax({
		url:site_url+'/wp-admin/admin-ajax.php',
		type:'post',
		data:{
				action: 'check_room_type_availability',
				start_date : check_in_date,
				end_date : check_out_date,
				adult_count : adult_count,
				children_count : children_count,
				is_ajax_wp : true
			},
		dataType:'json',
		success:function(response){
			console.log('response', response);
			if(response && response.result && response.result.status){

				$('#minical-select-dates-rooms').fadeOut("300");
				$('#minical-show-reservations').fadeIn("400");

				var html_content = '';
				var daily_rate_content = '';

				var company_id = response.result.company_id;
				company_data = response.result.data.company_data;
				data = response.result.data.view_data;
				var images = data.available_rate_plans.images;
				
				$.each(data.available_rate_plans, function(index, value) {

					var is_room_type_unavailable = false;
                	var is_room_bookable = true;
                	var is_rooms_available = false;

                	var rate_plan_id = value.rate_plan_id;
					var rate_plan_selected_count = 0; 
					var max_adult_content = '';
					var btn_blocked = '';
					var error_content = '';
					var image_content = '';
					var sm_image_content = '';
					var daily_rate_content = '';
					var description = '';

					console.log('value',value);

					if(value.average_daily_rate != 0 || (company_data.allow_free_bookings != 0 || 
						(!value.charge_type_id || value.charge_type_id == 0))){
						is_rooms_available = true;
					}

					if(is_rooms_available) {

						if(value && value.images && value.images.length > 0) {
							var j = 0;

							if(j == 0 && value.images.length > 1){
								sm_image_content += '<div class="rt-small-img">';
							}
							$.each(value.images, function(i, v) {
								if(i > 0) {
									j++;
								}
								if(i == 0) {
									image_content += '<a target="_blank" href="'+value.image_url+company_id+'/'+v.filename+'"'+
														'data-lightbox="'+rate_plan_id+'" >'+
														'<img class="room-type-img" src="'+value.image_url+company_id+'/'+v.filename+'" />'+
													'</a>';
								} else {
									
										sm_image_content += '<a class="img-anchor" target="_blank" href="'+value.image_url+company_id+'/'+v.filename+'"'+
														'data-lightbox="'+rate_plan_id+'" >'+
														'<img class="room-type-small-img" src="'+value.image_url+company_id+'/'+v.filename+'" />'+
													'</a>';
										
									//}
								}
							});

							if(j > 0 && sm_image_content) {
								sm_image_content += '</div>';
							}
						} else {
							image_content = '<div class="panel panel-default text-center">'+
												'<div class="h4 text-muted">Photo not available</div>'+
											'</div>';
						}

						image_content = image_content + sm_image_content;

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

	                    if(data && data.unavailable_room_types && data.unavailable_room_types.length > 0){
		                    $.each(data.unavailable_room_types, function(i, v) {
		                		if(v.id == value.room_type_id)
		                		{
		                			is_room_type_unavailable = true;
		                            is_room_bookable = false;
		                            error_content = '<div class="col-md-6" style="font-size: 14px;color: red;">This room type is unavailable for the given dates</div>';
		                			btn_blocked = 'disabled';
		                		}
		                    });
		                }


	                    if(value.min_length != undefined){
	                    	error_content = '<div class="col-md-6" style="font-size: 14px;color: red;">'+value.min_length+'</div>';
	                    	is_room_bookable = false;
	                    	btn_blocked = 'disabled';
	                    }
	                    if(value.max_length != undefined){
	                    	error_content = '<div class="col-md-6" style="font-size: 14px;color: red;">'+value.max_length+'</div>';
	                    	is_room_bookable = false;
	                    	btn_blocked = 'disabled';
	                    }
	                    if(value.arrival != undefined){
	                    	error_content = '<div class="col-md-6" style="font-size: 14px;color: red;">'+value.arrival+'</div>';
	                    	is_room_bookable = false;
	                    	btn_blocked = 'disabled';
	                    }
	                    if(value.departure != undefined){
	                    	error_content = '<div class="col-md-6" style="font-size: 14px;color: red;">'+value.departure+'</div>';
	                    	is_room_bookable = false;
	                    	btn_blocked = 'disabled';
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
					                                '<div class="col-md-12" style="min-height: 123px;">'+
					                                    description+
					                                '</div>'+
					                                
					                            	error_content+
					                            '</div>'+
                        							'<input type="button" id="'+rate_plan_id+'" '+btn_blocked+' name="submit" value="Book" class="btn btn-primary btn-lg book_reservation" style="width: 140px;float: right;border-radius: 0;padding: 7px;font-size: 12px;" />'+
					                        '</form>'+
										'</div>'+
									'</div>';
					}
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
    $('#minical-show-reservations').fadeOut("300");
    $('#minical-book-reservation').fadeIn("400");
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
			                        '<div class="panel-body" style="padding: 15px 0px;width: 310px;">'+
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

	html_content += '<div class="col-md-5">'+
						'<div class="page-header h4">'+
            				'Booking Information'+
            				'<a href="javascript:" class="start_over btn btn-default btn-sm pull-right">'+
				                'Start Over'+
				            '</a>'+
				        '</div>'+
				        show_charges+
				        '<div class="panel panel-default" id="charge_calculation"></div>'+
					'</div>'+
					'<div class="col-md-7">'+
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
    	$(this).val('Processing. . .');

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

    	var formText = $('#guest-information-form').find('.form-group').find('textarea');
		formText.each(function () {

    		var field_name = $(this).attr('name');
        	var field_value = $('textarea[name="'+field_name+'"]').val();
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

    				$('#minical-book-reservation').fadeOut("300");
					$('#minical-booking-engine-thankyou').fadeIn("400");

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