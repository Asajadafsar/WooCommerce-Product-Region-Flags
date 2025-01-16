<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Display the flag in the product archive pages
add_action('woocommerce_after_shop_loop_item_title', function () {
    global $product;
    $country = get_post_meta($product->get_id(), '_product_country', true);
    $flags = include plugin_dir_path(__FILE__) . '/flags.php';

    if ($country && isset($flags[$country])) {
        echo '<div class="product-country-flag">';
        echo '<img src="' . esc_url($flags[$country]) . '" alt="' . esc_attr($country) . ' flag">';
        echo '</div>';
    }
});

// Display the flag on the single product page
add_action('woocommerce_single_product_summary', function () {
    global $product;
    $country = get_post_meta($product->get_id(), '_product_country', true);
    $flags = include plugin_dir_path(__FILE__) . '/flags.php';

    if ($country && isset($flags[$country])) {
        echo '<div class="single-product-country-flag" style="margin-top: 20px;">';
        echo '<strong>' . __('Country:', 'woocommerce') . '</strong> ';
        echo '<img src="' . esc_url($flags[$country]) . '" alt="' . esc_attr($country) . ' flag" style="width: 25px; height: 18px; vertical-align: middle;">';
        echo '</div>';
    }
}, 15); // First change the priority to 15 for the single product page
