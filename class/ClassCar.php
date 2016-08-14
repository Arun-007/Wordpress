<?php
class ClassCar
	{

		var $pluginDirectory;
		var $templateDirectory;
		var $msg='';
			function __construct()
			{
				$this->Error=array();
				$this->pluginDirectory = carbookingInfoDir;
				$this->templateDirectory = carbookingInfoDir.'templates/';
				$this->Error["cat_nothing"]="No results found for ";
			}
			
	
			function loadTemplate($tmpl,$data)
			{ 
				if(file_exists($this->templateDirectory.$tmpl.'.php'))
				{
					if ( is_admin() ) 
						{
							$header='adminheader';
							require_once($this->templateDirectory.$header.'.php');
						}
						else
						{
						$header='header';
						require_once($this->templateDirectory.$header.'.php');
						}
					require_once($this->templateDirectory.$tmpl.'.php');
				}
					else
					require_once($this->templateDirectory.'404.php');
			}
			function loadTemplatePart($tmpl,$data)
			{ 
				if(file_exists($this->templateDirectory.$tmpl.'.php'))
					{
						require_once($this->templateDirectory.$tmpl.'.php');
					}
						else
						require_once($this->templateDirectory.'404.php');
			}
			
			/**********************frondend show car list page******************/
			
			function show_cars()
			{	
				
				//echo $this->loadTemplate('admin_message',$data);
				$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
				//$pagenum =2;
				$limit = 12; // number of rows in page
				$offset = ( $pagenum - 1 ) * $limit;
				$data = $this->getFrontendBookingDetails($limit,$offset);
				$total = $data['count'];
				$num_of_pages = ceil( $total / $limit );
				$page_links = $this->paginate($num_of_pages,$pagenum);
				//echo $pagenum;
				$data=array($data['result'],$page_links); 
				
				$this->loadTemplate('showCarsearchbox',$data); 
				$this->loadTemplate('showCarsForBookings',$data);
			}
			
						function getFrontendBookingDetails($limit,$offset)
			{
				global $wpdb;
				if(!empty($_POST['location_name'])){ $_SESSION['location_name'] = $_POST['location_name']; }
				if(!empty($_POST['search_from_date'])){ $_SESSION['search_from_date'] = $_POST['search_from_date']; }
				if(!empty($_POST['search_to_date'])){ $_SESSION['search_to_date'] = $_POST['search_to_date']; }
				
				
					  
				if(isset($_POST['search']))
					{
					  if(!empty($_POST['location_name'])){ $_SESSION['location_name'] = $_POST['location_name']; }else{ $_SESSION['location_name']='';}
					  if(!empty($_POST['search_from_date'])){ $_SESSION['search_from_date'] = $_POST['search_from_date']; }else{ $_SESSION['search_from_date']='';}
					   if(!empty($_POST['search_to_date'])){ $_SESSION['search_to_date'] = $_POST['search_to_date']; }else{ $_SESSION['search_to_date']='';}
					}
				$condition = 0;	
				if(isset($_SESSION['location_name']) && $_SESSION['location_name']!='')
								{

								$locid= $_SESSION['location_name'];
								
								$res = "SELECT * FROM '.$wpdb->prefix.'_term_relationships r INNER JOIN '.$wpdb->prefix.'_posts p ON r.object_id=p.ID WHERE r.term_taxonomy_id=$locid order by p.ID desc LIMIT $offset, $limit";
								
								$total_count = "SELECT COUNT(`ID`) FROM '.$wpdb->prefix.'_term_relationships r INNER JOIN '.$wpdb->prefix.'_posts p ON r.object_id=p.ID WHERE r.term_taxonomy_id=$locid ";
								
								$condition = 1;
								
								
								}
					/*if(isset($_SESSION['search_from_date']) && $_SESSION['search_from_date']!='')
								{
									$locid= $_SESSION['location_name'];
									$from_date = $_SESSION['search_from_date'];
									//$sql = "SELECT * FROM car_term_relationships r INNER JOIN car_posts p ON r.object_id=p.ID left JOIN car_booking b ON b.car_id=p.ID AND b.check_in_date between '$from_date' AND '2015/06/30 11:36' and b.check_out_date between '$from_date' AND '2015/06/30 11:36' WHERE r.term_taxonomy_id=$locid"; 
								$condition = 1;	
								}*/
								
					if(isset($_SESSION['search_to_date']) && $_SESSION['search_to_date']!='')
								{
									$locid= $_SESSION['location_name'];
									$from_date = $_SESSION['search_from_date'];
									$to_date = $_SESSION['search_to_date'];
									if(!empty($locid))
									{
									 $res =  "SELECT * FROM '.$wpdb->prefix.'_posts p  INNER JOIN  '.$wpdb->prefix.'_term_relationships r ON p.ID=r.object_id left JOIN '.$wpdb->prefix.'_carbooking b ON b.car_id=p.ID WHERE r.term_taxonomy_id=$locid  AND p.ID not in ( SELECT car_id FROM ".$wpdb->prefix."_carbooking WHERE ('$to_date' between check_in_date and check_out_date OR (check_out_date between  '$from_date'  and  '$to_date') OR  ('$from_date' between check_in_date and check_out_date) OR (check_in_date between '$from_date'  and '$to_date'))  and status !='cancelled' ORDER BY `car_id` ASC ) GROUP BY p.ID desc LIMIT $offset, $limit";
									
									 $total_count = "SELECT COUNT( DISTINCT(p.ID) ) FROM ".$wpdb->prefix."_posts p  INNER JOIN  ".$wpdb->prefix."_term_relationships r ON p.ID=r.object_id left JOIN ".$wpdb->prefix."_carbooking b ON b.car_id=p.ID WHERE r.term_taxonomy_id=$locid  AND p.ID not in ( SELECT car_id FROM `".$wpdb->prefix."_carbooking` WHERE ('$to_date' between check_in_date and check_out_date OR (check_out_date between  '$from_date'  and  '$to_date') OR  ('$from_date' between check_in_date and check_out_date) OR (check_in_date between '$from_date'  and '$to_date'))  and status !='cancelled' ORDER BY `car_id` ASC )";
									}else{
										
										
										$res =  "SELECT * FROM ".$wpdb->prefix."_posts p  INNER JOIN  ".$wpdb->prefix."_term_relationships r ON p.ID=r.object_id left JOIN ".$wpdb->prefix."_carbooking b ON b.car_id=p.ID WHERE p.post_type='car' AND p.ID not in ( SELECT ".$wpdb->prefix."_id FROM `".$wpdb->prefix."_carbooking` WHERE ('$to_date' between check_in_date and check_out_date OR (check_out_date between  '$from_date'  and  '$to_date') OR  ('$from_date' between check_in_date and check_out_date) OR (check_in_date between '$from_date'  and '$to_date'))  and status !='cancelled' ORDER BY `car_id` ASC ) GROUP BY p.ID desc LIMIT $offset, $limit";
									
									 $total_count = "SELECT COUNT( DISTINCT(p.ID) ) FROM ".$wpdb->prefix."_posts p  INNER JOIN  ".$wpdb->prefix."_term_relationships r ON p.ID=r.object_id left JOIN ".$wpdb->prefix."_carbooking b ON b.car_id=p.ID WHERE  p.post_type='car' AND p.ID not in ( SELECT ".$wpdb->prefix."_id FROM `".$wpdb->prefix."_carbooking` WHERE ('$to_date' between check_in_date and check_out_date OR (check_out_date between  '$from_date'  and  '$to_date') OR  ('$from_date' between check_in_date and check_out_date) OR (check_in_date between '$from_date'  and '$to_date'))  and status !='cancelled' ORDER BY `car_id` ASC )";
										
										
									}
									 
									$condition = 1;
									
									
								}			
								
					if($condition == 1 )
					{
						$sql=$res;
						$total_no=$total_count;
					}
					else
					{
						 $sql = "SELECT * FROM ".$wpdb->prefix."_posts WHERE post_type='car' order by ID desc LIMIT $offset, $limit";
						 $total_no = "SELECT COUNT(`ID`) FROM ".$wpdb->prefix."_posts WHERE post_type='car'";
					}
							//echo $sql;	
									
				/*if(isset($_SESSION['car_name']) && $_SESSION['car_name']!='')
								{
									
								
								$param.=" and  b.car_id  = '".$_SESSION['car_name']."'";
								}
					if(isset($_SESSION['booking_date']) && $_SESSION['booking_date']!='')
								{
								$param.=" and '".$_SESSION['booking_date']."' between b.check_in_date AND  b.check_out_date";
								
								}
					if(isset($_SESSION['search_field']) && $_SESSION['search_field']!='')
								{
								 $search_field = '%'.$_SESSION['search_field'].'%';
								 
								$param.=" and (b.car_id LIKE '$search_field' OR b.check_in_date LIKE '$search_field' OR b.check_out_date LIKE '$search_field' OR b.name LIKE '$search_field' OR b.email LIKE '$search_field' OR
								b.phone LIKE '$search_field' OR b.location LIKE '$search_field' OR b.address LIKE '$search_field' OR b.status LIKE '$search_field')";
								}
								
					if(isset($_SESSION['status']) && $_SESSION['status']!='')
								{
									
								$param.=" and  b.status = '".$_SESSION['status']."'";
								}
								
								
								
				if($param!='')
				{
				$parameter=$this->remove_and($param);
				
				
				 $sql = "SELECT * FROM ".$wpdb->prefix."booking b INNER JOIN ".$wpdb->prefix."posts p ON b.car_id=p.ID WHERE ".$parameter." order by b.id desc LIMIT $offset, $limit";		
				 echo $sql;
					
				
				$total_no = "SELECT COUNT(`car_id`) FROM ".$wpdb->prefix."booking b INNER JOIN ".$wpdb->prefix."posts p ON b.car_id=p.ID WHERE ".$parameter." order by d.car_id asc " ;
				
				
				
				}else
				{				
				
				$sql = "SELECT * FROM ".$wpdb->prefix."booking JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."booking.car_id=".$wpdb->prefix."posts.ID order by ".$wpdb->prefix."booking.id desc LIMIT $offset, $limit";
				
				echo $sql;
				
				$total_no = "SELECT COUNT(`car_id`) FROM ".$wpdb->prefix."booking JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."booking.car_id=".$wpdb->prefix."posts.ID";
				//echo $total_no;
				}*/
				$count = $wpdb->get_var($total_no);
				$result = $wpdb->get_results($sql);
				$data['result'] =$result;
				$data['count'] =$count;
				
				return $data;
			}
			
			
		
			
			/*******************************end frontend filtering***************************/
			function Booking_form()
			{ 
				echo $this->loadTemplatePart('bookingform','');			
				exit;
			}
			
			
			
			
			
				function show_car_Search()
			{	
				$this->loadTemplate('showCarsearchbox','');
			}
			
			
			
				function check_availability_submit()
			{	
				$this->loadTemplate('showCarsForBookings','');
				exit;
			}
			
			
			function checkAvailability($id)
			{
				global $wpdb;
				$id   = $_POST['id'];
				$from   = $_POST['from'];
				$to   = $_POST['to'];			
				if($from > $to)
				{
					$str ='notavailable';
				}else
				{
				//$sql = "SELECT * FROM ".$wpdb->prefix."booking WHERE car_id='$id' AND '$from' between check_in_date AND  check_out_date";
				//$to_sql = "SELECT * FROM ".$wpdb->prefix."booking WHERE car_id='$id' AND '$to' between check_in_date AND  check_out_date";
				//$sql = "SELECT * FROM ".$wpdb->prefix."booking WHERE car_id='$id' AND (check_in_date between '$from' AND '$to') AND (check_out_date between '$from' AND '$to') AND status !='cancelled'";
				//$sql = "SELECT * FROM car_booking WHERE car_id='101' AND ('$from' between check_in_date AND check_out_date) AND ('$to' between check_in_date AND check_out_date)";
				
				//$sql = "SELECT * FROM car_booking WHERE (check_in_date between '$from' AND '$to')  AND (check_out_date between '$from' AND '$to') AND car_id='$id'";
				
				/*$sql ="SELECT * FROM car_booking WHERE  ('$from' AND '$to' between `check_in_date` AND check_out_date) AND status !='cancelled' AND car_id='$id'";*/
				/*Select * from `Reservations` where $ToDate between date_from and date_to 
            OR date_to between  $FromDate  and  $ToDate  
            OR  $FromDate between date_from and date_to 
            OR date_from between $FromDate  and $ToDate
            AND `obj_id` = $obj;*/
			
			
			$sql ="Select * from `".$wpdb->prefix."_carbooking` where ('$to' between check_in_date and check_out_date OR (check_out_date between  '$from'  and  '$to') OR  ('$from' between check_in_date and check_out_date) OR (check_in_date between '$from'  and '$to')) AND car_id='$id' AND status !='cancelled'";
			//echo $sql;
				//$sql ="SELECT * FROM car_booking WHERE  ('$from' AND '$to' between `check_in_date` AND check_out_date) AND status !='cancelled' AND car_id='$id'";
				//echo $sql;
				$results = $wpdb->get_results($sql);
				$results_to = $wpdb->get_results($to_sql);
				if((count($results)>0))
				{
					$str ='notavailable';
					
				}
				else				
				{
					$str = 'available';
					
				}
				}
				echo $str;
				
				
				
				/*$sql = "SELECT * FROM ".$wpdb->prefix."bookings WHERE bed_id='$id' AND check_in_date = '$check_in_date' AND  (status ='pending' || status='approved') ";
				$results = $wpdb->get_results($sql);
				$str = '';
				if(count($results)>0)
				{
					$str ='notavailable';
				}
				else				
				{
					$str = 'available';
				}
				echo $str;	*/			
				
				
				exit;
			}
			
			
			/************************************************************************/
			function insertBokingDetails()
			{
				global $wpdb;	
				
							
					 $carid = $_POST['carid'];
					  $check_in_date = $_POST['from_date'];
					   $check_out_date = $_POST['to_date'];
					  $name = $_POST['full_name'];
					  $email = $_POST['email'];
					  $address = $_POST['address'];
					  $tel = $_POST['tel'];	
					  $location = $_POST['location_name'];
					  if( $location =='')
					  {
						  $loc = $_POST['location'];
					  }else
					  {
						  $loc = $_POST['location_name'];
					  }
					  $loc = $_POST['location'];
					  $status = $_POST['status'];
					  $car_price = get_post_meta( $carid, _price, true );
					  $ts1 = date('Y-m-d', strtotime($check_in_date));
					   $ts2 = date('Y-m-d', strtotime($check_out_date));
					  $datetime1 =date_create($ts1);
					  $datetime2 =date_create($ts2);
					  $colors = array("red", "green", "blue", "yellow");
		
/*					  $datetime1 = date_create('2009-10-11');
$datetime2 = date_create('2009-11-13');
$interval = date_diff($datetime1, $datetime2);
echo $interval->days;*/	
					if($status =='')
					 {
						 $status=pending;
					 }
					 $seconds_diff = date_diff($datetime1, $datetime2);
					 $res= $seconds_diff->days;

					  if($res == 0)
						{
						$res=1;
						}
					 $price = $res * $car_price;
					 
					if($check_in_date > $check_out_date)
					{
						echo $msg='<span class="ErrorMsg">Error!. Booking is not possible</span><br>';
						return false;
					}else
					{
					
					$sql = "insert into ".$wpdb->prefix."carbooking(car_id,check_in_date,check_out_date,name,email,phone,price,address,location,status) 
					values('$carid','$check_in_date','$check_out_date','$name','$email','$tel','$price','$address','$loc','$status')";
					
					
					
					$result = $wpdb->query($sql);
					}
					if($result)
					{	
						echo $msg='<div class="successMsg success">You have sucessfully reserved</span><br></div>';							
						$_POST='';
						return true;
					}
					else
					{
						echo $msg='<span class="ErrorMsg">Error!. Database Error</span><br>';
						return false;
					}
					
					 /*if($status =='')
					 {
						 $status=pending;
					 }
					 					
					$sql = "insert into ".$wpdb->prefix."bookings(bed_id,date_of_booking,check_in_date,name,email,address,phone,children,status) 
					values('$bed_id','$date','$check_in_date','$name','$email','$address','$phone','$children','$status')";
					
					
					$result = $wpdb->query($sql);
					
					$fac = "SELECT facility_name FROM wp_bookings_facility t1 JOIN wp_bookings_bed t2 ON t1.facility_id=t2.facility_id where t2.bed_id='$bed_id'" ;
					$fac_name = $wpdb->get_results($fac);
					//print_r($fac_name);
					
					$message ='<div class="container_mail" style="width:500px;background-color:#1c1c1c;color:#fff;padding-bottom:5px;">
					  <div style="margin-bottom:10px;margin-left:6px;padding-top:6px"><p style="text-align:center"><img src="http://www.rivierahotel.com.lb/wp-content/themes/rivera/images/logo.png" style="width: 94px;"></p></div>
					  <div style="margin-left:8px;padding-bottom:1px;">
					  <table  style="color:#fff;border:1px solid #a18d60;width:486px;">
					  
						<tr>
						<td > Name:</td><td>'. $name .'</td>
						</tr>
						<tr>
						<td >Email </td><td>'. $email .'</td>
						</tr>
						<tr>
						<td >Address</td><td>'. $address .'</td>
						</tr>
						<tr>
						<tr>
						<td >Phone</td><td>'. $phone .'</td>
						</tr>
						<tr>
						<td >Facility</td><td>'.$fac_name[0]->facility_name.'</td>
						</tr>
						<tr>
						<td >Bed No:</td><td>'.$bed_id.'</td>
						</tr>
						<tr>
						<tr>
						<td >Check-in Date: </td><td>'. $check_in_date .'</td>
						</tr>
						<tr>
						<tr>
						<td >No Of Children </td><td>'. $children .'</td>
						</tr>
						
						<tr>
						
						
						  </table></div></div>';
						  
					/******************************************/
					
					/* if($status =='pending')
					 {
						$user_message='<div class="container_mail" style="width:500px;background-color:#1c1c1c;color:#fff;padding-bottom:5px;">
					  <div style="margin-bottom:10px;margin-left:6px;padding-top:6px"><p style="text-align:center"><img src="http://www.rivierahotel.com.lb/wp-content/themes/rivera/images/logo.png" style="width: 94px;"></p></div>
					  <div style="margin-left:8px;padding-bottom:1px;text-align:center">
					  Thank you for booking a bed with us.  We’ve received your reservation request and you will receive a booking confirmation shortly.
					  </br>
					  <a href="http://www.rivierahotel.com.lb/">www.Rivierahotel.com.lb</a></div></div>';
					 }else if($status =='approved')
					 {
						 $user_message='<div class="container_mail" style="width:500px;background-color:#1c1c1c;color:#fff;padding-bottom:5px;">
					  <div style="margin-bottom:10px;margin-left:6px;padding-top:6px"><p style="text-align:center"><img src="http://www.rivierahotel.com.lb/wp-content/themes/rivera/images/logo.png" style="width: 94px;"></p></div>
					  <div style="margin-left:8px;padding-bottom:1px;text-align:center">
					  Your Booking is Approved
					  </br>
					  <a href="http://www.rivierahotel.com.lb/">www.Rivierahotel.com.lb</a></div></div>';
					 }
					
					
					
					  if($_POST['proceed_booking'])
						//echo $message;
					add_filter( 'wp_mail_content_type', function( $message ) {
						return 'text/html';
						});
					  wp_mail( 'beachlounge@rivierahotel.com.lb', 'Bed Booking', $message, $headers );
					  
					  
					  //echo $user_message;
					  add_filter( 'wp_mail_content_type', function( $user_message ) {
						return 'text/html';
						});
					  wp_mail( $email, 'Bed Booking', $user_message, $headers );
					
					if($result)
					{	
						//echo $msg='<div class="successMsg success">You have sucessfully reserved</span><br></div>';							
						$_POST='';
						return true;
					}
					else
					{
						//echo $msg='<span class="ErrorMsg">Error!. Database Error</span><br>';
						return false;
					}*/
			}
			
			
			function admin_update_booking()
			{
				
				global $wpdb;
					 $id = $_GET['booking_id'];
					  $car_name = $_POST['car_name'];
					 $check_in_date = $_POST['check_in_date'];
					 $check_out_date = $_POST['check_out_date'];
					 $name = $_POST['fname'];
					 $email = $_POST['email'];
					 $address = $_POST['address'];
					 $phone = $_POST['phone'];	
					 $location = $_POST['location'];
					 $status = $_POST['status'];
					 
					
					
					$sql = "UPDATE ".$wpdb->prefix."carbooking SET check_in_date='$check_in_date', check_out_date='$check_out_date', name='$name',email='$email',
					address='$address', phone='$phone', location='$location', status='$status' WHERE id='$id'";
					
					$result = $wpdb->query($sql);
					
										
					if($result)
					{	
						if($status =='approved')
					 {
						 $user_message='<div class="container_mail" style="width:500px;background-color:#1c1c1c;color:#fff;padding-bottom:5px;">
					  <div style="margin-bottom:10px;margin-left:6px;padding-top:6px"><p style="text-align:center"><img src="http://www.rivierahotel.com.lb/wp-content/themes/rivera/images/logo.png" style="width: 94px;"></p></div>
					  <div style="margin-left:8px;padding-bottom:1px;text-align:center">
					  Your Booking is Approved
					  </br>
					  <a href="http://www.rivierahotel.com.lb/">www.Rivierahotel.com.lb</a></div></div>';
					 }else if($status =='cancelled')
					 {
						 $user_message='<div class="container_mail" style="width:500px;background-color:#1c1c1c;color:#fff;padding-bottom:5px;">
					  <div style="margin-bottom:10px;margin-left:6px;padding-top:6px"><p style="text-align:center"><img src="http://www.rivierahotel.com.lb/wp-content/themes/rivera/images/logo.png" style="width: 94px;"></p></div>
					  <div style="margin-left:8px;padding-bottom:1px;text-align:center">
					  Your Booking is Cancelled
					  </br>
					  <a href="http://www.rivierahotel.com.lb/">www.Rivierahotel.com.lb</a></div></div>';
					 }
					 	add_filter( 'wp_mail_content_type', function( $user_message ) {
						return 'text/html';
						});
					 	
						wp_mail( $email, 'Bed Booking', $user_message, $headers );
						//echo $this->loadTemplate('admin_addBookingDetails',$id);
						return true;
					}
					else{
					
						return false;
					}
					
			}
			
			function remove_and($param)
			{	
			$param=trim($param);
			$and=substr($param,0,3);
			if($and=="and")
			return substr($param,3,strlen($param));
			else
			return $param;
			}
			
			
			function admin_bookings()
			{	
			global $wpdb;
				
				$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
				//$pagenum =2;
				$limit = 12; // number of rows in page
				$offset = ( $pagenum - 1 ) * $limit;
				$data = $this->getBookingDetails($limit,$offset);
				$total = $data['count'];
				$num_of_pages = ceil( $total / $limit );
				$page_links = $this->paginate($num_of_pages,$pagenum);
				//echo $pagenum;
				$data=array($data['result'],$page_links);  
				$this->loadTemplate('filterTemplate',$data); 
				$this->loadTemplate('showTemplateBookingList',$data);
			}
			/*********************Admin ADD BOOKING***********/
			function admin_add_booking()
			{	
				$data = array();
				if($_REQUEST['action'])
				{
					echo $this->loadTemplate('admin_editBookingDetails','');
					$str = 'Updated';
				}
				else
				{
					echo $this->loadTemplate('admin_addBookingDetails','');
					$str = 'Added Successfully';
				}
				
				if(!empty($this->msg) && $this->msg == 'success')
				{
					$data['msg']='Booking Details '.$str;
					$data['type']='success';
				}
				elseif(!empty($this->msg) && $this->msg == 'error')
				{
					$data['msg']='Failed';
					$data['type']='error';
				}
				echo $this->loadTemplate('admin_message',$data);
				
				
			}
			/*********************END Admin ADD BOOKING***********/
			function getBookingDetails($limit,$offset)
			{
				global $wpdb; global $wp_session;
				 ///$wp_session = WP_Session::get_instance();
				 var_dump($wp_session);
				
				
				
				if(!empty($_POST['loc_name'])){ $_SESSION['loc_name'] = $_POST['loc_name'];$wp_session['loc_name']=$_POST['loc_name']; }
				if(!empty($_POST['car_name'])){ $_SESSION['car_name'] = $_POST['car_name']; }
				if(!empty($_POST['booking_date'])){ $_SESSION['booking_date'] = $_POST['booking_date']; }
				if(!empty($_POST['search_field'])){ $_SESSION['search_field'] = $_POST['search_field']; }
				if(!empty($_POST['status'])){ $_SESSION['status'] = $_POST['status']; }
				
				if(isset($_POST['search']))
					{
					  if(!empty($_POST['loc_name'])){ $_SESSION['loc_name'] = $_POST['loc_name']; }else{ $_SESSION['loc_name']='';}
					  if(!empty($_POST['car_name'])){ $_SESSION['car_name'] = $_POST['car_name']; }else{ $_SESSION['car_name']='';}
					  if(!empty($_POST['booking_date'])){ $_SESSION['booking_date'] = $_POST['booking_date']; }else{ $_SESSION['booking_date']='';}
					  if(!empty($_POST['search_field'])){ $_SESSION['search_field'] = $_POST['search_field']; }else{ $_SESSION['search_field']='';}
					  if(!empty($_POST['status'])){ $_SESSION['status'] = $_POST['status']; }else{ $_SESSION['status']='';}
					  
				if($_REQUEST['search']==true)
						{
							
						}
					}
					
				if(isset($_SESSION['loc_name']) && $_SESSION['loc_name']!='')
								{
									
								/*$args = array(
    							'post_type' => 'car',
    							'tax_query' => array(
        						 array(
            					'taxonomy' => 'location',
            					'field'    => 'id',
            					'terms'    => $_SESSION['loc_name'],
        						),
    								),
   
								);

								$the_query = new WP_Query( $args );*/
								
								
								$sql = "SELECT * FROM ".$wpdb->prefix."_term_relationships r INNER JOIN ".$wpdb->prefix."_posts p ON r.object_id=p.ID WHERE r.term_taxonomy_id=".$_SESSION['loc_name']."";
								$res=$wpdb->get_results($sql);
								//echo "arun".$res[0]->ID;
								//var_dump($res);
								$count = count($res);
								$i=1;
								foreach($res as $value)
								{
									if($count == $i)
									{
										$carid.= $value->ID;
									}else
									{
									$carid.= $value->ID.",";
									}
								
								$i++;}
								//$param.=" b.car_id  = '".$value->ID."'";
								$param.="and b.car_id IN(".$carid.")";
								
								/*$param.=" and  b.car_id  = '".$_SESSION['car_name']."'";*/
								}
								
									
				if(isset($_SESSION['car_name']) && $_SESSION['car_name']!='')
								{
									
								
								$param.=" and  b.car_id  = '".$_SESSION['car_name']."'";
								}
					if(isset($_SESSION['booking_date']) && $_SESSION['booking_date']!='')
								{
								$param.=" and '".$_SESSION['booking_date']."' between b.check_in_date AND  b.check_out_date";
								
								}
					if(isset($_SESSION['search_field']) && $_SESSION['search_field']!='')
								{
								 $search_field = '%'.$_SESSION['search_field'].'%';
								
								$param.=" and (b.car_id LIKE '$search_field' OR b.check_in_date LIKE '$search_field' OR b.check_out_date LIKE '$search_field' OR b.name LIKE '$search_field' OR b.email LIKE '$search_field' OR
								b.phone LIKE '$search_field' OR b.location LIKE '$search_field' OR b.address LIKE '$search_field' OR b.status LIKE '$search_field')";
								}
								
					if(isset($_SESSION['status']) && $_SESSION['status']!='')
								{
									
								$param.=" and  b.status = '".$_SESSION['status']."'";
								}
								
								
								
				if($param!='')
				{
				$parameter=$this->remove_and($param);
				//select * from dbo.Students S INNER JOIN dbo.Advisors A ON S.Advisor_ID=A.Advisor_ID
				
				 $sql = "SELECT * FROM ".$wpdb->prefix."carbooking b INNER JOIN ".$wpdb->prefix."posts p ON b.car_id=p.ID WHERE ".$parameter." order by b.id desc LIMIT $offset, $limit";
					
				
				$total_no = "SELECT COUNT(`car_id`) FROM ".$wpdb->prefix."carbooking b INNER JOIN ".$wpdb->prefix."posts p ON b.car_id=p.ID WHERE ".$parameter." order by b.car_id asc " ;
				
				
				
				}else
				{				
				
				$sql = "SELECT * FROM ".$wpdb->prefix."carbooking JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."carbooking.car_id=".$wpdb->prefix."posts.ID order by ".$wpdb->prefix."carbooking.id desc LIMIT $offset, $limit";
				
				
				
				$total_no = "SELECT COUNT(`car_id`) FROM ".$wpdb->prefix."carbooking JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."carbooking.car_id=".$wpdb->prefix."posts.ID";
				//echo $total_no;
				}
				//echo $sql;
				//echo $total_no;
				$count = $wpdb->get_var($total_no);
				$result = $wpdb->get_results($sql);
				$data['result'] =$result;
				$data['count'] =$count;
				
				return $data;
			}
			
			function paginate($num_of_pages,$pagenum)
			{
				
				$pagelinks = paginate_links( array(
    			'base' => add_query_arg( 'pagenum', '%#%' ),
    			'format' => '',
    			'prev_text' => __( '&laquo;', 'text-domain' ),
    			'next_text' => __( '&raquo;', 'text-domain' ),
    			'total' =>$num_of_pages,
    			'current' => $pagenum
				) );
				
				return $pagelinks;
			}
			
	}

?>