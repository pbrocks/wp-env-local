<?php
/**
 * Plugin Name: TAG wp-env Local
 * Description: A simple plugin to view the debug.log file from the WordPress admin.
 * Version: 1.0.2
 * Author: pbrocks
 */

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

add_action('init', 'tap_wp_env_functionality_init', 1);
/**
 * Initializes the Keap Connect plugin functionality.
 *
 * This function dynamically loads all PHP files from the 'includes' and 'classes'
 * directories to add functionality for the plugin. These files can contain
 * classes, functions, or any necessary logic for the plugin to work.
 *
 * @return void
 */
function tap_wp_env_functionality_init() {
    // Include all PHP files in the /includes directory
    if ( file_exists( __DIR__ . '/includes' ) && is_dir( __DIR__ . '/includes' ) ) {
        foreach ( glob( __DIR__ . '/includes/*.php' ) as $filename ) {
            require $filename;
        }
    }

    // Include all PHP files in the /includes/classes directory
    if ( file_exists( __DIR__ . '/includes/classes' ) && is_dir( __DIR__ . '/includes/classes' ) ) {
        foreach ( glob( __DIR__ . '/includes/classes/*.php' ) as $filename ) {
            require $filename;
        }
    }
}