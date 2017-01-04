<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
	<td class="product-image">
		<?php 
			$featured_image = get_post_thumbnail_id($product->id); 
			$image = wp_get_attachment_image_src( $featured_image );
			$image_full = wp_get_attachment_image_src( $featured_image, 'full' );
			
			echo do_shortcode('[image class="my-second-portfolio" src="'.$image[0].'" alt="Example" type="rounded" link="true" href="'.$image_full[0].'" title="Example Image"][lightbox]');
		?>
	</td>
	<td class="product-name">
		<?php
			$is_visible        = $product && $product->is_visible();
			$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

			echo '<b style="font-size:18px">'.$item['name'].'</b>';
		?>
	</td>
	<td class="product-total">
		<?php 
			//show_pre( $item );		
			if( isset($item['Description']) && $item['Description'] != '' )
			{
				echo $item['Description'];
			}
			else
			{
				echo '<span class="muted">---</span>';
			}
			
		?>
	</td>
</tr>
<?php if ( $show_purchase_note && $purchase_note ) : ?>
<tr class="product-purchase-note">
	<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
</tr>
<?php endif; ?>
