<?php

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

add_filter(
	'pre_option_upload_path',
	function ( $upload_path ) {
		return '/wp-content/uploads/';
	}
);

add_filter(
	'pre_option_upload_url_path',
	function ( $upload_url_path ) {
		return '//theapiguys.mystagingwebsite.com/wp-content/uploads/';
	}
);
