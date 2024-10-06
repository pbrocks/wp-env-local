<?php

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

$options = get_option( 'tag_options' );

$production_domain = esc_attr( $options['production_domain'] );

if ( isset( $production_domain ) && $production_domain !== $_SERVER['HTTP_HOST'] ) {
    add_filter(
        'pre_option_upload_path',
        function ( $upload_path ) {
            return '/wp-content/uploads/';
        }
    );

    add_filter(
        'pre_option_upload_url_path',
        function ( $upload_url_path ) {
            return '//' . $production_domain . '/wp-content/uploads/';
        }
    );
}