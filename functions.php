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


