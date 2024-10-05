# Starter WordPress Options Page

```php
/**
 * Hook the 'starter_options_page' function to the 'admin_menu' action.
 */
add_action( 'admin_menu', 'starter_options_page' );

/**
 * Add the top-level menu page for the starter options.
 *
 * This function adds the 'Starter Options' page to the WordPress dashboard
 * with the necessary capabilities.
 */
function starter_options_page() {
    add_menu_page(
        __( 'Starter Page', 'starter-settings' ), // Localized title for the page.
        __( 'Starter Options', 'starter-settings' ), // Localized title for the menu.
        'manage_options', // Capability required to access the page.
        'starter-page', // Unique slug for the page.
        'starter_options_page_html', // Callback function to display the content.
        'dashicons-palmtree', // Icon for the menu.
        3 // Position in the menu.
    );
}

/**
 * Callback function to render the content of the 'Starter Options' page.
 *
 * This function checks the user's capabilities and displays the settings form.
 */
function starter_options_page_html() {
    // Check if the current user can manage options.
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Get the current options.
    $options = get_option( 'starter_options' );

    // Check if the settings were updated and display a message.
    if ( isset( $_GET['settings-updated'] ) ) {
        add_settings_error( 'starter_messages', 'starter_message', __( 'Settings Saved', 'starter-settings' ), 'updated' );
    }

    // Display any error/update messages.
    settings_errors( 'starter_messages' );
    ?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <h2><?php echo esc_html( empty( $options['starter_nomenclature'] ) ? __( 'Not yet saved', 'starter-settings' ) : $options['starter_nomenclature'] ); ?>
        <?php _e( 'as Title', 'starter-settings' ); ?></h2>
    <form action="options.php" method="post">
        <?php
            // Output security fields for the registered setting "starter".
            settings_fields( 'starter' );
            // Output setting sections and their fields.
            do_settings_sections( 'starter' );
            // Output the save settings button.
            submit_button( __( 'Save Settings', 'starter-settings' ) );
        ?>
    </form>
    <?php
        // Debugging the current options.
        echo '<pre>$options ' . esc_html( print_r( $options, true ) ) . '</pre>';
    ?>
</div>
<?php
}

/**
 * Hook the 'starter_settings_init' function to the 'admin_init' action.
 */
add_action( 'admin_init', 'starter_settings_init' );

/**
 * Initialize custom settings and options.
 *
 * This function registers settings, sections, and fields for the 'Starter' page.
 */
function starter_settings_init() {
    // Register a new setting for the 'starter' options page.
    register_setting( 'starter', 'starter_options' );

    // Register a new section in the 'starter' options page.
    add_settings_section(
        'starter_section_developers',
        __( 'The Matrix has you.', 'starter-settings' ),
        'starter_section_developers_callback',
        'starter'
    );

    // Register the fields in the 'starter_section_developers' section.
    add_settings_field(
        'starter_field_pill',
        __( 'Pill', 'starter-settings' ),
        'starter_field_pill_callback',
        'starter',
        'starter_section_developers',
        array(
            'label_for'         => 'starter_field_pill',
            'class'             => 'starter_row',
            'starter_custom_data' => 'custom',
        )
    );

    add_settings_field(
        'starter_setting_input',
        __( 'Input text in this setting field', 'starter-settings' ),
        'starter_setting_callback_function',
        'starter',
        'starter_section_developers',
        array(
            'label_for' => 'starter_setting_input',
            'class'     => 'starter_row',
        )
    );

    add_settings_field(
        'starter_nomenclature',
        __( 'Metanym', 'starter-settings' ),
        'starter_nomenclature_callback_function',
        'starter',
        'starter_section_developers',
        array(
            'label_for' => 'starter_nomenclature',
            'class'     => 'starter_row',
        )
    );
}

/**
 * Callback function for the 'Metanym' field.
 *
 * @return void
 */
function starter_nomenclature_callback_function() {
    $options = get_option( 'starter_options' );
    ?>
<input type="text" name="starter_options[starter_nomenclature]"
    value="<?php echo isset( $options['starter_nomenclature'] ) ? esc_attr( $options['starter_nomenclature'] ) : 'Events'; ?>">
<?php
}

/**
 * Callback function for the input setting field.
 *
 * @return void
 */
function starter_setting_callback_function() {
    $options = get_option( 'starter_options' );
    ?>
<input type="text" name="starter_options[textarea_field_0]"
    value="<?php echo isset( $options['textarea_field_0'] ) ? esc_attr( $options['textarea_field_0'] ) : ''; ?>">
<?php
}

/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, and callback.
 */
function starter_section_developers_callback( $args ) {
    ?>
<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'starter-settings' ); ?>
</p>
<?php
}

/**
 * Callback function for the 'Pill' field.
 *
 * @param array $args The arguments for the field including label_for and class.
 */
function starter_field_pill_callback( $args ) {
    // Get the current options.
    $options = get_option( 'starter_options' );
    ?>
<select id="<?php echo esc_attr( $args['label_for'] ); ?>"
    data-custom="<?php echo esc_attr( $args['starter_custom_data'] ); ?>"
    name="starter_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <option value="red" <?php selected( $options[ $args['label_for'] ], 'red' ); ?>>
        <?php esc_html_e( 'red pill', 'starter-settings' ); ?>
    </option>
    <option value="blue" <?php selected( $options[ $args['label_for'] ], 'blue' ); ?>>
        <?php esc_html_e( 'blue pill', 'starter-settings' ); ?>
    </option>
</select>
<?php
    if ( 'blue' === $options['starter_field_pill']) {
        ?>
<p class="description">
    <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and believe whatever you want to believe.', 'starter-settings' ); ?>
</p>
<?php
    }
    if ( 'red' === $options['starter_field_pill']) {
        ?>
<p class="description">
    <?php esc_html_e( 'You take the red pill and stay in Wonderland, and I show you how deep the rabbit hole goes.', 'starter-settings' ); ?>
</p>
<?php
    }
}
```
