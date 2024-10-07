<?php

// Hook to add admin menu item
add_action( 'admin_menu', 'tag_add_admin_menu', 9 );
add_action( 'admin_enqueue_scripts', 'tag_env_enqueue_admin_styles' );

if ( ! function_exists( 'write_to_log' ) ) {
	function write_to_log( $log ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( sanitize_text_field( $log ) ); // Sanitize log entry
		}
	}
}

/**
 * Add admin menu item function
 *
 * @return void
 */
function tag_add_admin_menu() {
	add_menu_page(
		esc_html__( 'TAG Debug Info', 'tag-plugin' ),
		esc_html__( 'The API Guys', 'tag-plugin' ),
		'manage_options',
		'the-api-guys',
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
	echo '<style></style>';
	?>
<div class="wrap">
	<h1 class="logo"><?php esc_html_e( 'Developer\'s Companion', 'keap-connect-wp' ); ?></h1>
	<?php

	// Get the current options.
	$options = get_option( 'tag_options' );

	if ( $options ) :
		$home_url = home_url();
		?>
	<h3 style="color: maroon;">Developer's Companion Active</h3>

		<?php
	endif;

	?>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo esc_url( add_query_arg( 'tab', 'debug-log', admin_url( 'admin.php?page=the-api-guys' ) ) ); ?>"
			class="nav-tab <?php echo isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) === 'debug-log' ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Debug Log', 'tag-plugin' ); ?>
		</a>
		<a href="<?php echo esc_url( add_query_arg( 'tab', 'installation', admin_url( 'admin.php?page=the-api-guys' ) ) ); ?>"
			class="nav-tab <?php echo isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) === 'installation' ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Installation Info', 'tag-plugin' ); ?>
		</a>
		<a href="<?php echo esc_url( add_query_arg( 'tab', 'images', admin_url( 'admin.php?page=the-api-guys' ) ) ); ?>"
			class="nav-tab <?php echo isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) === 'images' ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Image URLs', 'tag-plugin' ); ?>
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
			case 'images':
				tag_options_tab_html();
				break;
		}
		?>
	</div>
</div>
	<?php
}
function tag_env_enqueue_admin_styles( $hook ) {
	// Replace 'tag_env_page' with the actual slug of your plugin's admin page
	$plugin_page = 'toplevel_page_tag_env_page';

	// Check if we're on the plugin's admin page
	if ( $hook !== $plugin_page ) {
		return;
	}

	// Enqueue your CSS file
	wp_enqueue_style(
		'my-plugin-admin-style', // Handle for the style
		plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css', // Path to your CSS file
		array(), // Dependencies, if any
		'1.0.0', // Version
		'all' // Media
	);
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
		$log_path    = trim( file_get_contents( $file_path ) ) . '/WordPress/wp-content/debug.log';
	}

	echo '<h2>' . esc_html__( 'WP Env Install Path', 'tag-plugin' ) . '</h2>';
	echo '<p>' . esc_html__( 'Find container path =', 'tag-plugin' ) . ' <code>wp-env install-path</code></p>';
	if ( isset( $output_path ) ) {
		echo '<pre>' . esc_html( $output_path ) . '</pre>';
	}

	printf(
		'<p>%s: <code>%s</code>, %s.</p>',
		esc_html__( 'This will help you delete the debug log. If you have run', 'tag-plugin' ),
		esc_html__( 'wp-env install-path > ./local/info-to-pass/wp-env-path.txt', 'tag-plugin' ),
		esc_html__( 'you should see instructions below', 'tag-plugin' )
	);
	echo '<div class="wrapper">';

	if ( isset( $log_path ) ) {
		echo '<h2>' . esc_html__( 'To Delete Debug Log', 'tag-plugin' ) . '</h2>';
		echo '<pre>';
		echo esc_html( 'rm -rf ' . $log_path );
		echo '<br>' . esc_html( 'touch ' . $log_path );
		echo '</pre>';
	}

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
