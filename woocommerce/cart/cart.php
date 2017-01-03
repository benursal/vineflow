<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//WC()->cart->set_quantity('1ff1de774005f8da13f42943881c655f', 1);

//$items = WC()->cart->get_cart_for_session();
//$items['c20ad4d76fe97759aa27a0c99bff6710']['wccpf_description'] = 'bahosss tae';
//WC()->cart->cart_contents = $items;
//WC()->cart->set_session();

//$items = WC()->cart->get_cart_item_quantities();//['b6d767d2f8ed5d21a44b0e5886680cb9']['wccpf_description'] = 'stephen benedict';
//show_pre( $items );


//$wp_session = WP_Session::get_instance();
//$_SESSION['tae'] = 'sdf';
//show_pre( $_SESSION['tae'] );

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" id="my-cart" onsubmit="return false" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table shop_table_responsive cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e( 'Name', 'woocommerce' ); ?></th>
			<th class="product-custom-description"><?php _e( 'Custom Description', 'woocommerce' ); ?></th>
			
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-remove">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'woocommerce' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
					</td>

					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo $thumbnail;
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
						?>
					</td>

					<td class="product-name" data-title="<?php _e( 'Product', 'woocommerce' ); ?>">
						<?php
							if ( ! $product_permalink ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
							}

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
							}
						?>
					</td>

					<td class="product-custom-description">
					
						<?php
						
						$customer_can_add_description = get_post_meta( $product_id, "wccaf_customer_can_add_description", true );
						
						
						if( $customer_can_add_description == 'YES' ) : 
						?>
					
							<input type="text" name="<?php echo $cart_item_key;?>" value="<?=$cart_item['wccpf_description'];?>" placeholder="Item Title" class="input-text item-title text" />
						
						<?php else : ?>
						
							<span class="muted">No description needed</span>
						
						<?php endif; ?>


						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '<input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
					</td>
				</tr>
				<?php
			}
		}
		
		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" style="text-align:right" class="">
				<!--<a class="button" href="<?php echo WC()->cart->get_cart_url(); ?>?empty-cart"><?php _e( 'Empty Cart', 'woocommerce' ); ?></a>-->
				
				<a href="<?php echo site_url('shop');?>">Continue Selecting</a>
				
				<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update', 'woocommerce' ); ?>" />
				
				
				
				<?php do_action( 'woocommerce_cart_actions' ); ?>
				
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<div style="text-align:center; margin-top:30px;">
	<?php woocommerce_button_proceed_to_checkout(); ?>
</div>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<?php do_action( 'woocommerce_after_cart' ); ?>
