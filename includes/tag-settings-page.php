<?php

/**
 * Hook the 'tag_settings_init' function to the 'admin_init' action.
 */
add_action( 'admin_init', 'tag_settings_init' );

/**
 * Callback function to render the content of the 'tag Options' page.
 *
 * This function checks the user's capabilities and displays the settings form.
 */
function tag_options_tab_html() {
	// Check if the current user can manage options.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Get the current options.
	$options = get_option( 'tag_options' );

	// Check if the settings were updated and display a message.
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'tag_messages', 'tag_message', __( 'Settings Saved', 'tag-settings' ), 'updated' );
	}

	// Display any error/update messages.
	settings_errors( 'tag_messages' );
	?>
<h2><?php echo esc_html( empty( $options['tag_nomenclature'] ) ? __( 'Not yet saved', 'tag-settings' ) : $options['tag_nomenclature'] ); ?>
	<?php _e( 'as Title', 'tag-settings' ); ?></h2>
	
<form action="options.php" method="post">
	<?php
			// Output security fields for the registered setting reference name "the_api_guys".
			settings_fields( 'the_api_guys' ); // Reference name for the settings fields.
			// Output setting sections and their fields.
			do_settings_sections( 'the_api_guys' ); // Reference name for the settings sections.
			// Output the save settings button.
			submit_button( __( 'Save Settings', 'tag-settings' ) );
	?>
</form>
	<?php
		// Debugging the current options.
		echo '<pre>$options ' . esc_html( print_r( $options, true ) ) . '</pre>';
	?>
	<?php
}


/**
 * Initialize custom settings and options.
 *
 * This function registers settings, sections, and fields for the 'the_api_guys' page.
 */
function tag_settings_init() {
	// Register a new setting for the 'the_api_guys' options page.
	register_setting( 'the_api_guys', 'tag_options' );

	$options = get_option( 'tag_options' );

	$settings_section_text = isset( $options['production_domain'] ) ? '//' . esc_attr( $options['production_domain'] ) : __( 'The Matrix has you when you decide to allow it.', 'tag-settings' );
	// Register a new section in the 'the_api_guys' options page.
	add_settings_section(
		'tag_developers_section',
		$settings_section_text,
		'tag_developers_section_callback',
		'the_api_guys'
	);

	// Register the fields in the 'tag_developers_section' section.
	add_settings_field(
		'tag_field_pill',
		__( 'Pill', 'tag-settings' ),
		'tag_field_pill_callback',
		'the_api_guys',
		'tag_developers_section',
		array(
			'label_for'       => 'tag_field_pill',
			'class'           => 'tag_row',
			'tag_custom_data' => 'custom',
		)
	);

	// Add the checkbox field
	add_settings_field(
		'tag_developers_companion',     // Field ID
		'Developers Companion Active', // Title
		'tag_developers_companion_callback', // Callback function to display the checkbox
		'the_api_guys',                 // Page slug
		'tag_developers_section',       // Section ID
		array(
			'label_for'       => 'tag_developers_companion',
			'class'           => 'tag_row',
			'tag_custom_data' => 'custom',
		)
	);

	add_settings_field(
		'tag_setting_input',
		__( 'Input text in this setting field', 'tag-settings' ),
		'tag_setting_callback_function',
		'the_api_guys',
		'tag_developers_section',
		array(
			'label_for' => 'tag_setting_input',
			'class'     => 'tag_row',
		)
	);

	add_settings_field(
		'tag_production_domain',
		__( 'Production Domain', 'tag-settings' ),
		'tag_production_domain_callback_function',
		'the_api_guys',
		'tag_developers_section',
		array(
			'label_for' => 'tag_production_domain',
			'class'     => 'tag_row',
		)
	);
	add_settings_field(
		'tag_nomenclature',
		__( 'Metanym (nomenclature)', 'tag-settings' ),
		'tag_nomenclature_callback_function',
		'the_api_guys',
		'tag_developers_section',
		array(
			'label_for' => 'tag_nomenclature',
			'class'     => 'tag_row',
		)
	);
}

/**
 * Undocumented function
 *
 * @return void
 */
function tag_developers_companion_callback() {
	$options = get_option( 'tag_options' );
	$checked = isset( $options['tag_developers_companion'] ) ? $options['tag_developers_companion'] : 0;
	?>
	<label for="tag_developers_companion">
		<input name="tag_options[tag_developers_companion]" type="checkbox" id="tag_developers_companion" value="1" <?php checked( 1, $checked, true ); ?> />
	</label>
	<?php
}
/**
 * Callback function for the 'Metanym' field.
 *
 * @return void
 */
function tag_nomenclature_callback_function() {
	$options = get_option( 'tag_options' );
	?>
<input type="text" name="tag_options[tag_nomenclature]"
	value="<?php echo isset( $options['tag_nomenclature'] ) ? esc_attr( $options['tag_nomenclature'] ) : 'Using Production URLs'; ?>">
	<?php
}

/**
 * Callback function for the 'Production URL' field.
 *
 * @return void
 */
function tag_production_domain_callback_function() {
	$options = get_option( 'tag_options' );
	?>
<input type="text" name="tag_options[production_domain]"
	value="<?php echo isset( $options['production_domain'] ) ? esc_attr( $options['production_domain'] ) : 'theapiguys.com'; ?>">
	<?php
}

/**
 * Callback function for the input setting field.
 *
 * @return void
 */
function tag_setting_callback_function() {
	$options = get_option( 'tag_options' );
	?>
<input type="text" name="tag_options[textarea_field_0]"
	value="<?php echo isset( $options['textarea_field_0'] ) ? esc_attr( $options['textarea_field_0'] ) : ''; ?>">

	<?php
	if ( isset( $options['textarea_field_0'] ) ) {
		$entered_text = sprintf(
		/* translators: %s: the entered text */
			'<span style="font-weight: 800;">' . __( 'You have entered: %s', 'tag-settings' ) . '</span>',
			'<span style="font-style: italic; font-weight: normal;">' . esc_html( $options['textarea_field_0'] ) . '</span>'
		);
		?>
<p class="description">
		<?php echo wp_kses_post( $entered_text ); ?>
</p>
		<?php
	}
}

/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, and callback.
 */
function tag_developers_section_callback( $args ) {
	$options               = get_option( 'tag_options' );
	$settings_section_text = isset( $options['textarea_field_0'] ) ? esc_attr( $options['textarea_field_0'] ) : 'Follow the white rabbit.';
	?>
<p id="<?php echo esc_attr( $args['id'] ); ?>">
	<?php esc_html_e( $settings_section_text, 'tag-settings' ); ?>.
</p>
	<?php
}

/**
 * Callback function for the 'Pill' field.
 *
 * @param array $args The arguments for the field including label_for and class.
 */
function tag_field_pill_callback( $args ) {
	// Get the current options.
	$options = get_option( 'tag_options' );
	?>
<select id="<?php echo esc_attr( $args['label_for'] ); ?>"
	data-custom="<?php echo esc_attr( $args['tag_custom_data'] ); ?>"
	name="tag_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
	<option value="red" <?php selected( $options[ $args['label_for'] ], 'red' ); ?>>
		<?php esc_html_e( 'red pill', 'tag-settings' ); ?>
	</option>
	<option value="blue" <?php selected( $options[ $args['label_for'] ], 'blue' ); ?>>
		<?php esc_html_e( 'blue pill', 'tag-settings' ); ?>
	</option>
</select>
	<?php
	if ( isset( $options['tag_field_pill'] ) ) {
		if ( 'blue' === $options['tag_field_pill'] ) {
			?>
<p class="description">
			<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and believe whatever you want to believe.', 'tag-settings' ); ?>
</p>
			<?php
		}
		if ( 'red' === $options['tag_field_pill'] ) {
			?>
<p class="description">
			<?php esc_html_e( 'You take the red pill and stay in Wonderland, and I show you how deep the rabbit hole goes.', 'tag-settings' ); ?>
</p>
			<?php
		}
	}
}
