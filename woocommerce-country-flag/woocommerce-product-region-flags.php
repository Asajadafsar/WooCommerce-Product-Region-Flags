<?php
/**
 * Plugin Name: WooCommerce Product Region Flags
 * Description: Add a country flag to WooCommerce products and display it in product archives and product pages.
 * Version: 1.1.0
 * Author: sajad afsar
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-flags.php';

// Enqueue styles
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('woocommerce-product-region-flags', plugins_url('assets/styles.css', __FILE__));
});
