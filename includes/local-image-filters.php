<?php

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

$options = get_option( 'tag_options' );
if ( is_array( $options ) && isset( $options['production_domain'] ) ) {
	// Access the array value
	$production_domain = esc_attr( $options['production_domain'] );

	if ( isset( $production_domain ) ) {
		add_filter(
			'pre_option_upload_path',
			function () {
				return '/wp-content/uploads/';
			}
		);

		add_filter(
			'pre_option_upload_url_path',
			function ( $production_domain ) {
				return '//' . $production_domain . '/wp-content/uploads/';
			}
		);
	}
}
