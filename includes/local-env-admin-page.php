<?php

if ( ! function_exists( 'write_to_log' ) ) {
	function write_to_log( $log ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( sanitize_text_field( $log ) ); // Sanitize log entry
		}
	}
}

// Hook to add admin menu item
add_action( 'admin_menu', 'tag_add_admin_menu' );

// Function to add admin menu item
function tag_add_admin_menu() {
	add_menu_page(
		esc_html__( 'TAG Debug Info', 'tag-plugin' ),  // Localize
		esc_html__( 'TAG Debug Info', 'tag-plugin' ),  // Localize
		'manage_options',
		'tag-debug-info',
		'tag_debug_info_admin_page',
		'dashicons-rest-api',
		3
	);
}

/**
 * Render admin page
 *
 * @return void
 */
function tag_debug_info_admin_page() {
	echo '<style>pre {white-space:pre-wrap;padding:1rem;border:3px solid white;background:aliceblue;}li{margin-left:2rem;}</style>';
	?>
<div class="wrap">
    <h1><?php esc_html_e( 'TAG Debug Info', 'tag-plugin' ); ?></h1> <!-- Localize -->
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo esc_url( add_query_arg( 'tab', 'debug-log', admin_url( 'admin.php?page=tag-debug-info' ) ) ); ?>"
            class="nav-tab <?php echo isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) == 'debug-log' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Debug Log', 'tag-plugin' ); ?>
        </a>
        <a href="<?php echo esc_url( add_query_arg( 'tab', 'installation', admin_url( 'admin.php?page=tag-debug-info' ) ) ); ?>"
            class="nav-tab <?php echo isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) == 'installation' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Installation Info', 'tag-plugin' ); ?>
        </a>
    </h2>
    <div>
        <?php
			$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'debug-log';  // Sanitize input
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
 * Show debug log function
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

		echo '<h2>' . esc_html__( 'Log Contents', 'tag-plugin' ) . '</h2>';
		echo '<p>' . esc_html__( 'Debug log path:', 'tag-plugin' ) . ' <strong>' . esc_html( $debug_log_path ) . '</strong></p>';

		// Filter the array
		$filteredArray = array_filter( $log_contents, 'doNotIncludeDeprecatedMessages' );
		echo '<h3>' . esc_html__( 'Filtered Log', 'tag-plugin' ) . '</h3><pre>' . esc_html( print_r( $filteredArray, true ) ) . '</pre>';
	} else {
		echo '<p>' . esc_html__( 'Debug log file not found.', 'tag-plugin' ) . '</p>';
	}
}

/**
 * Display installation info
 *
 * @return void
 */
function tag_display_installation_info() {
	// Check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
    $output_path = esc_html__( 'You need to run `wp-env install-path > ./local/info-to-pass/wp-env-path.txt`', 'tag-plugin' );

    $file_path = WP_CONTENT_DIR . '/info-passed/wp-env-path.txt';

    if ( file_exists( $file_path ) ) {
        $output_path = trim( file_get_contents( $file_path ) );
        $log_path = trim( file_get_contents( $file_path ) ) . '/WordPress/wp-content/debug.log'; 
    }

    echo '<h2>' . esc_html__( 'WP Env Install Path', 'tag-plugin' ) . '</h2>';
	echo '<p>' . esc_html__( 'Find container path =', 'tag-plugin' ) . ' <code>wp-env install-path</code></p>';
    if ( isset( $output_path ) ) {
        echo '<pre>' . esc_html( $output_path ) . '</pre>';
    }
	echo '<p>' . esc_html__( 'This will help you delete the debug log. If you have run `wp-env install-path > ./local/passed-info/info-to-pass/wp-env-path.txt`, you should see instructions below:', 'tag-plugin' ) . '</p>';
    if ( isset( $log_path ) ) {
        echo '<h2>' . esc_html__( 'To Delete Debug Log', 'tag-plugin' ) . '</h2>';
        echo '<pre>';
        echo esc_html( 'rm -rf ' . $log_path );
        echo '<br>' . esc_html( 'touch ' . $log_path );
        echo '</pre>';
    }

	echo '<div class="wrapper">';
	echo '<h2>' . esc_html__( 'Container Access', 'tag-plugin' ) . '</h2>';
	echo '<ul>';
	echo '<li>' . esc_html__( 'gethostname() = ', 'tag-plugin' ) . '<strong>' . esc_html( gethostname() ) . '</strong></li>';
	echo '<li><strong>' . esc_html__( 'Access Docker Container =>', 'tag-plugin' ) . '</strong> <pre>docker exec -it ' . esc_html( gethostname() ) . ' /bin/bash</pre></li>';
	echo '</ul>';

	echo '<h2>' . esc_html__( 'Debug Information', 'tag-plugin' ) . '</h2>';
	echo '<ul>';
	echo '<li>' . esc_html__( 'WP_DEBUG =', 'tag-plugin' ) . ' <strong>' . esc_html( WP_DEBUG ? 'true' : 'false' ) . '</strong></li>';
	echo '<li>' . esc_html__( 'WP_DEBUG_LOG =', 'tag-plugin' ) . ' <strong>' . esc_html( WP_DEBUG_LOG ? 'true' : 'false' ) . '</strong></li>';
	echo '<li>' . esc_html__( 'WP_DEBUG_DISPLAY =', 'tag-plugin' ) . ' <strong>' . esc_html( WP_DEBUG_DISPLAY ? 'true' : 'false' ) . '</strong></li>';
	echo '</ul>';
	echo '</div>';
}

/**
 * Filter deprecated messages
 *
 * @param string $element
 * @return bool
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