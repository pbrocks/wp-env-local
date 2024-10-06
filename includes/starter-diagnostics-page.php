<?php
/**
 * Starter Diagnostics
 */
if ( is_multisite() ) {
    add_action( 'network_admin_menu', 'starter_diagnostics' );
}
/**
 * Add a page to the dashboard menu.
 *
 * @since 1.0.0
 *
 * @return array
 */
add_action( 'admin_menu', 'starter_diagnostics' );
function starter_diagnostics() {
    $slug  = preg_replace( '/_+/', '-', __FUNCTION__ );
    $label = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
    // add_dashboard_page(
    //     __( $label, 'starter-diagnostics' ),
    //     __( $label, 'starter-diagnostics' ),
    //     'manage_options', $slug . '.php',
    //     'starter_diagnostics_page' 
    // );

    add_menu_page(
        // __( 'Starter Page', 'starter-settings' ), // Localized title for the page.
        // __( 'Starter Options', 'starter-settings' ), // Localized title for the menu.
        __( $label, 'starter-diagnostics' ),
        __( $label, 'starter-diagnostics' ),
        'manage_options', // Capability required to access the page.
        'starter-diagnostics', // Unique slug for the page.
        'starter_diagnostics_page', // Callback function to display the content.
        'dashicons-palmtree', // Icon for the menu.
        3 // Position in the menu.
    );
}


/**
 * Debug Information
 *
 * @since 1.0.0
 *
 * @param bool $html Optional. Return as HTML or not
 *
 * @return string
 */
function starter_diagnostics_page() {
    global $wpdb;
    echo '<div class="wrap">';
    echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
    $screen         = get_current_screen();
    $site_theme     = wp_get_theme();
    $site_prefix    = $wpdb->prefix;
    $prefix_message = '$site_prefix = ' . $site_prefix;
    if ( is_multisite() ) {
        $network_prefix  = $wpdb->base_prefix;
        $prefix_message .= '<br>$network_prefix = ' . $network_prefix;
        $blog_id         = get_current_blog_id();
        $prefix_message .= '<br>$site_prefix = ' . $network_prefix . $blog_id . '_';
    }

    do_action( 'add_to_starter_diagnostics_dash' );

    echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';
    echo 'Your WordPress version is ' . get_bloginfo( 'version' );

    $my_theme = wp_get_theme();
    echo '<h4>' . $prefix_message . '</h4>';
    echo '<h4>Theme is ' . sprintf(
    __( '%1$s and is version %2$s', 'text-domain' ),
    $my_theme->get( 'Name' ),
    $my_theme->get( 'Version' )
    ) . '</h4>';
    echo '<h4>Templates found in ' . get_template_directory() . '</h4>';
    echo '<h4>Stylesheet found in ' . get_stylesheet_directory() . '</h4>';

    echo '</div>';
}

add_action( 'add_to_starter_diagnostics_dash', 'show_queries_for_starter_diagnostics' );
function show_queries_for_starter_diagnostics() {
    echo '<h3>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h3>';
    echo '<div class="add-to-diagnostics-dash" style="background:#ffebee;padding:1rem 2rem;border:.4rem solid #bbb">';
    $current_url = home_url( add_query_arg( null, null ) );
    echo '<h4>$current_url = ' . $current_url . '</h4>';
    $add_query_arg = esc_url( add_query_arg( 'foo', 'bar' ) );
    echo '<h4>$add_query_arg = ' . $add_query_arg . '</h4>';

    echo '</div>';
}

add_action( 'add_to_starter_diagnostics_dash', 'constants_for_starter_diagnostics' );
function constants_for_starter_diagnostics() {
    echo '<h3>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h3>';
    echo '<div class="add-to-diagnostics-dash" style="background:aliceblue;padding:1rem 2rem;border:.4rem solid #bbb">';
    if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
        echo '<h4>To hide ' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
        echo '<pre>get_defined_constants() ';
        print_r( get_defined_constants( true )['user'] );
        echo '</pre>';
    } else {
        echo '<h4>To show ' . ucwords( preg_replace( '/_+/', ' ', str_replace('_for_starter_diagnostics', '', __FUNCTION__ ) ) ) . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
    }
    echo '</div>';
}

add_action( 'add_to_starter_diagnostics_dash', 'server_vars_for_starter_diagnostics' );
function server_vars_for_starter_diagnostics() {
    echo '<h3>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h3>';
    echo '<div class="add-to-diagnostics-dash" style="background:mintcream;padding:1rem 2rem;border:.4rem solid #bbb">';
    if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
        echo '<h4>To hide  ' . ucwords( preg_replace( '/_+/', ' ', str_replace('_for_starter_diagnostics', '', __FUNCTION__ ) ) ) . '  <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
        echo '<pre>$_SERVER ';
        print_r( $_SERVER );
        echo '</pre>';
    } else {
        echo '<h4>To show  ' . ucwords( preg_replace( '/_+/', ' ', str_replace('_for_starter_diagnostics', '', __FUNCTION__ ) ) ) . '  <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
    }
    echo '</div>';
}

add_filter( 'gettext', 'starter_diagnostics_i18n_test', 20, 3 );

/**
 * Change comment form default field names.
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function starter_diagnostics_i18n_test( $translated_text, $untranslated_text, $domain ) {
    if ( 'starter-diagnostics-1' !== $domain ) {
        return $untranslated_text;
    }
    switch ( $untranslated_text ) {
        case 'Stylesheet found in':
            $line            = basename( __FILE__ ) . ' | ' . __LINE__;
            $translated_text = __( $line . ' shows translations working', 'starter-diagnostics' );
      break;
    }
    return $translated_text;
}
/**
 * [starter_diagnostics_gettext description]
 *
 * An error of type E_ERROR was caused in line 114 of the file /app/public/wp-content/plugins/pbrx-diag/inc/starter-diagnostic-menu.php. Error message: Cannot redeclare starter_diagnostics_gettext() (previously declared in /app/public/wp-content/plugins/pbrx-diag/inc/i18n-translations.php:33)
 *
 * Stylesheet found in
 *
 * @param  [type] $translated_text   [description]
 * @param  [type] $untranslated_text [description]
 * @param  [type] $domain            [description]
 * @return [type]                    [description]
 */
function starter_diagnostics_gettext( $translated_text, $untranslated_text, $domain ) {
    if ( 'starter-diagnostics-1' !== $domain ) {
        return $untranslated_text;
    }

    if ( 'Add more info here' === $untranslated_text ) {
        $translated_text = 'Line ' . __LINE__ . ' of ' . basename( __FILE__ ) . ' shows i18n to be working.';
    }
    return $translated_text;
}

add_filter( 'gettext', 'starter_diagnostics_gettext', 20, 3 );


register_activation_hook( __FILE__, 'starter_diagnostics_welcome_install' );
function starter_diagnostics_welcome_install() {
    set_transient( 'starter_diagnostics_activated', true, 30 );
}

add_action( 'admin_init', 'starter_diagnostics_welcome', 11 );

/**
 * Check the plugin activated transient exists if does then redirect
 */
function starter_diagnostics_welcome() {
    if ( ! get_transient( 'starter_diagnostics_activated' ) ) {
        return;
    }

    // Delete the plugin activated transient
    delete_transient( 'starter_diagnostics_activated' );

    wp_safe_redirect(
    add_query_arg(
    array(
				'page' => 'starter-diagnostics-1.php',
    ),
    admin_url( 'index.php' )
    )
    );
    exit;
}