<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.wbcomdesigns.com
 * @since             1.0.0
 * @package           Wc_Custom_Thank_You_Page
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Custom Thank You Page
 * Plugin URI:        http://www.wbcomdesigns.com
 * Description:       This plugin creates <strong>custom Thank You page</strong> for <strong>Wooommerce Orders</strong>.
 * Version:           1.0.0
 * Author:            Wbcom Designs
 * Author URI:        http://www.wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-custom-thank-you-page
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-custom-thank-you-page-activator.php
 */
function activate_wc_custom_thank_you_page() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-custom-thank-you-page-activator.php';
	Wc_Custom_Thank_You_Page_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-custom-thank-you-page-deactivator.php
 */
function deactivate_wc_custom_thank_you_page() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-custom-thank-you-page-deactivator.php';
	Wc_Custom_Thank_You_Page_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_custom_thank_you_page' );
register_deactivation_hook( __FILE__, 'deactivate_wc_custom_thank_you_page' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-custom-thank-you-page.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_custom_thank_you_page() {
	//Define constants
	if( !defined( 'WCCTP_PLUGIN_PATH' ) ) {
		define( 'WCCTP_PLUGIN_PATH', plugin_dir_path(__FILE__) );
	}

	if( !defined( 'WCCTP_PLUGIN_URL' ) ) {
		define( 'WCCTP_PLUGIN_URL', plugin_dir_url(__FILE__) );
	}

	if( !defined( 'WCCTP_TEXT_DOMAIN' ) ) {
		define( 'WCCTP_TEXT_DOMAIN', 'wc-custom-thank-you-page' );
	}

	$plugin = new Wc_Custom_Thank_You_Page();
	$plugin->run();
}


/**
 * Check plugin requirement on plugins loaded
 * this plugin requires WooCommerce to be installed and active
 */
add_action('plugins_loaded', 'wcctp_plugin_init');
function wcctp_plugin_init() {
	$wc_active = in_array('woocommerce/woocommerce.php', get_option('active_plugins'));
	if ( current_user_can('activate_plugins') && $wc_active !== true ) {
		add_action('admin_notices', 'wcctp_plugin_admin_notice');
	} else {
		run_wc_custom_thank_you_page();
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wcctp_admin_settings_link' );
	}
}

function wcctp_plugin_admin_notice() {
	$wcctp_plugin = __( 'WooCommerce Custom Thank You Page', WCCTP_TEXT_DOMAIN );
	$wc_plugin = __( 'WooCommerce', WCCTP_TEXT_DOMAIN );

	echo '<div class="error"><p>'
	. sprintf(__('%1$s requires %2$s to function correctly. %1$s is uneffective now.', WCCTP_TEXT_DOMAIN), '<strong>' . esc_html($wcctp_plugin) . '</strong>', '<strong>' . esc_html($wc_plugin) . '</strong>')
	. '</p></div>';
	if (isset($_GET['activate'])) unset($_GET['activate']);
}

function wcctp_admin_settings_link( $links ) {
	$settings_link = array( '<a href="'.admin_url('admin.php?page=wc-custom-thank-you-page-settings').'">'.__( 'Settings', WCCTP_TEXT_DOMAIN ).'</a>' );
	return array_merge( $links, $settings_link );
}