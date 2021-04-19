<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wc_Custom_Thank_You_Page
 * @subpackage Wc_Custom_Thank_You_Page/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Custom_Thank_You_Page
 * @subpackage Wc_Custom_Thank_You_Page/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wc_Custom_Thank_You_Page_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $plugin_settings_tabs;

	/**
	 * Define Plugin slug.
	 *
	 * @author  wbcomdesigns
	 * @since   1.0.0
	 * @access  private
	 * @var     $plugin_slug contains plugin slug.
	 */
	private $plugin_slug = 'wc-custom-thank-you-page';


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name          = $plugin_name;
		$this->version              = $version;
		$this->plugin_settings_tabs = array();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wcctp_enqueue_styles() {
		if ( strpos( $_SERVER['REQUEST_URI'], $this->plugin_name ) !== false ) {
			wp_enqueue_style( $this->plugin_name . '-font-awesome', WCCTP_PLUGIN_URL . 'admin/css/font-awesome.min.css' );
			wp_enqueue_style( $this->plugin_name . '-selectize-css', WCCTP_PLUGIN_URL . 'admin/css/selectize.css' );
			wp_enqueue_style( $this->plugin_name, WCCTP_PLUGIN_URL . 'admin/css/wc-custom-thank-you-page-admin.css' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wcctp_enqueue_scripts() {
		if ( strpos( $_SERVER['REQUEST_URI'], $this->plugin_name ) !== false ) {
			wp_enqueue_script( $this->plugin_name . '-selectize', WCCTP_PLUGIN_URL . 'admin/js/selectize.min.js' );
			wp_enqueue_script( $this->plugin_name, WCCTP_PLUGIN_URL . 'admin/js/wc-custom-thank-you-page-admin.js', array( 'jquery' ) );

			wp_localize_script(
				$this->plugin_name,
				'wcctp_admin_js_object',
				array(
					'ajaxurl'                     => admin_url( 'admin-ajax.php' ),
					'products_select_placeholder' => __( 'Select Products', WCCTP_TEXT_DOMAIN ),
				)
			);
		}
	}

	/**
	 * Actions performed to create a custom sub menu on loading admin_menu
	 */
	public function wcctp_add_sub_menu_page() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {

				add_menu_page( esc_html__( 'WB Plugins', WCCTP_TEXT_DOMAIN ), esc_html__( 'WB Plugins', WCCTP_TEXT_DOMAIN ), 'manage_options', 'wbcomplugins', array( $this, 'wcctp_admin_settings_page' ), 'dashicons-lightbulb', 59 );
				add_submenu_page( 'wbcomplugins', esc_html__( 'General', WCCTP_TEXT_DOMAIN ), esc_html__( 'General', WCCTP_TEXT_DOMAIN ), 'manage_options', 'wbcomplugins' );
		}
		add_submenu_page( 'wbcomplugins', __( 'WC Custom Thank You Page Settings', WCCTP_TEXT_DOMAIN ), __( 'Custom Thank You Page', WCCTP_TEXT_DOMAIN ), 'manage_options', $this->plugin_name, array( $this, 'wcctp_admin_settings_page' ) );
	}

	/**
	 * Actions performed to create a submenu page content
	 */
	public function wcctp_admin_settings_page() {
		global $allowedposttags;
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wc-custom-thank-you-page';
		?>
		<div class="wrap">
			<div class="wcpq-pro-header">
				<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				<h1 class="wbcom-plugin-heading">
				<?php esc_html_e( 'WC Custom Thank You Page', WCCTP_TEXT_DOMAIN ); ?>
				</h1>
			</div>
				<?php settings_errors(); ?>
			<div class="wbcom-admin-settings-page">
				<?php
				$this->wcctp_plugin_settings_tabs();
				do_settings_sections( $tab );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Actions performed to create tabs on the sub menu page
	 */
	public function wcctp_plugin_settings_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wc-custom-thank-you-page';
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . esc_attr( $active ) . '" href="?page=' . esc_attr( $this->plugin_slug ) . '&tab=' . esc_attr( $tab_key ) . '">' . esc_html( $tab_caption, 'wb-todo' ) . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * Actions performed to register general settings tab
	 */
	public function wcctp_register_general_settings() {
		$this->plugin_settings_tabs['wc-custom-thank-you-page'] = __( 'General', WCCTP_TEXT_DOMAIN );
		register_setting( 'wc-custom-thank-you-page', 'wc-custom-thank-you-page' );
		add_settings_section( 'wcctp-general-section', ' ', array( &$this, 'wcctp_general_settings_content' ), 'wc-custom-thank-you-page' );
	}

	public function wcctp_general_settings_content() {
		if ( file_exists( dirname( __FILE__ ) . '/inc/wcctp-general-settings.php' ) ) {
			require_once dirname( __FILE__ ) . '/inc/wcctp-general-settings.php';
		}
	}

	public function wcctp_register_support_settings() {
		$this->plugin_settings_tabs['wcctp-support'] = __( 'Support', WCCTP_TEXT_DOMAIN );
		register_setting( 'wcctp-support', 'wcctp-support' );
		add_settings_section( 'wcctp-support-section', ' ', array( &$this, 'wcctp_support_settings_content' ), 'wcctp-support' );
	}

	public function wcctp_support_settings_content() {
		if ( file_exists( dirname( __FILE__ ) . '/inc/wcctp-support-settings.php' ) ) {
			require_once dirname( __FILE__ ) . '/inc/wcctp-support-settings.php';
		}
	}
}
