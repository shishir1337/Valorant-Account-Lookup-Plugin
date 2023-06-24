<?php
/**
 * Plugin Name: Valorant Account Lookup Plugin
 * Plugin URI:  https://www.shishir1337.com
 * Description: A WordPress plugin to display a Valorant account lookup form on product pages.
 * Version:     1.0
 * Author:      Md. Shishir Ahmed
 * Author URI:  https://www.shishir1337.com
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
  <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-center">
    <div class="flex-grow">
      <label for="ign" class="font-medium">IGN:</label>
      <input id="ign" type="text" class="rounded-md border border-gray-300 p-2" placeholder="Enter IGN">
    </div>
    <div class="flex-grow">
      <label for="tag" class="font-medium">Tag:</label>
      <input id="tag" type="text" class="rounded-md border border-gray-300 p-2" placeholder="Enter Tag">
    </div>
    <div>
      <button id="lookupBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-4">OK</button>
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
