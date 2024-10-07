<?php
/**
 * This creates the API Guys menus in wp-admin.
 *
 * @since   0.7.4
 * @package keap_wp_functionality
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class create the Admin menus.
 *
 * @since 0.7.4
 */
class Create_TAG_WP_Admin_Menu {
    /**
     * Main construct
     *
     * @since 0.7.4
     *
     * @return void
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array( $this, 'tag_wp_env_enqueue_scripts' ) );
    }


    /**
     * Enqueues JavaScript for the plugin's admin functionality.
     *
     * This function adds the necessary JavaScript file for the admin dashboard
     * to handle the Keap API authentication.
     *
     * @return void
     */
    public function tag_wp_env_enqueue_scripts() {
        // wp_enqueue_script(
        //     'wp-env-local-js', 
        //     plugins_url( 'js/keap-auth-plugin.js', __DIR__), 
        //     array('jquery'), 
        //     '0.0.2', 
        //     true
        // );
        // wp_localize_script(
        //     'wp-env-local-js',
        //     'keap_connect_ajax_object',
        //     array(
        //         'keap_connect_ajaxurl' => admin_url('admin-ajax.php'),
        //         'random_number'   => time(),
        //         'keap_connect_nonce'   => wp_create_nonce('keap-connect-nonce'),
        //         'explanation_one' => 'Set up anything from the PHP side here in this function ('.__FUNCTION__. '). Add the variable to the JS file.',
        //     )
        // );
        wp_enqueue_style(
            'wp-env-local-css', 
            plugins_url( 'css/wp-env-local.css', __DIR__), 
            array(), 
            '0.0.2'
        );
    
    }

}

new Create_TAG_WP_Admin_Menu();