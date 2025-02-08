<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Shortcode to display the country flag in product pages
add_shortcode('product_country_flag', function () {
    global $post;
    $country = get_post_meta($post->ID, '_product_country', true);
    $flags = include plugin_dir_path(__FILE__) . '/flags.php';

    if ($country && isset($flags[$country])) {
        return '<div class="product-country-flag">
            <img src="' . esc_url($flags[$country]) . '" alt="' . esc_attr($country) . ' flag" style="width: 40px; height: 25px; margin-top: 10px;">
        </div>';
    }
    return '';
});

// Display the flag in the product archive pages
add_action('woocommerce_after_shop_loop_item_title', function () {
    global $product;
    $country = get_post_meta($product->get_id(), '_product_country', true);
    $flags = include plugin_dir_path(__FILE__) . '/flags.php';

    if ($country && isset($flags[$country])) {
        echo '<div class="product-country-flag">';
        echo '<img src="' . esc_url($flags[$country]) . '" alt="' . esc_attr($country) . ' flag" style="width: 25px; height: 18px; margin-top: 5px;">';
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
        echo '<strong>' . __('Country:', 'woocommerce') . '</strong>';
        echo '<img src="' . esc_url($flags[$country]) . '" alt="' . esc_attr($country) . ' flag" style="width: 25px; height: 18px; vertical-align: middle;">';
        echo '</div>';
    }
}, 15);

// Shortcode to display country flags as filter icons
add_shortcode('product_country_flags', function () {
    $flags = include plugin_dir_path(__FILE__) . '/flags.php';
    $current_category = get_queried_object();
    $products_in_category = wc_get_products(array(
        'category' => array($current_category->slug),
        'limit' => -1,
    ));

    $countries_in_category = array();
    foreach ($products_in_category as $product) {
        $country = get_post_meta($product->get_id(), '_product_country', true);
        if ($country && !in_array($country, $countries_in_category)) {
            $countries_in_category[] = $country;
        }
    }

    $default_country = in_array('us', $countries_in_category) ? 'us' : (count($countries_in_category) > 0 ? $countries_in_category[0] : '');

    $output = '<div class="country-flag-filter">';

    foreach ($flags as $code => $url) {
        if ($code !== '' && in_array($code, $countries_in_category)) {
            $active_class = ($code === $default_country) ? 'active' : '';
            $output .= '<a href="#" data-country="' . esc_attr($code) . '" class="country-flag-link ' . $active_class . '">';
            $output .= '<img src="' . esc_url($url) . '" alt="' . esc_attr($code) . ' flag" style="width: 25px; height: 18px; margin: 5px;">';
            $output .= '</a>';
        }
    }

    $output .= '</div>';

    $output .= '<style>
        .country-flag-link {
            display: inline-block;
            transition: all 0.3s ease;
            border-radius: 5px;
            padding: 5px;
        }
        .country-flag-link.active {
            animation: selectFlag 0.5s ease;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            transform: scale(1.1);
        }
        @keyframes selectFlag {
            0% { transform: scale(1); box-shadow: 0 0 0 rgba(0, 0, 0, 0); }
            50% { transform: scale(1.2); box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); }
            100% { transform: scale(1.1); box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); }
        }
    </style>';

    $output .= '<script>
    jQuery(document).ready(function($) {
        var defaultCountry = $(".country-flag-link.active").data("country");
        $(".product").hide();
        $(".product").each(function() {
            var productCountry = $(this).find(".product-country-flag img").attr("alt").split(" ")[0];
            if (productCountry === defaultCountry) {
                $(this).show();
            }
        });

        $(".country-flag-link").click(function(e) {
            e.preventDefault();
            var country = $(this).data("country");
            $(".country-flag-link").removeClass("active");
            $(this).addClass("active");
            $(".product").hide();
            $(".product").each(function() {
                var productCountry = $(this).find(".product-country-flag img").attr("alt").split(" ")[0];
                if (productCountry === country) {
                    $(this).show();
                }
            });
        });
    });
    </script>';

    return $output;
});
?>
