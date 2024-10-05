# 

```php
<?php
/**
 * This creates the API Guys menus in wp-admin.
 *
 * @since   0.7.4
 * @package keap_wp_functionality
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class create the Admin menus.
 *
 * @since 0.7.4
 */
class Create_WP_Admin_Menus {
    /**
     * Main construct
     *
     * @since 0.7.4
     *
     * @return void
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'apiguys_dashboard' ) );
        // add_action( 'admin_init', array( $this, 'settings_init' ) );
        add_action( 'add_to_apiguys_dash', array( $this, 'check_profit_and_loss_data' ) );
        add_action( 'add_to_apiguys_dash', array( $this, 'check_profit_and_loss_parse' ) );
        add_action( 'add_to_apiguys_dash', array( $this, 'list_profit_and_loss_parse' ) );
        add_action( 'add_to_apiguys_dash', array( $this, 'winner_loser_for_user_menu_item' ) );
        add_action( 'add_to_apiguys_subdash1', array( $this, 'check_prop_reports_position' ) );
        add_action( 'add_to_apiguys_subdash1', array( $this, 'get_prop_reports_full_position' ) );
    }

    /**
     * Add a page to the dashboard menu.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function apiguys_dashboard() {
        add_menu_page(
        __( 'Keap Settings', 'keap-connect-wp' ),
        __( 'Keap Settings', 'keap-connect-wp' ),
        'manage_options',
        'keap-settings.php',
        array( $this, 'apiguys_options_page' ),
        'dashicons-sos',
        3
        );

        if ( 2 === get_current_user_id() ) {
            add_submenu_page(
            'keap-settings.php',
            __( 'PBrocks Settings', 'keap-connect-wp' ),
            __( 'PBrocks Settings', 'keap-connect-wp' ),
            'manage_options',
            'pbrocks-settings.php',
            array( $this, 'pbrocks_dashboard' )
            );

        }
    }

    /**
     * Add a page to the dashboard menu.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function pbrocks_dashboard() {
        echo '<div class="wrap">';
        ?>
<style type="text/css">
#wpwrap {
    background: aliceblue;
}

.add-to-apiguys-dash {
    background: mintcream;
    margin: .1rem 0;
    border: 3px solid lightgray;
    padding: .31rem 1.2rem;
}

pre {
    white-space: pre-wrap;
}
</style>
<?php
        echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
        $token      = get_transient( 'pr_api_token' );
        echo '<h2>PBrocks token ' . $token . '</h2>';
        echo '<h3>_ChallengeInitializedDateOptions = ' . ( memb_getContactField( '_ChallengeInitializedDateOptions' ) ?: 'Uh Oh, no data' ) . '</h3>';
        // echo '<h4>_ChallengeInitializedDateOptions ' . wp_date( 'n-j-Y', strtotime( memb_getContactField( '_ChallengeInitializedDateOptions' ) ) . '</h4>';

        echo '<ul class="notes">';
        echo '<li>Remember to update menus on Production</li>';
        echo '</ul>';
        $options = get_account_id();
        echo '<pre>keap_settings ' . print_r( $options, true ) . '</pre>';
        ?>
<pre>
	add_shortcode( 'quick-users-shortcode', array( $this, 'quick_users_shortcode' ) );
	add_shortcode( 'winners-vs-losers-shortcode', array( $this, 'winners_vs_losers_shortcode' ) );
	add_shortcode( 'days-traded-shortcode', array( $this, 'determine_days_traded_shortcode' ) );
	add_shortcode( 'winner-loser-shortcode', array( $this, 'render_winner_loser_shortcode' ) );
	add_shortcode( 'profit-target-shortcode', array( $this, 'profit_target_shortcode' ) );
	add_shortcode( 'profit-n-loss-shortcode', array( $this, 'profit_and_loss_shortcode' ) );
	add_shortcode( 'round-turns-shortcode', array( $this, 'round_turns_shortcode' ) );
	add_shortcode( 'daily-max-shortcode', array( $this, 'daily_max_vs_overall_max_shortcode' ) );
	add_shortcode( 'daily-gain-shortcode', array( $this, 'daily_gain_shortcode' ) );
	add_shortcode( 'daily-loss-shortcode', array( $this, 'daily_loss_shortcode' ) );
	</pre>
<?php
        $this->get_formatted_prop_reports_position();
        $this->winner_loser_for_user_menu_item();
        $this->check_parse_profit_and_loss_data_for_charts();
        $this->check_gain_loss_nonzero_realized();
        $this->check_profit_and_loss_data();
        $this->check_profit_and_loss_parse();
        $this->check_prop_reports_position();
        $this->get_prop_reports_full_position();
        $this->list_profit_and_loss_parse();
        echo '<div>winners-vs-losers-shortcode ' . apply_shortcodes( '[]winners-vs-losers-shortcode' ) . '</div>';
        $winner_loser_for_user = determine_winner_loser_for_user();
        echo '<pre>winner_loser_for_user ' . print_r( $winner_loser_for_user, true ) . '</pre>';
        echo '</div>';
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
    public function apiguys_sub0_screen() {
        echo '<div class="wrap">';
        ?>
<style type="text/css">
#wpwrap {
    background: aliceblue;
}

.add-to-apiguys-dash {
    background: mintcream;
    margin: .1rem 0;
    border: 3px solid lightgray;
    padding: .31rem 1.2rem;
}
</style>
<?php
        echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
        $keap_user = get_user_meta( get_current_user_id(), 'keap_user_name', true );

        $props_report_token = get_user_meta( get_current_user_id(), 'prop_reports_token_' . $keap_user, true );
        echo '<p>' . $props_report_token . '</p>';
        do_action( 'add_to_apiguys_subdash0' );
        echo '</div>';
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
    public function apiguys_sub1_screen() {
        echo '<div class="wrap">';
        ?>
<style type="text/css">
#wpwrap {
    background: aliceblue;
}

.add-to-apiguys-dash {
    background: mintcream;
    margin: .1rem 0;
    border: 3px solid lightgray;
    padding: .31rem 1.2rem;
}
</style>
<?php
        echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
        echo '<p>' . wp_date( 'Y_m_d__H:i' ) . '</p>';

        // echo '<p>' . $props_report_token . '</p>';
        do_action( 'add_to_apiguys_subdash1' );
        echo '</div>';
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
    public function apiguys_sub2_screen() {
        echo '<div class="wrap">';
        ?>
<style type="text/css">
#wpwrap {
    background: aliceblue;
}

.add-to-apiguys-dash {
    background: mintcream;
    margin: .1rem 0;
    border: 3px solid lightgray;
    padding: .31rem 1.2rem;
}
</style>
<?php
        echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
        do_action( 'add_to_apiguys_subdash2' );
        echo '</div>';
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
    public function apiguys_dashboard_screen() {
        ?>
<style type="text/css">
#wpwrap {
    background: aliceblue;
}

.add-to-apiguys-dash {
    background: mintcream;
    margin: .1rem 0;
    border: 3px solid lightgray;
    padding: .31rem 1.2rem;
}
</style>
<?php
        echo '<div class="wrap">';
        echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
        $account_id = ( get_user_meta( get_current_user_id(), 'keap_account_id', true ) ?: 'no Account ID' );
        echo '<h4>PBrocks says ' . $account_id . '</h4>';
        do_action( 'add_to_apiguys_dash' );
        ?>
<h3>References</h3>
<ul>
    <li><a href="https://keap.memberium.com/how-to-integrate-memberium-with-your-developers-php-code/"
            target="_blank">https://keap.memberium.com/how-to-integrate-memberium-with-your-developers-php-code/</a>
    </li>
    <li><a href="https://keap.memberium.com/documentation/shortcodes/"
            target="_blank">https://keap.memberium.com/documentation/shortcodes/</a></li>
</ul>
<?php
        echo '</div>';
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
    public function adding_to_apiguys_dashboard_screen() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . ' | current_filter() = ' . current_filter() . '</h2>';
        $keap_user = get_user_meta( get_current_user_ID(), 'keap_user_name', true );
        $token       = get_user_meta( get_current_user_id(), 'prop_reports_token_' . $keap_user, true );
        echo '<div class="add-to-apiguys-dash">';
        echo '<h3>' . __( 'Add more info here', 'keap-connect-wp' ) . '</h3>';
        echo '</div>';
    }

    /**
     * [determine_positions_for_user description]
     *
     * @return [type] [description]
     */
    public function determine_positions_for_user() {
        $api_url     = 'https://keaptrading.propreports.com/api.php';

        $today = new \DateTime( 'today' );
        $minus_30 = clone $today;
        $minus_30->modify( '-30 day' );
        $start_date = $minus_30->format( 'Y-m-d' );
        $end_date   = $today->format( 'Y-m-d' );
        $keap_user = get_user_meta( get_current_user_id(), 'keap_user_name', true );
        $password    = get_user_meta( get_current_user_id(), 'keap_account_password', true );

        $props_report_token = get_user_meta( get_current_user_id(), 'prop_reports_token_' . $keap_user, true );

        if ( ! $props_report_token ) {
            $props_report_token = get_prop_reports_token_wp( $keap_user, $password );
        }
        $token                 = $props_report_token;
        $account_id            = get_user_meta( get_current_user_id(), 'keap_account_id', true );
        $props_report_position = noargs_prop_reports_token_wp( $api_url, $start_date, $end_date, $token, $account_id );

        if ( 'Invalid or expired token.' === $props_report_position ) {
            $token                 = create_prop_reports_token_wp( $keap_user, $password );
            $props_report_position = noargs_prop_reports_token_wp( $api_url, $start_date, $end_date, $token, $account_id );
        }
        return $props_report_position;
    }

    /**
     * [get_formatted_prop_reports_position description]
     *
     * @return [type] [description]
     */

    /**
     * [winner_loser_for_user_menu_item description]
     *
     * @return [type] [description]
     */
    public function winner_loser_for_user_menu_item() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            $winner_loser = 'determine_winner_loser_for_user()';
            echo '<pre>winner_loser() ' . print_r( $winner_loser, true ) . '</pre>';

            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [check_gain_loss_nonzero_realized description]
     *
     * @return [type] [description]
     */
    public function check_gain_loss_nonzero_realized() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            $gain_loss_nonzero_realized = 'calculate_gain_loss_nonzero_realized()';
            echo '<pre>gain_loss_nonzero_realized ' . print_r( $gain_loss_nonzero_realized, true ) . '</pre>';
            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [check_profit_and_loss_data description]
     *
     * @return [type] [description]
     */
    public function check_profit_and_loss_data() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            echo 'get_profit_by_day_transient()';
            $profit_and_loss = 'get_profit_by_day_transient()';
            echo '<pre>profit_and_loss() ' . print_r( $profit_and_loss, true ) . '</pre>';

            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [check_profit_and_loss_data description]
     *
     * @return [type] [description]
     */
    public function check_profit_and_loss_parse() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            echo 'parse_profit_and_loss_data_for_charts()';
            $profit_and_loss = 'parse_profit_and_loss_data_for_charts()';
            echo '<pre>profit_and_loss() ' . print_r( $profit_and_loss, true ) . '</pre>';

            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [list_profit_and_loss_data description]
     *
     * @return [type] [description]
     */
    public function list_profit_and_loss_parse() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            echo 'list_profit_and_loss_data_for_charts()';
            $profit_and_loss = 'list_profit_and_loss_data_for_charts()';
            echo '<pre>profit_and_loss() ' . print_r( $profit_and_loss, true ) . '</pre>';

            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [check_prop_reports_position description]
     *
     * @return [type] [description]
     */
    public function check_prop_reports_position() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            $props_report_position_array = '$login_actions_class->check_positions_with_prop_reports_token()';
            echo '<pre>get_prop_reports_full_position() ' . print_r( $props_report_position_array, true ) . '</pre>';

            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [check_parse_profit_and_loss_data_for_charts description]
     *
     * @return [type] [description]
     */
    public function check_parse_profit_and_loss_data_for_charts() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            $print_variable = 'parse_profit_and_loss_data_for_charts()';
            echo '<pre>parse_profit_and_loss_data_for_charts() ' . print_r( $print_variable, true ) . '</pre>';

            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [get_prop_reports_full_position description]
     *
     * @return [type] [description]
     */
    public function get_prop_reports_full_position() {
        echo '<h2>' . __( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'keap-connect-wp' ) . '</h2>';
        echo '<div class="add-to-apiguys-dash">';
        if ( isset( $_REQUEST['action'] ) && __FUNCTION__ === $_REQUEST['action'] ) {
            echo '<h4>To hide ' . __FUNCTION__ . ' <a href="' . esc_url( remove_query_arg( 'action' ) ) . '"><button>Click Here</button></a></h4>';
            $props_report_position_array = '$login_actions_class->check_positions_with_prop_reports_token()';
            echo '<pre>get_prop_reports_full_position() ' . print_r( $props_report_position_array, true ) . '</pre>';

            echo '<p style="color:firebrick">' . __FILE__ . ':' . __LINE__ . '</p>';
        } else {
            echo '<h4>To show ' . __FUNCTION__ . ' <a href="' . esc_url( add_query_arg( 'action', __FUNCTION__ ) ) . '"><button>Click Here</button></a></h4>';
        }
        echo '</div>';
    }

    /**
     * [settings_init description]
     *
     * @return [type] [description]
     */
    public function settings_init() {
        register_setting( 'keap_page', 'keap_settings' );

        add_settings_section(
        'keap_page_section',
        __( '======', 'keap-connect-wp' ),
        array( $this, 'settings_section_callback' ),
        'keap_page'
        );

        add_settings_field(
        'keap_api_name',
        __( 'Keap API Name (keap_api_name)', 'keap-connect-wp' ),
        array( $this, 'keap_api_name_render' ),
        'keap_page',
        'keap_page_section'
        );

        add_settings_field(
        'keap_api_password',
        __( 'Keap API Name (keap_api_password)', 'keap-connect-wp' ),
        array( $this, 'keap_api_password_render' ),
        'keap_page',
        'keap_page_section'
        );

        add_settings_field(
        'keap_options_dashboard_upgrade_link',
        __( 'Options Dashboard Upgrade Link Page (keap_options_dashboard_upgrade_link)', 'keap-connect-wp' ),
        array( $this, 'keap_options_dashboard_upgrade_link_render' ),
        'keap_page',
        'keap_page_section'
        );
        add_settings_field(
        'keap_stocks_dashboard_upgrade_link',
        __( 'Stocks Dashboard Upgrade Link Page (keap_stocks_dashboard_upgrade_link)', 'keap-connect-wp' ),
        array( $this, 'keap_stocks_dashboard_upgrade_link_render' ),
        'keap_page',
        'keap_page_section'
        );
    }

    /**
     * [keap_api_name_render description]
     *
     * @return [type] [description]
     */
    public function keap_api_name_render() {
        $options = get_option( 'keap_settings' );
        ?>
<input type='text' name='keap_settings[keap_api_name]' value='<?php echo $options['keap_api_name']; ?>'>
<?php
    }

    /**
     * [keap_api_password_render description]
     *
     * @return [type] [description]
     */
    public function keap_api_password_render() {
        $options = get_option( 'keap_settings' );
        ?>
<input type='password' name='keap_settings[keap_api_password]' value='<?php echo $options['keap_api_password']; ?>'>
<?php
    }

    /**
     * [keap_options_dashboard_upgrade_link description]
     *
     * @return [type] [description]
     */
    public function keap_options_dashboard_upgrade_link_render() {
        $options = get_option( 'keap_settings' );
        wp_dropdown_pages(
        array(
        'name'              => 'keap_settings[keap_options_dashboard_upgrade_link]',
        'show_option_none'  => __( '— Select —' ),
        'option_none_value' => '0',
        'selected'          => $options['keap_options_dashboard_upgrade_link'],
        )
        );
    }


    /**
     * [keap_options_dashboard_upgrade_link description]
     *
     * @return [type] [description]
     */
    public function keap_stocks_dashboard_upgrade_link_render() {
        $options = get_option( 'keap_settings' );
        wp_dropdown_pages(
        array(
        'name'              => 'keap_settings[keap_stocks_dashboard_upgrade_link]',
        'show_option_none'  => __( '— Select —' ),
        'option_none_value' => '0',
        'selected'          => $options['keap_stocks_dashboard_upgrade_link'],
        )
        );
    }

    /**
     * [settings_section_callback description]
     *
     * @return [type] [description]
     */
    public function settings_section_callback() {
        ?>
<style type="text/css">
p.description {
    font-size: .9rem;
    padding-right: 2rem;
}

.notes {
    font-size: .79rem;
    color: maroon;
    list-style: disc;
    margin-left: 2rem;
}
</style>
<?php
        echo '<p class="description">' . __( 'This section is for global settings used in the dashboard. If the Prop Reports API user\'s password changes, it must be updated here immediately. Otherwise, everyone will lose access to their reports.', 'keap-connect-wp' ) . '</p>';
    }

    /**
     * [apiguys_options_page description]
     *
     * @return [type] [description]
     */
    public function apiguys_options_page() {
        echo '<div class="wrap">';
        $options = get_option( 'keap_settings' );        ?>
<style type="text/css">
p.description {
    font-size: .9rem;
    padding-right: 2rem;
}

.notes {
    font-size: .79rem;
    color: maroon;
    list-style: disc;
    margin-left: 2rem;
}

#wpwrap {
    background: aliceblue;
}

.add-to-apiguys-dash {
    background: mintcream;
    margin: .1rem 1rem;
    border: 3px solid lightgray;
    padding: .31rem 1.2rem;
}
</style>
<form action='options.php' method='post'>
    <h2>Keap Settings</h2>
    <?php
        settings_fields( 'keap_page' );
        do_settings_sections( 'keap_page' );
        submit_button();
        ?>
</form>
<?php
        echo '<div>';
        echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
        do_action( 'add_to_apiguys_dash' );
        echo '</div>';
        ?>
<?php
        echo '<h2>PBrocks only</h2>';
        echo '<ul class="notes">';
        echo '<li>Remember to update menus on Production</li>';
        echo '</ul>';
        $this->get_prop_reports_full_position();
        echo '<pre>keap_settings ' . print_r( $options, true ) . '</pre>';
        echo '</div>';
    }
}

new Create_WP_Admin_Menus();
```
