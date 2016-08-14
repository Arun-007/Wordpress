<?php
/**
* Plugin Name: CarBooking
* Plugin URI: http://mypluginuri.com/
* Description: CarBooking.
* Version: 1.0 or whatever version of the plugin (pretty self explanatory)
* Author: :)
* Author URI: :)
*/


/* Filter the single_template with our custom function*/

global $wpdb;	

define('carbookingInfoDir',ABSPATH . 'wp-content/plugins/carbooking/');
define('carbookingInfoUrl',site_url() . '/wp-content/plugins/carbooking/');
require_once(carbookingInfoDir.'inc/db.php');
require_once(carbookingInfoDir.'inc/car_posttype.php');
require_once(carbookingInfoDir.'class/ClassCar.php');
//require_once(bookingInfoDir.'class/ClassFacilityType.php');
//require_once(bookingInfoDir.'class/ClassBed.php');
//require_once(bookingInfoDir.'class/ClassBooking.php');
//require_once(bookingInfoDir.'ajax-actions.php');

/*********************db ***********/

function booking_database() {
	
	
	require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	global $wpdb;
	$db_table_name = $wpdb->prefix . 'carbooking';
	
	
	if( $wpdb->get_var( "SHOW TABLES LIKE '$db_table_name'" ) != $db_table_name ) {
		if ( ! empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty( $wpdb->collate ) )
			$charset_collate .= " COLLATE $wpdb->collate";
			
			$sql = "CREATE TABLE IF NOT EXISTS " . $db_table_name . " (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `car_id` int(11) NOT NULL,
				  `check_in_date` varchar(15) NOT NULL,
				  `check_out_date` varchar(15) NOT NULL,
				  `name` varchar(15) NOT NULL,
				  `email` varchar(20) NOT NULL,
				  `phone` varchar(15) NOT NULL,
				  `price` varchar(15) NOT NULL,
				  `location` varchar(15) NOT NULL,
				  `address` varchar(30) NOT NULL,
				  `status` varchar(10) NOT NULL,
				  PRIMARY KEY (`id`)
				)$charset_collate;";
				
				

				dbDelta( $sql );
		
	}
	
	
}
register_activation_hook(__FILE__, 'booking_database');




/********************Booking************/

add_action( 'admin_menu', 'register_my_custom_menu_page' );
	
	function  register_my_custom_menu_page()
	{
		add_menu_page( 'Car-Booking', 'Car-Booking', 'administrator', 'bookings', 'bookings');
		add_submenu_page( 'bookings', 'Add Booking', 'Add Booking', 'administrator', 'add_booking', 'add_booking' );
		//add_submenu_page( 'bookings', 'View Booking Details', 'View Booking Details', 'administrator', 'view_booking_details', 'view_booking_details' );

	}
	

	function bookings()
	{ 			
		$carlist= new ClassCar();		
		$carlist->admin_bookings();
	}
	
		function add_booking()
	{ 			
		$carlist= new ClassCar();		
		
		if($_POST['update_booking'])
		{
			if($carlist->admin_update_booking())
			{
				$carlist->msg= 'success';
			}
			else
			{
				$carlist->msg= 'error';
			}
		}
		if($_POST['admin_add_bookingbtn'])
		{
			if($carlist->insertBokingDetails())
			{ 
				$carlist->msg= 'success';
			}
			else
			{
				$carlist->msg= 'error';
			}
		}
		$carlist->admin_add_booking(); 
	}
	
/* Shortcode for booking */
add_shortcode('BOOKINGS', 'carBookings'); 
function carBookings()
{
	$carlist= new ClassCar();
		$carlist->show_cars();
	
}

$carlist = new ClassCar;
if(isset($_POST['proceed_booking']))
	{
		$carlist->insertBokingDetails();
		
	}


/* Shortcode for Search box */
add_shortcode('SHOW-CAR-SEAR-CHBOX', 'showcarSearch'); 
function showcarSearch()
{
	$carlist= new ClassCar();
		$carlist->show_car_Search();
	
}

add_action( 'wp_ajax_nopriv_bookingform', 'bookingform');
add_action( 'wp_ajax_bookingform', 'bookingform');

function bookingform() 
{
    $classcar= new ClassCar();
	$classcar->Booking_form();
	
}




add_action( 'wp_ajax_nopriv_checkavailabilitysubmit', 'check_availability_submit');
add_action( 'wp_ajax_checkavailabilitysubmit', 'check_availability_submit');

function check_availability_submit() 
{
//echo "ggg";
    $classcar= new ClassCar();
	$classcar->Check_availability_submit();
	
}

add_action( 'wp_ajax_nopriv_check_availability', 'check_availability');
add_action( 'wp_ajax_check_availability', 'check_availability');

function check_availability() 
{ 
	if(isset($_REQUEST['id']))	
		$id = $_REQUEST['id'];
	 else
		$id = '';
    $classcar= new ClassCar;
	$classcar->checkAvailability($id);
}
