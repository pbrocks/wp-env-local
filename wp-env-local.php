<?php
/**
 * Plugin Name: TAG wp-env Local
 * Description: A simple plugin to view the debug.log file from the WordPress admin.
 * Version: 1.0.2
 * Author: pbrocks
 */

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

require __DIR__ . '/local-image-filters.php';

if ( ! function_exists( 'write_to_log' ) ) {
	function write_to_log( $log ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}

// Hook to add admin menu item
add_action( 'admin_menu', 'tag_add_admin_menu' );

// Function to add admin menu item
function tag_add_admin_menu() {
	add_menu_page(
		'TAG Debug Info',
		'TAG Debug Info',
		'manage_options',
		'view-debug-log',
		'view_debug_log_admin_page',
		'dashicons-admin-tools',
		3
	);
}
/**
 * Undocumented function
 *
 * @return void
 */
function view_debug_log_admin_page() {
	echo '<style>pre {white-space:pre-wrap;padding:1rem;border:3px solid white;background:aliceblue;}li{margin-left:2rem;}</style>';
	?>
<div class="wrap">
    <h1>tag Debug Info</h1>
    <h2 class="nav-tab-wrapper">
        <a href="?page=view-debug-log&tab=debug-log"
            class="nav-tab <?php echo isset( $_GET['tab'] ) && $_GET['tab'] == 'debug-log' ? 'nav-tab-active' : ''; ?>">Debug
            Log</a>
        <a href="?page=view-debug-log&tab=installation"
            class="nav-tab <?php echo isset( $_GET['tab'] ) && $_GET['tab'] == 'installation' ? 'nav-tab-active' : ''; ?>">Installation
            Info</a>
    </h2>
    <div>
        <?php
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'debug-log';
			switch ( $tab ) {
				case 'debug-log':
					tag_show_debug_log();
					break;
				case 'installation':
					tag_display_installation_info();
					break;
			}
			?>
    </div>
</div>
<?php
}

/**
 * tag_show_debug_log function
 *
 * @return void
 */
function tag_show_debug_log() {
	// Check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Path to the debug.log file
	$debug_log_path = WP_CONTENT_DIR . '/debug.log';

	// Check if the file exists
	if ( file_exists( $debug_log_path ) ) {
		// Read the file contents
		$log_contents = array_reverse( explode( "\n", trim( file_get_contents( $debug_log_path ) ) ) );

		echo '<h2>Log Contents</h2>';
		echo '<p>Debug log path: <strong>' . esc_html( $debug_log_path ) . '</strong></p>';

		// Filter the array
		$filteredArray = array_filter( $log_contents, 'doNotIncludeDeprecatedMessages' );
		echo '<h3>Filtered Log</h3><pre>' . esc_html( print_r( $filteredArray, true ) ) . '</pre>';
	} else {
		echo '<p>Debug log file not found.</p>';
	}
}

/**
 * tag_display_installation_info function
 *
 * @return void
 */
function tag_display_installation_info() {
	// Check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
    $output_path = 'You need to run `wp-env xws`';
    // wp-env install-path > ./local/plugins/wpdev-local-plugin/wp-env-path.txt

    $file_path = plugin_dir_path( __FILE__ ) . 'wp-env-path.txt';

    if (file_exists($file_path)) {
        $output_path = trim(file_get_contents($file_path));
        $log_path = trim(file_get_contents($file_path)) . '/WordPress/wp-content/debug.log'; 
    }

    echo '<h2>WP Env Install Path</h2>';
	echo '<p>Find container path = <code>wp-env install-path</code></p>';
    if (isset($output_path)) {
        echo '<pre>' . esc_html($output_path) . '</pre>';
    }
	echo '<p>This will help you can delete the debug log. If you have run <code>wp-env install-path > ./local/plugins/wp-env-local/wp-env-path.txt</code>, you should see instructions below:</p>';
    if (isset($log_path)) {
        echo '<h2>To Delete Debug Log</h2>';
        echo '<pre>';
        echo 'rm -rf ' . esc_html($log_path);
        echo '<br>touch ' . esc_html($log_path) ;
        echo '</pre>';
    }
	// echo '<div class="wrap">';
	echo '<h2>Debug Information</h2>';

	// Display any relevant installation information here
	echo '<div class="wrapper">';

	echo '<ul>';
	echo '<li>WP_DEBUG = <strong>' . ( WP_DEBUG ? 'true' : 'false' ) . '</strong></li>';
	echo '<li>WP_DEBUG_LOG = <strong>' . ( WP_DEBUG_LOG ? 'true' : 'false' ) . '</strong></li>';
	echo '<li>WP_DEBUG_DISPLAY = <strong>' . ( WP_DEBUG_DISPLAY ? 'true' : 'false' ) . '</strong></li>';
	echo '</ul>';

	echo '<h2>Container Access</h2>';
	echo '<ul>';
	echo '<li>gethostname() = <strong>' . esc_html( gethostname() ) . '</strong></li>';
	echo '<li><strong>Access Docker Container =></strong> <pre>docker exec -it ' . esc_html( gethostname() ) . ' /bin/bash</pre></li>';
	echo '</ul>';
	echo '</div>';

    
}

/**
 * doNotIncludeDeprecatedMessages function
 *
 * @param [type] $element
 * @return void
 */
function doNotIncludeDeprecatedMessages( $element ) {
	$deprecatedMessages = array(
		'PHP Deprecated:',
		'PHP Warning:',
		'Trying to access array offset on value of type bool',
	);

	foreach ( $deprecatedMessages as $message ) {
		if ( strpos( $element, $message ) !== false ) {
			return false;
		}
	}
	return true;
}
