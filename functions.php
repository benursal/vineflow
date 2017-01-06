<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to X in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Parent Stylesheet
//   02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================


add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );



// Additional Functions
// =============================================================================

function show_pre( $arr )
{
	echo '<pre>';
	print_r( $arr );
	echo '</pre>';
}

function woo_in_cart($product_id) {
    global $woocommerce;
 
    foreach($woocommerce->cart->get_cart() as $key => $val ) {
        $_product = $val['data'];
 
        if($product_id == $_product->id ) {
            return true;
        }
    }
 
    return false;
}

add_action( 'cornerstone_load_builder', function() {
	echo '<style>.ps-container>.ps-scrollbar-y-rail>.ps-scrollbar-y {width: 10px !important;}</style>';
});

function default_no_quantities( $individually, $product ){
	$individually = true;
	return $individually;
}

add_filter( 'woocommerce_is_sold_individually', 'default_no_quantities', 10, 2 );


// check for empty-cart get param to clear the cart

add_action( 'init', 'woocommerce_clear_cart_url' );

function woocommerce_clear_cart_url() {

	global $woocommerce;

	if ( isset( $_GET['empty-cart'] ) ) 
	{
		$woocommerce->cart->empty_cart();
	}
}


function lime_add_to_cart_validation($passed, $product_id, $quantity, $variation_id = '', $variations= '') { 
    global $woocommerce;
	
	// validate the number of items in cart if it does not exceed the allowed number
	//$max_quantity = get_option('ywmmq_cart_maximum_quantity');
	
	if( count($woocommerce->cart->get_cart_item_quantities()) == get_option('ywmmq_cart_maximum_quantity') )
	{
		wc_add_notice( __( 'You already have completed the number of content items in your library' , 'woocommerce' ), 'error' );
		return false;
	}
	
	// check if the item is already in the cart
	if( woo_in_cart( $product_id ) )
	{
		$product = new WC_Product($product_id);
		
		wc_add_notice( __( 'You have already added <strong>' . $product->get_title() . '</strong>' , 'woocommerce' ), 'error' );
		return false;
	}
	
	
    return $passed;
}

add_action( 'woocommerce_add_to_cart_validation', 'lime_add_to_cart_validation', 10, 5 );

function lime_check_login_redirect() {
    if ( is_woocommerce() || is_cart() || is_checkout() ) 
	{
        if( ! is_user_logged_in() )
		{
			$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
			
			if ( $myaccount_page_id ) {
				wp_redirect( get_permalink( $myaccount_page_id ) );
			}
			
			exit;
		}
		else
		{
			if( has_existing_order() )
			{
				wp_redirect(site_url('my-account'));
			}
		}
    }
	
	
}
add_action('template_redirect', 'lime_check_login_redirect');


// remove default sorting dropdown
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Removes showing results
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );


function cloudways_product_subcategories( $args = array() ) 
{
	$parentid = get_queried_object_id();
    //echo $parentid;
	//$args = array(
		//'parent' => $parentid
	//);
	
	// get products
	$products_array = get_posts( array('post_type' => 'product', 'posts_per_page' => -1) );
	
	$total_products = count( $products_array );
	
	// get posts for categories
	$terms = get_terms( 'product_cat' );
	 
	if ( $terms ) {
			 
		echo '<ul class="product-cats">';
			echo '<li><b>Categories:</b></li>';
			$class = ( $parentid == 0 ) ? 'selected' : '';
			echo '<li class="'.$class.'"><a href="'.get_permalink( woocommerce_get_page_id( 'shop' ) ).'">All (' . $total_products . ')</a></li>';
			
			foreach ( $terms as $term ) {
					//show_pre( $term );
				$class = ( $parentid == $term->term_id ) ? 'selected' : '';
				
				echo '<li class="' . $class . '">';                 
					
				echo '<a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">';
					echo $term->name . ' (' . $term->count . ')';
				echo '</a>';
																		 
				echo '</li>';
																		 
	 
		}
		 
		echo '</ul>';
	 
	}
	
	
	// mini cart
	// shows number of items selected
	echo '<div class="the-mini-cart">
			<p>You have added</p>
			<h5 style="font-weight:bold"><b id="cart_item_count">'.WC()->cart->get_cart_contents_count().'</b> item(s)</h5>
			<h4>Please add <b id="cart_num_items_needed">'.(get_option('ywmmq_cart_maximum_quantity') - WC()->cart->get_cart_contents_count()).'</b> more items</h4>
			<a href="'.site_url('cart').'">View Your Library</a>
		  </div>';
	
}

add_action( 'woocommerce_before_shop_loop', 'cloudways_product_subcategories', 50 );

function lime_before_shop_loop_item()
{
	echo '<div style="padding:20px; border:3px solid black;">';
}
//woocommerce_before_shop_loop_item_title
//add_action( 'woocommerce_before_shop_loop_item_title', 'lime_before_shop_loop_item', 10, 2 );


function lime_after_shop_loop_item()
{
	echo '</div>';
}

//add_action( 'woocommerce_after_shop_loop_item_title', 'lime_after_shop_loop_item', 10, 2 );


/* Add to the functions.php file of your theme */
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' ); 

function woo_custom_order_button_text() {
    return __( 'Save Items to My Library', 'woocommerce' ); 
}



function save_name_on_item_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['name-on-item'] ) ) {
        $cart_item_data[ 'name_on_item' ] = $_REQUEST['name-on-item'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
	
	//show_pre( $_REQUEST['name-on-item'] );
	
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_name_on_item_field', 10, 2 );


function my_assets() {
	
	wp_enqueue_script( 'app_code', get_stylesheet_directory_uri() . '/js/app.js?ver='.date('YmdHis') );
	wp_localize_script( 'app_code', 'app', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'max_items_in_cart' => get_option('ywmmq_cart_maximum_quantity'),
		'item_count' => WC()->cart->get_cart_contents_count()
	));
}


add_action( 'wp_ajax_nopriv_update_item_description', 'update_item_description' );
add_action( 'wp_ajax_update_item_description', 'update_item_description' );

function update_item_description() {
	
	$items = WC()->cart->get_cart_for_session();
	
	//$s = json_decode($_REQUEST['cart_items'], TRUE);
	
	///print_r($_REQUEST['cart_items'][0]['key']);
	
	
	foreach( $_REQUEST['cart_items'] as $cart_item )
	{
		$items[$cart_item['key']]['wccpf_description'] = $cart_item['value'];
		
		//print_r( $cart_item );
	}
	
	WC()->cart->cart_contents = $items;
	WC()->cart->set_session();
	
	//print_r( $_REQUEST['cart_items'] );
	// Always die in functions echoing ajax content
	die();
}

add_action( 'wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart' );
add_action( 'wp_ajax_remove_item_from_cart', 'remove_item_from_cart' );

function remove_item_from_cart()
{
	$return = false;
	foreach( WC()->cart->get_cart() as $key => $val ) {
       if($val['data']->id == $_REQUEST['product_id'])
	   {
			$return = WC()->cart->remove_cart_item($key);
	   }
    }
 
	echo $return;
	//print_r( $_REQUEST['product_id'] );
	die();
}

add_action( 'wp_enqueue_scripts', 'my_assets' );

// define the woocommerce_update_cart_validation callback 
function filter_woocommerce_update_cart_validation( $true, $cart_item_key, $values, $quantity ) { 
    
	$items = WC()->cart->get_cart_for_session();
	//$items[$cart_item_key]['quantity'] = 1;
	$items[$cart_item_key]['wccpf_description'] = $_REQUEST['cart'][$cart_item_key]['item_description'];

	WC()->cart->cart_contents = $items;
	WC()->cart->set_session();
	
	//WC()->cart->set_quantity($cart_item_key, 1);
	
	//$_SESSION['tae'] = 'tae';
	
    return true; 
}; 
         
// add the filter 
add_filter( 'woocommerce_update_cart_validation', 'filter_woocommerce_update_cart_validation', 10, 4 ); 

/**
 * Set a custom add to cart URL to redirect to
 * @return string
 */
function custom_add_to_cart_redirect() { 
    return site_url('cart'); 
}
add_filter( 'woocommerce_add_to_cart_redirect', 'custom_add_to_cart_redirect' );

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_cart_button_text' );    // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +

function woo_custom_cart_button_text() {
 
        return __( 'Add to My Library', 'woocommerce' );
}


// define the woocommerce_thankyou callback 
function action_woocommerce_thankyou( $order_id ) { 
    
}
         
// add the action 
//add_action( 'woocommerce_thankyou', 'action_woocommerce_thankyou', 10, 1 );


/*
 * Change the order of the endpoints that appear in My Account Page - WooCommerce 2.6
 * The first item in the array is the custom endpoint URL - ie http://mydomain.com/my-account/my-custom-endpoint
 * Alongside it are the names of the list item Menu name that corresponds to the URL, change these to suit
 */

function wpb_woo_my_account_order() {
	$myorder = array(
		'dashboard'          => __( 'Home', 'woocommerce' ),
		'edit-account'       => __( 'Account Details', 'woocommerce' ),
		'customer-logout'    => __( 'Logout', 'woocommerce' ),
	);
	
	unset( $myorder['downloads'] );
	unset( $myorder['orders'] );
	unset( $myorder['edit-address'] );
	unset( $myorder['payment-methods'] );
	
	return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );


/* checks if the customer has a current subscription
 * returns TRUE or FALSE
 */

function has_existing_order()
{
	$customer_orders = new WP_Query( array(
		'posts_per_page ' => 1,
		'meta_key'    => '_customer_user',
		'meta_value'  => get_current_user_id(),
		'post_type'   => wc_get_order_types(),
		'post_status' => array_keys( wc_get_order_statuses() ),
		'date_query' => array(
			array(
				'year'  => date('Y'),
				'month' => date('m')
			),
		),
	));
	
	return count( $customer_orders->posts );
}

add_filter('wp_nav_menu_objects', 'ad_filter_menu', 10, 2);

function ad_filter_menu($sorted_menu_objects, $args) 
{
	
	$new_menu_objects = $sorted_menu_objects;
	
	// check if the user is on the login page
	// this is determined by checking if there's no active user login and if is_account_page returns TRUE
	
	if( !is_user_logged_in() && is_account_page() )
	{
		$new_menu_objects = array();
		echo '<style>.x-brand.text{width:100%;text-align:center;}</style>';
	}
	
	// check if the user has an existing catalog
	if( has_existing_order() )
	{
		$new_menu_objects = array();
		
		foreach( $sorted_menu_objects as $menu_obj )
		{
			
			if( $menu_obj->url != site_url('shop').'/' && $menu_obj->url != site_url('cart').'/' )
			{
				$new_menu_objects[] = $menu_obj;
			}
			
		}
	}
	
	return $new_menu_objects;
}