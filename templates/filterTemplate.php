<style>
.tabStudentDet td
{
	vertical-align:top;
}
.selectfld, .textfld
{
	max-width:140px !important; 
}
.selectfld
{
	min-width:140px;
}
</style>
<link href="<?php echo site_url(); ?>/wp-content/plugins/car-listing/css/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
<script src="<?php echo site_url(); ?>/wp-content/plugins/car-listing/js/jquery.datetimepicker.js"></script>
<?php
global $wpdb;	

/*$args = array(
    'post_type' => 'car',
    'tax_query' => array(
        array(
            'taxonomy' => 'location',
            'field'    => 'slug',
            'terms'    => 'oman',
        ),
    ),
   
);


// The Query
$the_query = new WP_Query( $args );

while ( $the_query->have_posts() ) {
	$the_query->the_post();
	the_title();
}
wp_reset_postdata();*/






/*function getcarDetail($loc)
	{ 
		global $wpdb; 
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
	}*/
	
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

<div class="container-fluid filterContainer">
<h3>View Booking Details</h3>

<form id="filterform" method="post" action="admin.php?page=bookings">
<table class="filterTable">
<tr>
<td><select name="loc_name" onchange="form.submit();" class="selectfld facility form-control"><?php echo getlocation($_SESSION['loc_name']); ?></select></td>
<td><select name="car_name"  class="selectfld facility form-control"><?php echo getcarDetail($_SESSION['loc_name']); ?></select></td>
<!--<td><select name="car_name" onchange="form.submit();" class="selectfld facility form-control"><?php //echo getcarDetail($_SESSION['car_name']); ?></select></td>-->

<td><input type="text" id="datetimepickeradmin" class="textfld facname form-control" name="booking_date" placeholder="yyyy/mm/dd" value="<?php echo $_SESSION['booking_date'] ?>" /></td> 

<td><input type="text" class="textfld facname form-control" name="search_field" placeholder="Name" value="<?php echo $_SESSION['search_field'] ?>" /></td> 

<td>
<select name="status" class="selectfld form-control">
	<option value="">Booking Status</option>
	<option value="approved" <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 'approved') echo 'selected'; ?>>Approved</option>
	<option value="pending" <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 'pending') echo 'selected'; ?>>Pending</option>
	<option value="cancelled" <?php if(isset($_SESSION['status']) && $_REQUEST['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
</select>
</td> 


<td>
<input type="hidden" value="true" name="search" />
<input type="submit" value="Filter" class="btn studentFilter btn-primary" id="studentFilter" name="filter" /></td>

</tr>


</table>
</form>
</div>
<script >
jQuery('#datetimepickeradmin').datetimepicker();
</script>