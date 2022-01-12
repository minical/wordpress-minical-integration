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


function update_minical_api_key()
{
	var api_key = $('#minical_api_key').val();    

	if(api_key == ''){
		alert('Please enter api key');
		return false;
	}

	$.ajax({
			url:site_url+'/wp-admin/admin-ajax.php',
			type:'post',
			data:{action: 'update_minical_api_key', api_key : api_key},
			dataType:'json',
			success:function(response){
				if(response.success){
					$("#api_key_updation").show();

					setTimeout(function(){
						location.reload();
					},2000);
			    }
			}
	    });
}

    

    
	

    


