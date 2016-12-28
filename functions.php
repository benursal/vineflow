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

function lime_check_login_redirect() {
    if (
        ! is_user_logged_in()
        && (is_woocommerce() || is_cart() || is_checkout())
    ) {
        
		$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
		
		if ( $myaccount_page_id ) {
			wp_redirect( get_permalink( $myaccount_page_id ) );
		}
		
        exit;
    }
}
add_action('template_redirect', 'lime_check_login_redirect');




add_action( 'woocommerce_add_to_cart_validation', 'lime_add_to_cart_validation', 10, 5 );
