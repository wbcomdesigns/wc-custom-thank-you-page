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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_settings_tabs = array();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wcctp_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Custom_Thank_You_Page_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Custom_Thank_You_Page_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'wcctp-font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css' );
		wp_enqueue_style( 'wcctp-select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-custom-thank-you-page-admin.css', array(), $this->version, 'all' );


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wcctp_enqueue_scripts() {

		wp_enqueue_script( 'wcctp-select2-js', plugin_dir_url( __FILE__ ) . 'js/select2.js' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-custom-thank-you-page-admin.js', array( 'jquery' ) );

		wp_localize_script(
			$this->plugin_name,
			'wcctp_admin_js_object',
			array(
				'ajaxurl' => admin_url('admin-ajax.php')
			)
		);
	}

	/**
	 * Actions performed to create a custom sub menu on loading admin_menu
	 */
	public function wcctp_add_sub_menu_page() {
		add_submenu_page( 'woocommerce', __( 'WC Custom Thank You Page Settings', WCCTP_TEXT_DOMAIN ), __( 'Custom Thank You Page', WCCTP_TEXT_DOMAIN ), 'manage_options', $this->plugin_name, array( $this, 'wcctp_admin_settings_page' ) );
	}

	/**
	 * Actions performed to create a submenu page content
	 */
	public function wcctp_admin_settings_page() {
		$tab = isset($_GET['tab']) ? $_GET['tab'] : 'wc-custom-thank-you-page';
		?>
		<div class="wrap">
			<h2><?php _e( 'Custom Thank You Page - WooCommerce Orders', WCCTP_TEXT_DOMAIN ); ?></h2>
			<?php $this->wcctp_plugin_settings_tabs(); ?>
			<form action="" method="POST" id="<?php echo $tab;?>-settings-form" enctype="multipart/form-data">
			<?php do_settings_sections( $tab );?>
			</form>
		</div>
		<?php
	}

	/**
	 * Actions performed to create tabs on the sub menu page
	 */
	public function wcctp_plugin_settings_tabs() {
		$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'wc-custom-thank-you-page';
		echo '<h2 class="nav-tab-wrapper">';
		foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_name . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * Actions performed to register general settings tab
	 */
	public function wcctp_register_general_settings() {
		$this->plugin_settings_tabs['wc-custom-thank-you-page'] = __( 'General', WCCTP_TEXT_DOMAIN );
		register_setting('wc-custom-thank-you-page', 'wc-custom-thank-you-page');
		add_settings_section('wcctp-general-section', ' ', array(&$this, 'wcctp_general_settings_content'), 'wc-custom-thank-you-page');
	}

	public function wcctp_general_settings_content() {
		if (file_exists(dirname(__FILE__) . '/inc/wcctp-general-settings.php')) {
			require_once( dirname(__FILE__) . '/inc/wcctp-general-settings.php' );
		}
	}

	public function wcctp_register_support_settings() {
		$this->plugin_settings_tabs['wcctp-support'] = __( 'Support', WCCTP_TEXT_DOMAIN );
		register_setting('wcctp-support', 'wcctp-support');
		add_settings_section('wcctp-support-section', ' ', array(&$this, 'wcctp_support_settings_content'), 'wcctp-support');
	}

	public function wcctp_support_settings_content() {
		if (file_exists(dirname(__FILE__) . '/inc/wcctp-support-settings.php')) {
			require_once( dirname(__FILE__) . '/inc/wcctp-support-settings.php' );
		}
	}
}