<?php
/*
Plugin Name: Valorant Account Lookup
Description: A WordPress plugin to display a Valorant account lookup form on product pages and add a custom field.
Version: 1.0.0
Author: Md. Shishir Ahmed
Author URI: https://www.shishir1337.com
*/

// Enqueue the necessary scripts and styles
function account_lookup_enqueue_scripts() {
  // Enqueue Tailwind CSS
  wp_enqueue_style( 'tailwind-css', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' );
  
  // Enqueue the plugin script
  wp_enqueue_script( 'account-lookup-script', plugin_dir_url( __FILE__ ) . 'js/account-lookup.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'account_lookup_enqueue_scripts' );

// Shortcode function to display the account lookup form and result
function account_lookup_shortcode() {
  ob_start();
  ?>
  <div class="container mx-auto p-8">
    <h1 class="text-2xl font-bold mb-4">Valorant Account</h1>
    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-center justify-center">
      <div class="flex-grow">
        <label for="ign" class="font-medium">IGN:</label>
        <input id="ign" type="text" class="rounded-md border border-gray-300 p-2" placeholder="Enter IGN">
      </div>
      <div class="flex-grow">
        <label for="tag" class="font-medium">Tag:</label>
        <input id="tag" type="text" class="rounded-md border border-gray-300 p-2" placeholder="Enter Tag">
      </div>
      <div class="flex justify-center items-center mt-2">
        <button id="lookupBtn" class="bg-green-500 px-4 py-2 rounded-md hover:text-green-500">Add</button>
      </div>
    </div>

    <div id="result" class="mt-8">
      <!-- Result will be displayed here -->
    </div>
  </div>
  <?php
  return ob_get_clean();
}
add_shortcode( 'account_lookup', 'account_lookup_shortcode' );

// Enqueue WooCommerce scripts and styles
add_action('wp_enqueue_scripts', 'valorant_custom_field_enqueue_woocommerce_scripts');

// Display custom field on product page
add_action('woocommerce_before_add_to_cart_button', 'valorant_custom_field_display');

// Validate and save custom field
add_filter('woocommerce_add_to_cart_validation', 'valorant_custom_field_validate', 10, 3);
add_action('woocommerce_add_cart_item_data', 'valorant_custom_field_save', 10, 2);
add_filter('woocommerce_get_item_data', 'valorant_custom_field_display_data', 10, 2);
add_action('woocommerce_checkout_create_order_line_item', 'valorant_custom_field_add_to_order', 10, 4);

/**
 * Enqueue WooCommerce scripts and styles
 */
function valorant_custom_field_enqueue_woocommerce_scripts()
{
    if (function_exists('is_product') && is_product()) {
        // Enqueue necessary scripts and styles
        wp_enqueue_script('jquery');
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-single-product');
        wp_enqueue_style('woocommerce-general');
    }
}

/**
 * Display the custom field on the product page
 */
function valorant_custom_field_display()
{
    echo '<div class="custom-field-wrapper">';
    echo '<label for="custom_field">' . __('Your Account', 'woocommerce') . '</label>';
    echo '<input type="text" id="custom_field" name="custom_field" class="input-text" readonly placeholder="' . __('Your Account Name and Tag', 'woocommerce') . '"/>';
    echo '</div>';
}

/**
 * Validate the custom field before adding to cart
 */
function valorant_custom_field_validate($passed, $product_id, $quantity)
{
    $custom_field = isset($_POST['custom_field']) ? sanitize_text_field($_POST['custom_field']) : '';

    if (empty($custom_field)) {
        wc_add_notice(__('Please enter a valid account details.', 'woocommerce'), 'error');
        $passed = false;
    }

    return $passed;
}

/**
 * Save the custom field data in the cart item
 */
function valorant_custom_field_save($cart_item_data, $product_id)
{
    if (isset($_POST['custom_field'])) {
        $cart_item_data['custom_field'] = sanitize_text_field($_POST['custom_field']);
        $cart_item_data['unique_key'] = md5(microtime().rand());
    }
    return $cart_item_data;
}

/**
 * Display the custom field value in the cart and checkout
 */
function valorant_custom_field_display_data($item_data, $cart_item)
{
    if (isset($cart_item['custom_field'])) {
        $item_data[] = array(
            'key'     => __('Your Account Name', 'woocommerce'),
            'value'   => sanitize_text_field($cart_item['custom_field']),
            'display' => '',
        );
    }
    return $item_data;
}

/**
 * Add the custom field value to the order as order item meta
 */
function valorant_custom_field_add_to_order($item, $cart_item_key, $values, $order)
{
    if (isset($values['custom_field'])) {
        $item->add_meta_data(__('Valorant IGN', 'woocommerce'), sanitize_text_field($values['custom_field']));
    }
}
