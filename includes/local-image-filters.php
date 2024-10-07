<?php

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

$options = get_option( 'tag_options' );

if ( is_array( $options ) && isset( $options['production_domain'] ) ) {
	$production_domain = esc_attr( $options['production_domain'] );

	if ( $production_domain !== $_SERVER['HTTP_HOST'] ) {
	    add_filter(
	        'pre_option_upload_path',
	        function ( $upload_path ) {
	            return '/wp-content/uploads/';
	        }
	    );

	    add_filter(
	        'pre_option_upload_url_path',
	        function ( $upload_url_path ) use ( $production_domain ) {
	            return "//$production_domain/wp-content/uploads/";
	        }
	    );
	}
}