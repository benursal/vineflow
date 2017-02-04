<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php

$my_catalogs = get_my_catalogs();
$catalog_months;
//show_pre( $my_catalogs );
// get all the current catalogs
echo '<b style="font-size:18px">My Catalogs</b>: ';

echo '<select name="my_catalogs">';

foreach( $my_catalogs as $catalog )
{
	$order_library = get_post_meta( $catalog->ID, 'order_library', true );
	$catalog_months[] = $order_library;
	
	$selected = ( (isset($_REQUEST['order']) && $_REQUEST['order'] == $catalog->ID) || $order_library == date('m/Y') ) ? 'selected' : '';
	echo '<option value="'.$catalog->ID.'" '.$selected.'>'.$order_library.'</option>';
}

echo '</select>';
// "select" the current library being viewed

$existing_library = count( $my_catalogs );
$the_order_id = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : $my_catalogs[0]->ID;
?>



	<?php
		//echo sprintf( esc_attr__( 'Hello %s%s%s (not %2$s? %sSign out%s)', 'woocommerce' ), '<strong>', esc_html( $current_user->display_name ), '</strong>', '<a href="' . esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) . '">', '</a>' );
	?>
	<?php
		//echo sprintf( esc_attr__( 'From your account dashboard you can view your %1$srecent orders%2$s, manage your %3$sshipping and billing addresses%2$s and %4$sedit your password and account details%2$s.', 'woocommerce' ), '<a href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '">', '</a>', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-address' ) ) . '">', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-account' ) ) . '">' );
	?>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );
	
	/** Add a conditional here... **/
	
	//echo '<div class="alert"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> 
			//<h3>Welcome to your content library for the month of <strong>'. date('F') . '</strong></h3></div>';
	
	
	$month_viewing = explode('/', get_post_meta( $the_order_id, 'order_library', true ));
	
	$d = new DateTime();
	$d->setDate($month_viewing[1], $month_viewing[0], 1);
	
	$month_name = strtoupper($d->format( 'F' ));
	//$next_month_param = strtoupper($d->format( 'm-Y' ));

	echo $next_month;
	
	if( $existing_library )
	{
		
		echo do_shortcode('[alert type="info" close="false" heading="Welcome"]Welcome to your content catalog for the month of <strong style="font-size:18px">'. strtoupper( $month_name ) . '</strong>[/alert]');
		
		$next_month_libraries = next_month_library_exists();
		
		if( $next_month_libraries )
		{
			//show_pre( $next_month_libraries );
			//show_pre( $catalog_months );
			
			foreach( $next_month_libraries as $n_month )
			{
				//unset( $next_month_libraries[$c_month] );
				if( !in_array( $n_month, $catalog_months ) )
				{
					$nx_month = explode('/', $n_month);
	
					$d = new DateTime();
					$d->setDate($nx_month[1], $nx_month[0], 1);
					
					$month_links[] = '<a href="'.site_url('shop').'/?order_library='.str_replace('/', '-', $n_month).'" class="month_link">'. ucwords($d->format('F')).'</a>';
				}
			}
			
			if( count( $month_links ) > 0 )
			{
				if( count( $month_links ) == 1 )
				{
					$heading = 'New Content Library Available!';
					$month_links_str = $month_links[0];
					$linking_verb = 'is';
				}
				elseif( count( $month_links ) == 2 )
				{
					$month_links_str = implode(' and ', $month_links );
					$heading = 'New Content Libraries Available!';
					$linking_verb = 'are';
				}
				elseif( count( $month_links ) > 2 )
				{
					$month_links_str = implode(', ', $month_links );
					$heading = 'New Content Libraries Available!';
					$linking_verb = 'are';
				}
				
				//echo $month_links_str;
				
				//show_pre( $next_month_libraries );
				
				echo do_shortcode('[alert type="success" close="true" heading="'.$heading.'"]We\'re pleased to announce that the content library for <strong>'. $month_links_str . '</strong> '.$linking_verb.' now available.[/alert]');
				
				//<p style="text-align:center; margin-top:20px;"><a href="'.site_url('shop').'/?month='.$next_month_param.'" class="button">Click here to view library</a></p>[/alert]');
			}
		}
		
	}
	else
	{
		echo do_shortcode('[alert type="warning" close="false" heading="Important Notice"]You don\'t have a content library for <strong>'. date('F') . '</strong>.[/alert]');
	} 
	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );
	
	
	//$_REQUEST['month'];
	
	//echo $existing_library;
	
	$args = array(
				'posts_per_page ' => 1,
				'order' => 'DESC',
				'meta_key'    => '_customer_user',
				'meta_value'  => get_current_user_id(),
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_order_statuses() ),
				'date_query' => array(
					array(
						'year'  => date('Y'),
						'month' => date('m')
					),
				)
			);
	
	// if there is more than 1 order for this month, that means the has already created a catalog for next month
	// if that is the case, we'll get the FIRST row/record
	if( $existing_library > 1 && !isset( $_REQUEST['month']) )
	{
		$args['order'] = 'ASC';
	}
	
	$customer_orders = new WP_Query( $args );
	
	//show_pre( $customer_orders->posts );
	//$order_details = WC()->order->get_order($customer_orders->posts[0]->ID);
	//echo $customer_orders->posts[0]->ID;
	//show_pre( $order_details );
	
	//echo count( $customer_orders->posts );
	
	//$order = new WC_Order($customer_orders->posts[0]->ID);
	//show_pre( $order->get_items() );
	
	//wc_get_template( 'myaccount/view-order.php', array( 'order_id' => 65 ) );
	
	if( $existing_library )
	{
		//echo $my_catalogs[0]->ID;
		woocommerce_account_view_order($the_order_id);
	}
	else
	{
?>
		<p>To select your preferred content, click below:</p>
		<p style="text-align:center"><a href="<?=site_url('shop'); ?>" class="button">View Library</a></p>
<?php
	}
?>
	
	
<?php	
	
	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
?>
