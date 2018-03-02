<?php

/**
 * Plugin Name: WP REST API Routes
 * Description: Exposing rewrite rules through the REST API.
 * Author: Fredrik Forsmo
 * Author URI: https://frozzare.com
 * Version: 1.0.0
 * Plugin URI: https://github.com/ylletjs/wp-rest-api-routes
 * Textdomain: wp-rest-api-routes
 * Domain Path: /languages/
 */

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Bootstrap plugin.
 */
add_action( 'plugins_loaded', function () {
    Yllet\Api\Routes::instance();
} );
