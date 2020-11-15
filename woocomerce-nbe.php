<?php
/**
 * Plugin Name: WooCommerce NBE
 * Plugin URI: https://woocommerce.com/
 * Description: The woocommerce payment gateway for the National Bank of Egypt.
 * Version: 1.0.0
 * Author: LeeuCode
 * Author URI: https://woocommerce.com
 * Text Domain: lc
 * Domain Path: /i18n/languages/
 * Requires at least: 5.3
 * Requires PHP: 7.0
 *
 * @package WooCommerce
 */

// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

/**
 * Offline Payment Gateway
 *
 * Provides an Offline Payment Gateway; mainly for testing purposes.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class       WC_NBE
 * @extends     WC_Payment_Gateway
 * @version     1.0.0
 * @package     WooCommerce/Classes/Payment
 * @author      SkyVerge
 */
add_action( 'plugins_loaded', 'wc_nbe_gateway_init', 11 );

function wc_nbe_gateway_init() {

    if (class_exists('WC_Payment_Gateway')) {
        require 'includes/wc-nbe-class.php';
        require 'includes/wc-nbe-checkout-descraption-fields.php';
    } //end if
} // end wc_nbe_gateway_init()

function wc_nbe_add_to_gateways( $gateways ) {
    $gateways[] = 'wc_nbe';
    return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'wc_nbe_add_to_gateways' );

// function Zumper_widget_enqueue_script() {   
//     wp_enqueue_script( 'nbe', 'https://test-nbe.gateway.mastercard.com/checkout/version/57/checkout.js' );
// }
// add_action('wp_enqueue_scripts', 'Zumper_widget_enqueue_script');