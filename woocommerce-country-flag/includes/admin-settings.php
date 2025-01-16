<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add custom field for selecting the country in WooCommerce product settings
add_action('woocommerce_product_options_general_product_data', function () {
    $countries = include plugin_dir_path(__FILE__) . '/flags.php';

    woocommerce_wp_select([
        'id'          => '_product_country',
        'label'       => __('Product Country', 'woocommerce'),
        'description' => __('Select the country for this product.', 'woocommerce'),
        'options'     => $countries,
    ]);
});

// Save the custom field value
add_action('woocommerce_process_product_meta', function ($post_id) {
    if (isset($_POST['_product_country'])) {
        update_post_meta($post_id, '_product_country', sanitize_text_field($_POST['_product_country']));
    }
});
