<link href="<?php echo site_url(); ?>/wp-content/plugins/car-listing/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
<script src="<?php echo site_url(); ?>/wp-content/plugins/car-listing/js/jquery.datetimepicker.js"></script>
<?php
global $wpdb;	


	
function getlocation($locid='')
{
	$locations = get_terms( 'location', 'orderby=name&hide_empty=0' );
	
	$out='<option value=""> Location </option>';
	echo "location".$locid;
	foreach($locations as $loc)
		{
			
			if($locid==$loc->term_taxonomy_id)
			$out.='<option selected="selected" value="'.$loc->term_taxonomy_id.'">'.$loc->name.'</option>';
			else
			$out.='<option value="'.$loc->term_taxonomy_id.'">'.$loc->name.'</option>';
		}
		return $out;
	
}

function getcarDetail($loc)
	{ 
		global $wpdb; 
		if(empty($loc))
		{
			$data=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE post_type='car'");
		$res='<option value=""> Cars </option>';
		foreach($data as $value)
		{
			if($carid==$value->ID)
			$res.='<option selected="selected" value="'.$value->ID.'">'.$value->post_title.'</option>';
			else
			$res.='<option value="'.$value->ID.'">'.$value->post_title.'</option>';
		}
		return $res;	
		}else
		{
		$args = array(
    'post_type' => 'car',
    'tax_query' => array(
        array(
            'taxonomy' => 'location',
            'field'    => 'id',
            'terms'    => $loc,
        ),
    ),
   
);

$the_query = new WP_Query( $args );

		
		$res='<option value=""> Cars </option>';
		foreach($the_query->posts as $value)
		{
			if($carid==$value->ID)
			$res.='<option selected="selected" value="'.$value->ID.'">'.$value->post_title.'</option>';
			else
			$res.='<option value="'.$value->ID.'">'.$value->post_title.'</option>';
		}
		}
		return $res;	
	}



?>


<div class="col-md-12 bookings admin_add_booking booking-modal">

<form method="POST" enctype="multipart/form-data" id="car_booking" action="">
<h3>Book CAR</h3>

<table class="table">
	<input type="hidden" name="booking_id" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->booking_id;} ?>">
<tr>  
<td class="table_td lefttd">Location</td>   
<td><select name="location" onchange="form.submit();" class="selectfld facility form-control"><?php echo getlocation($_REQUEST['location']); ?></select>
<?php $term = get_term_by('id', 11, 'location'); ?>
<input type="hidden" name="location_name" value="<?php echo $name = $term->name; ?>"/>

</td>
</tr>	
<tr>
<td colspan="2" align="center">
<?php if(isset($msg)) echo $msg; ?>
</td> 
</tr>
<tr>
<td class="table_td lefttd">Car Name</td> 

<td><select name="carid"  id="car_name" class="selectfld facility form-control"><?php echo getcarDetail($_REQUEST['location']); ?></select></td>

	

</tr>

<tr>
<td class="table_td lefttd">Check-in Date</td> 
<td class="table_td righttd">
	<input type="text" class="textfld check_in_date form-control" id="date_timepicker_start" name="from_date" required>
	<label class="check_in_error avlerror"></label><label class="check_in_success avlsuccess"></label>
</td>
</tr>
<tr>
<td class="table_td lefttd">Check-Out Date</td> 
<td class="table_td righttd">
	<input type="text" class="textfld check_in_date form-control" id="date_timepicker_end" name="to_date" required>
	<label class="check_in_error avlerror"></label><label class="check_in_success avlsuccess"></label>
</td>
</tr>
<tr>
</tr>
<td>
<label for="available_result" class="available_result"><?php  $price = get_post_meta( $_REQUEST['carid'], _price, true ); ?><input id="price"type="hidden" value="<?php echo $price;?>"></label>

    <div class="clearfix"></div>
    <input type="button" disabled="" value="Check Availability" class="gradient-button custom-button clsdisabled" id="checkavailability" name="checkavailability">
</td>
<td>
<div class="message"></div>
</td>
<tr>
<td class="table_td lefttd">Name</td> 
<td class="table_td righttd">
	<input type="text" class="textfld name form-control" name="full_name" required />
</td>
</tr>
<tr>
<td class="table_td lefttd">Email</td> 
<td class="table_td righttd">
	<input type="email" class="textfld email form-control" name="email" required />
</td>
</tr>
<tr>
<td class="table_td lefttd">Address</td> 
<td class="table_td righttd">
	<textarea name="address" cols=17 class="address form-control txtarea" required ></textarea>
</td>
</tr>
<tr>
<td class="table_td lefttd">Phone</td> 
<td class="table_td righttd">
	<input type="text" class="textfld phone form-control" name="tel" required/>
</td>
</tr>
<tr>
<td class="table_td lefttd">Status</td> 


<td class="table_td righttd">
	<select name="status" class="form-control selectfld">
    	<option value="approved" <?php if($result[0]->status == 'approved'){echo 'selected';} ?>>Approved</option>
        <option value="pending" <?php if($result[0]->status == 'pending'){echo 'selected';} ?>>Pending</option>
        <option value="cancelled" <?php if($result[0]->status == 'cancelled'){echo 'selected';} ?>>Cancelled</option>
    </select>
</td>



</tr>

<tr>
<td class="table_td lefttd"></td> 
<td class="table_td righttd">

<input type="submit" value="Proceed Booking" name="proceed_booking" class="book btn btn-primary proceed_booking clsdisabled" disabled />

</td>
</tr>

</table>
</form>
</div>
<script type="text/javascript">
var dNow = new Date();
		var utcdate= dNow.getFullYear() + '/' + (dNow.getMonth()+ 1) + '/' + dNow.getDate(); 
		jQuery('#date_timepicker_start').datetimepicker({
		minDate: utcdate,
		});

		jQuery('#date_timepicker_end').datetimepicker({
		minDate: utcdate,
		onSelectDate:checkAvailability,
	    closeOnDateSelect:true,
		
		});
		var car_price = jQuery('#price').val();
		
		function checkAvailability()
		{
			
			var from_date =new Date(jQuery('#date_timepicker_start').val());
			var to_date =new Date(jQuery('#date_timepicker_end').val());
			var diff = to_date - from_date;
			var res =(diff/(1000*60*60*24));
			if(res == 0)
			{
				res=1;
			}
			var price_res = res*car_price;
			jQuery('#price').val(price_res);
			
			var flag = 1 ;
			//alert(flag);
			if(from_date == '' || to_date == '')
			{
				flag = 0;
				
			}
			if(from_date > to_date)
			{
				
				flag = 0;
			}
			if(flag == 1)
			{
				jQuery('#car_booking #checkavailability').removeAttr('disabled');
				jQuery('#car_booking #checkavailability').removeClass('clsdisabled');
				jQuery('.booking-modal .message .alert.alert-danger').remove();
				
			}else
			{
				jQuery('#car_booking #checkavailability').attr('disabled','true');
				jQuery('#car_booking #checkavailability').addClass('clsdisabled');
				jQuery('.booking-modal .message').append('<div class="alert alert-danger"><strong>Check From and To date</strong></div>');
			}
		}
		var nme =jQuery('#carid').val();
		
		/*jQuery( "#car_name" ).change(function() {
			var nme =jQuery('#car_name').val();
  		
		});*/

		jQuery("#checkavailability").click(function(){
				
				jQuery.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>",
				type: 'POST',
				data: {
				action: 'check_availability',
				id: jQuery('#car_name').val(),
				
				from : jQuery('#date_timepicker_start').val(),
				to : jQuery('#date_timepicker_end').val(),
				
				},
				dataType: 'html',
				success: function(response) { 
				
				if(jQuery.trim(response) == 'available')
					{
						
						jQuery('.booking-modal .message').append('<div class="alert alert-success"><strong>CAR Available</strong></div>');
						 jQuery('.booking-modal .message .alert.alert-success').delay(3000).fadeOut();
						jQuery('#car_booking .proceed_booking').removeAttr('disabled');
						jQuery('#car_booking .proceed_booking').removeClass('clsdisabled');
					}
					else if(jQuery.trim(response) == 'notavailable')
					{
						jQuery('.booking-modal .message').append('<div class="alert alert-danger"><strong>CAR not available</strong></div>');
						 jQuery('.booking-modal .message .alert.alert-danger').delay(3000).fadeOut();
						//jQuery('#car_booking .available_result').text('');
						jQuery('#car_booking .proceed_booking').attr('disabled','true');
						jQuery('#car_booking .proceed_booking').addClass('clsdisabled');
					}
				//alert(response);
				
				
				
				
				
				}
				
			});
				
				
			
		
		
		});





 </script>

