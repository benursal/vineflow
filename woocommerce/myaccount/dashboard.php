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
	
	
	if( has_existing_order() )
	{
		echo do_shortcode('[alert type="info" close="false" heading="Welcome"]Welcome to your content library for the month of <strong>'. strtoupper(date('F')) . '</strong>[/alert]');
		
		if( next_month_library_exists() )
		{
			$d = new DateTime();
			$d->modify( 'last day of next month' );
			$next_month = strtoupper($d->format( 'F' ));
			
			echo do_shortcode('[alert type="success" close="true" heading="New Content Library!"]We\'re pleased to announce that the content library for the month of <strong>'. $next_month . '</strong> is now available.  
			<p style="text-align:center; margin-top:20px;"><a href="'.site_url('shop').'" class="button">Click here to view library</a></p>[/alert]');
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
	
	//show_pre( $customer_orders->posts );
	//$order_details = WC()->order->get_order($customer_orders->posts[0]->ID);
	//echo $customer_orders->posts[0]->ID;
	//show_pre( $order_details );
	
	//echo count( $customer_orders->posts );
	
	//$order = new WC_Order($customer_orders->posts[0]->ID);
	//show_pre( $order->get_items() );
	
	//wc_get_template( 'myaccount/view-order.php', array( 'order_id' => 65 ) );
	
	if( has_existing_order() )
	{
		woocommerce_account_view_order($customer_orders->posts[0]->ID);
	}
	else
	{
?>
		<p>If you wish to continue this service, you can <a href="<?=site_url('shop'); ?>">choose content items</a>.</p>
		<p style="text-align:center"><a href="<?=site_url('shop'); ?>" class="button">Choose Content Items</a></p>
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
