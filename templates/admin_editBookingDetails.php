<div class="col-md-12 bookings admin_edit_booking">
<form method="post" enctype="multipart/form-data" id="frm_admin_edit_booking" action="">
<h3>Edit Booking Details</h3>
<?php
global $wpdb;

if($_REQUEST['booking_id']) 
{
$sql = "SELECT *  FROM ".$wpdb->prefix."carbooking WHERE id='$_REQUEST[booking_id]'";
$result = $wpdb->get_results($sql);
}
?>
<table class="table">
	<input type="hidden" name="booking_id" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->booking_id;} ?>">
	
<tr>
<td colspan="2" align="center">
<?php if(isset($msg)) echo $msg; ?>
</td> 
</tr>
<tr>
<td class="table_td lefttd">Car Name</td> 
<td class="table_td righttd">
<input type="text" class="textfld check_in_date form-control" id="" name="car_name" value="<?php echo get_the_title( $result[0]->car_id ); ?>" readonly>	
	
</td>
</tr>

<tr>
<td class="table_td lefttd">Check-in Date</td> 
<td class="table_td righttd">
	<input type="text" class="textfld check_in_date form-control" id="datetimepicker6" name="check_in_date" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->check_in_date;} ?>" readonly>
	<label class="check_in_error avlerror"></label><label class="check_in_success avlsuccess"></label>
</td>
</tr>
<tr>
<td class="table_td lefttd">Check-Out Date</td> 
<td class="table_td righttd">
	<input type="text" class="textfld check_in_date form-control" id="datetimepicker6" name="check_out_date" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->check_out_date;} ?>" readonly>
	<label class="check_in_error avlerror"></label><label class="check_in_success avlsuccess"></label>
</td>
</tr>
<tr>
<td class="table_td lefttd">Name</td> 
<td class="table_td righttd">
	<input type="text" class="textfld name form-control" name="fname" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->name;} ?>">
</td>
</tr>
<tr>
<td class="table_td lefttd">Email</td> 
<td class="table_td righttd">
	<input type="email" class="textfld email form-control" name="email" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->email;} ?>">
</td>
</tr>
<tr>
<td class="table_td lefttd">Address</td> 
<td class="table_td righttd">
	<textarea name="address" cols=17 class="address form-control txtarea"><?php if(isset($_REQUEST['booking_id'])){echo $result[0]->address;} ?></textarea>
</td>
</tr>
<tr>
<td class="table_td lefttd">Phone</td> 
<td class="table_td righttd">
	<input type="text" class="textfld phone form-control" name="phone" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->phone;} ?>">
</td>
</tr>
<tr>
<td class="table_td lefttd">location</td> 
<td class="table_td righttd">
	<input type="text" class="textfld phone form-control" name="location" value="<?php if(isset($_REQUEST['booking_id'])){echo $result[0]->location;} ?>" readonly>
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

<input type="submit" value="Update Booking Details" name="update_booking" class="update_booking btn btn-primary"/>

</td>
</tr>

</table>
</form>
</div>
<script type="text/javascript">
jQuery( document ).ready( function() {

jQuery("#frm_admin_edit_booking").validate({    
        rules: {
            name: "required",
            email: {required: true,	email:true },
            phone: {required: true,	minlength:10 },
            address: "required"	
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter yor name",
            email: "Please enter your email",
			address: "Please enter your address",
			phone: "Please enter your phone number"
			
        }
    });
	/* jQuery( '.admin_edit_booking .check_out_date' ).blur( function( e ) {
		var id = jQuery('.bed_id').val();alert(id);
		var chkindate = jQuery('.check_in_date').val();
		var chkoutdate = jQuery('.check_out_date').val();
		if(chkindate == '' || chkoutdate == '' || id == '')
		{
			if(id == '')
				jQuery('bed_number_val').text('Select Bed Number');
			else				
				jQuery('.bed_number_val').text('');
				
			if(chkindate == '')
				jQuery('.check_in_val').text('Enter check-in date');
			else				
				jQuery('.check_in_val').text('');
				
			if(chkoutdate == '')
				jQuery('.check_out_val').text('Enter check-out date');
			else				
				jQuery('.check_out_val').text('');
			
			return false;
		}
		else
		{ 
			jQuery.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>",
				type: 'POST',
				data: {
				action: 'check_availability',
				id: id,
				check_in_date: chkindate,
				check_out_date: chkoutdate
				},
				dataType: 'html',
				success: function(response) { alert(response);
					jQuery('.admin_edit_booking .available_result').text(response);
					if(response == 'available')
					{ jQuery('.admin_edit_booking .btn.update_booking').removeAttr('disabled'); }
					else
					{ jQuery('.admin_edit_booking .btn.update_booking').attr('disabled','true'); }
				}
			});
		}
	}); */
	
	     var selected_date ="";
		 var selected_time ="";
		 jQuery('#datetimepicker6').datetimepicker({
		     format:'Y-m-d',
			 minDate:0,	
			 onSelectDate:checkAvailability,
			 closeOnDateSelect:true,
		     timepicker:false
		   });	
	
});
function checkAvailability()
	{
		var id = jQuery('.bed_id').val();//alert(id);
		var chkindate = jQuery('.check_in_date').val();
		if(chkindate == '' || id == '')
		{
			if(id == '')
				jQuery('bed_number_val').text('Select Bed Number');
			else				
				jQuery('.bed_number_val').text('');
				
			if(chkindate == '')
				jQuery('.check_in_val').text('Enter check-in date');
			else				
				jQuery('.check_in_val').text('');
						
			return false;
		}
		else
		{ 
			jQuery.ajax({
				url: "<?php echo admin_url('admin-ajax.php'); ?>",
				type: 'POST',
				data: {
				action: 'check_availability',
				id: id,
				check_in_date: chkindate
				},
				dataType: 'html',
				success: function(response) {//alert(response);
					if(response == 'available')
					{						
						jQuery('.admin_edit_booking .check_in_success').text('Bed is Available');
						jQuery('.admin_edit_booking .check_in_error').text('');	
						jQuery('.admin_edit_booking .btn.update_booking').removeAttr('disabled'); 
					}
					else
					{ 						
						jQuery('.admin_edit_booking .check_in_error').text('Bed is Not Available');						
						jQuery('.admin_edit_booking .check_in_success').text('');
						jQuery('.admin_edit_booking .btn.update_booking').attr('disabled','true'); 
					}
				}
			});
		}
		//jQuery('#frm_admin_edit_booking').submit();
	}	
</script>