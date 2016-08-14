<?php

/**********new custom post type***************/
function my_custom_post_car() {
  $labels = array(
    'name'               => _x( 'Cars', 'post type general name' ),
    'singular_name'      => _x( 'Car', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'car' ),
    'add_new_item'       => __( 'Add New Car' ),
    'edit_item'          => __( 'Edit Car' ),
    'new_item'           => __( 'New Car' ),
    'all_items'          => __( 'All Cars' ),
    'view_item'          => __( 'View Car' ),
    'search_items'       => __( 'Search Car' ),
    'not_found'          => __( 'No Car found' ),
    'not_found_in_trash' => __( 'No Cars found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Cars'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our cars and car specific data',
    'public'        => true,
    'menu_position' => 5,
	/*'register_meta_box_cb' => 'add_cars_metaboxes',  //custom neta box*/
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
	
  );
  register_post_type( 'car', $args ); 
}
add_action( 'init', 'my_custom_post_car' );


function my_taxonomies_Car() {
  $labels = array(
    'name'              => _x( 'Car Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Car Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Car Categories' ),
    'all_items'         => __( 'All Car Categories' ),
    'parent_item'       => __( 'Parent Car Category' ),
    'parent_item_colon' => __( 'Parent Car Category:' ),
    'edit_item'         => __( 'Edit Car Category' ), 
    'update_item'       => __( 'Update Car Category' ),
    'add_new_item'      => __( 'Add New Car Category' ),
    'new_item_name'     => __( 'New Car Category' ),
    'menu_name'         => __( 'Car Categories' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'car_category', 'car', $args );
  
  
  
  
    $labels = array(
    'name'              => _x( 'locations', 'taxonomy general name' ),
    'singular_name'     => _x( 'location', 'taxonomy singular name' ),
    'search_items'      => __( 'Car locations' ),
    'all_items'         => __( 'All locations' ),
    'parent_item'       => __( 'Parent location' ),
    'parent_item_colon' => __( 'Parent location:' ),
    'edit_item'         => __( 'Edit location' ), 
    'update_item'       => __( 'Update location' ),
    'add_new_item'      => __( 'Add New location' ),
    'new_item_name'     => __( 'New Car location' ),
    'menu_name'         => __( 'locations' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'location', 'car', $args );
  
  
  
}
add_action( 'init', 'my_taxonomies_car', 0 );
add_action( 'init', 'start_session');
function start_session()
{
	 if (!session_id())
      session_start();
}




// Add the cars Meta Boxes

function add_cars_metaboxes() {
	add_meta_box('wpt_cars_Reg', 'Reg Year', 'wpt_cars_Reg', 'car', 'side', 'default');
	add_meta_box('wpt_cars_trans', 'Transmission', 'wpt_cars_trans', 'car', 'side', 'default');
	add_meta_box('wpt_cars_mileage', 'Mileage', 'wpt_cars_mileage', 'car', 'side', 'default');
	add_meta_box('wpt_cars_size', 'Engine Size', 'wpt_cars_size', 'car', 'side', 'default');
	add_meta_box('wpt_cars_color', 'Color', 'wpt_cars_color', 'car', 'side', 'default');
	add_meta_box('wpt_cars_price', 'Price', 'wpt_cars_price', 'car', 'side', 'default');
	add_meta_box('wpt_cars_gallery', 'Gallery', 'wpt_cars_gallery', 'car', 'side', 'default');
	add_meta_box('wpt_cars_fuel', 'Fuel Type', 'wpt_cars_fuel', 'car', 'side', 'default');
	add_meta_box('wpt_cars_Body', 'Body Type', 'wpt_cars_Body', 'car', 'side', 'default');
}
// The Event Location Metabox

function wpt_cars_Reg() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_reg" id="carmeta_reg" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$reg = get_post_meta($post->ID, '_reg', true);
	
	// Echo out the field
	echo '<input type="text" name="_reg" value="' . $reg  . '" class="widefat" />';

}

function wpt_cars_trans() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_trans" id="carmeta_trans" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$trans = get_post_meta($post->ID, '_trans', true);
	
	// Echo out the field
	echo '<input type="text" name="_trans" value="' . $trans  . '" class="widefat" />';

}

function wpt_cars_mileage() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_mileage" id="carmeta_mileage" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	
	$mileage =  get_post_meta($post->ID, '_mileage', true);
	
	// Echo out the field
	
	echo '<input type="text" name="_mileage" value="' . $mileage  . '" class="widefat1" />';

}

function wpt_cars_size() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_size" id="carmeta_size" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$size = get_post_meta($post->ID, '_size', true);
	
	// Echo out the field
	echo '<input type="text" name="_size" value="' . $size  . '" class="widefat" />';

}

function wpt_cars_color() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_color" id="carmeta_color" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$color = get_post_meta($post->ID, '_color', true);
	
	// Echo out the field
	echo '<input type="text" name="_color" value="' . $color  . '" class="widefat" />';

}

function wpt_cars_price() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_price" id="carmeta_price" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$price = get_post_meta($post->ID, '_price', true);
	
	// Echo out the field
	echo '<input type="text" name="_price" value="' . $price  . '" class="widefat" />';

}

function wpt_cars_gallery() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_gallery" id="carmeta_gallery" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$gallery = get_post_meta($post->ID, '_gallery', true);
	
	// Echo out the field
	echo '<input type="text" name="_gallery" value="' . $gallery  . '" class="widefat" />';

}

function wpt_cars_fuel() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_fuel" id="carmeta_fuel" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$fuel = get_post_meta($post->ID, '_fuel', true);
	
	// Echo out the field
	echo '<input type="text" name="_fuel" value="' . $fuel  . '" class="widefat" />';

}

function wpt_cars_body() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="carmeta_body" id="carmeta_body" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	$body = get_post_meta($post->ID, '_body', true);
	
	// Echo out the field
	echo '<input type="text" name="_body" value="' . $body  . '" class="widefat" />';

}

add_action( 'add_meta_boxes', 'add_cars_metaboxes' );


// Save the Metabox Data

function wpt_save_cars_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['carmeta_reg'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	
	if ( !wp_verify_nonce( $_POST['carmeta_trans'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	if ( !wp_verify_nonce( $_POST['carmeta_mileage'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	if ( !wp_verify_nonce( $_POST['carmeta_size'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	if ( !wp_verify_nonce( $_POST['carmeta_color'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	if ( !wp_verify_nonce( $_POST['carmeta_price'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	if ( !wp_verify_nonce( $_POST['carmeta_gallery'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	if ( !wp_verify_nonce( $_POST['carmeta_body'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	if ( !wp_verify_nonce( $_POST['carmeta_fuel'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$events_meta['_reg'] = $_POST['_reg'];
	$events_meta['_trans'] = $_POST['_trans'];
	$events_meta['_mileage'] = $_POST['_mileage'];
	$events_meta['_size'] = $_POST['_size'];
	$events_meta['_color'] = $_POST['_color'];
	$events_meta['_price'] = $_POST['_price'];
	$events_meta['_gallery'] = $_POST['_gallery'];
	$events_meta['_fuel'] = $_POST['_fuel'];
	$events_meta['_body'] = $_POST['_body'];
	
	// Add values of $events_meta as custom fields
	
	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}

}

add_action('save_post', 'wpt_save_cars_meta', 1, 2); // save the custom fields



/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template');

function my_custom_template($single) {
    global $wp_query, $post;

/* Checks for single template by post type */
if ($post->post_type == "car"){
	$pluginurl = plugin_dir_path( __FILE__ );
	$template = $pluginurl;
    if(file_exists($template))
	
        return $pluginurl . '/post-template/single-car.php';
}
    return $single;
}

?>