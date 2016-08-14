
<table class="table wp-list-table widefat fixed striped posts">
	<thead>
		<tr>
			<th>Car Name:</th>
			<th>Name</th>
			<th>Check In Date</th>
			<th>Check Out Date</th>
			<th>Address</th>
			<th>Email</th>
			<th>Phone</th>
            <th>Location</th>
            <th>Price($)</th>
			<th>Status</th>
			<th>Edit</th>
			<th>Cancel</th>
		</tr>
	</thead>
    
	<?php 
	
	foreach($data[0] as $booking)
		{ 
		?>
			
			
			<tr>
				<?php   ?>
				<td> <?php echo $booking->post_title; ?></td>
				
				 <td> <?php echo $booking->name; ?></td>
				<td> <?php echo $booking->check_in_date; ?></td>
				<td> <?php echo $booking->check_out_date; ?></td>
               
				
				
				<td> <?php echo $booking->address; ?></td>
				<td> <?php echo $booking->email; ?></td>
				<td> <?php echo $booking->phone; ?></td>
                <td> <?php echo $booking->location; ?></td>
                <td> <?php echo $booking->price; ?></td>
				<td> <?php echo $booking->status; ?></td>
				<?php if($booking->status != 'cancelled') { ?>
				<td> <a href="?page=add_booking&action=edit&booking_id=<?php echo $booking->id ?>">Edit</a></td>
				<td> <a onclick="return delete_fn(<?php echo $bid ?>)" >Cancel</a></td>
				<?php } ?>
			</tr>
		
			
		<?php 
		}


		?>
	
</table>
<div class="clearfix"></div>
<?php 

$page_links = $data[1];


if ( $page_links ) {
  echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
?>

