<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php post_class(); ?>>
	<?php
	show_pre( $product );
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	
	echo woocommerce_get_product_thumbnail();
	
	?>
	
	
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<a rel="nofollow" href="<?php echo esc_url( $product_url ); ?>" data-quantity="1" data-product_id="<?php echo $product->id;?>" data-product_sku="" class="button product_type_simple add_to_cart_button ajax_add_to_cart">Add to library</a>
	
	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php
	
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
