<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wc_Custom_Thank_You_Page
 * @subpackage Wc_Custom_Thank_You_Page/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Custom_Thank_You_Page
 * @subpackage Wc_Custom_Thank_You_Page/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wc_Custom_Thank_You_Page_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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
		wp_enqueue_style( 'wcctp-bxslider-css', plugin_dir_url( __FILE__ ) . 'css/jquery.bxslider.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-custom-thank-you-page-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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
		wp_enqueue_script( 'wcctp-bxslider-js', plugin_dir_url( __FILE__ ) . 'js/jquery.bxslider.js', array( 'jquery' ) );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-custom-thank-you-page-public.js', array( 'jquery' ), $this->version, false );
		
		wp_localize_script(
			$this->plugin_name,
			'wcctp_front_js_object',
			array(
				'ajaxurl' => admin_url('admin-ajax.php')
			)
		);
	}

	/**
	 * Actions performed to list products on thank you page
	 * after the order details table
	 */
	public function wcctp_thankyou_page_list_products( $order ) {
		$settings = get_option( 'wcctp_general_settings', true );
		$thankyou_products = $thankyou_social_share = array();
		//Listing products
		if( isset( $settings['thankyou_products'] ) ) {
			$thankyou_products = $settings['thankyou_products'];
			if( !empty( $thankyou_products ) ) {
				?>
				<h2><?php _e( 'You may be interested in...', WCCTP_TEXT_DOMAIN );?></h2>
				<div class="wcctp-thank-you-products-display">
					<?php foreach ( $thankyou_products as $key => $pid ) {?>
						<?php echo do_shortcode( '[product id="'.$pid.'"]' );?>
					<?php }?>
				</div>
				<?php
			}
		}

		//Social share your purchase
		if( isset( $settings['thankyou_social_share'] ) ) {
			$thankyou_social_share = $settings['thankyou_social_share'];
			$order_id = $this->wcctp_access_protected( $order, 'id' );
			?>
			<h2><?php _e( 'Share your purchase', WCCTP_TEXT_DOMAIN );?></h2>
			<div class="wcctp-thank-you-social-share-display">
				<ul class="tabs">
					<?php if( in_array( 'wcctp-facebook', $thankyou_social_share) ) {?>
						<li class="tab-link current" data-tab="tab-1">Facebook</li>
					<?php }?>

					<?php if( in_array( 'wcctp-twitter', $thankyou_social_share) ) {?>
						<li class="tab-link" data-tab="tab-2">Twitter</li>
					<?php }?>

					<?php if( in_array( 'wcctp-google-plus', $thankyou_social_share) ) {?>
						<li class="tab-link" data-tab="tab-3">Google Plus</li>
					<?php }?>
				</ul>

				<?php if( in_array( 'wcctp-facebook', $thankyou_social_share) ) {?>
					<div id="tab-1" class="tab-content current">
						<textarea rows="8" placeholder="<?php _e( 'Write something about your purchase...', WCCTP_TEXT_DOMAIN );?>" id="wcctp-purchase-share-facebook" class="wcctp-share-content"></textarea>
						<a href="javascript:void(0);" class="wcctp-share-btn" id="wcctp-share-facebook"><?php _e( 'Share', WCCTP_TEXT_DOMAIN );?></a>
					</div>
				<?php }?>

				<?php if( in_array( 'wcctp-twitter', $thankyou_social_share) ) {?>
					<div id="tab-2" class="tab-content">
						<textarea rows="8" placeholder="<?php _e( 'Write something about your purchase...', WCCTP_TEXT_DOMAIN );?>" id="wcctp-purchase-tweet-twitter" class="wcctp-share-content"></textarea>
						<a href="javascript:void(0);" class="wcctp-share-btn" id="wcctp-tweet-twitter"><?php _e( 'Tweet', WCCTP_TEXT_DOMAIN );?></a>
					</div>
				<?php }?>

				<?php if( in_array( 'wcctp-google-plus', $thankyou_social_share) ) {?>
					<div id="tab-3" class="tab-content">
						<textarea rows="8" placeholder="<?php _e( 'Write something about your purchase...', WCCTP_TEXT_DOMAIN );?>" id="wcctp-purchase-share-google-plus" class="wcctp-share-content"></textarea>
						<a href="javascript:void(0);" class="wcctp-share-btn" id="wcctp-share-google-plus"><?php _e( 'Share', WCCTP_TEXT_DOMAIN );?></a>
					</div>
				<?php }?>
			</div><!-- container -->
			<?php
		}
	}

	/**
	 * Actions performed customize the thank you message
	 */
	public function wcctp_thankyou_message( $text, $order ) {
		$settings = get_option( 'wcctp_general_settings', true );
		$thankyou_logo = $thankyou_message = '';
		if( isset( $settings['thankyou_logo'] ) ) {
			$thankyou_logo = $settings['thankyou_logo'];
		}
		if( isset( $settings['thankyou_message'] ) ) {
			$thankyou_message = $settings['thankyou_message'];
		}

		//Logo
		$img = '<img class="wcctp-thank-you-logo" src="'.$thankyou_logo.'" /><br />';
		return $img.$thankyou_message;
	}

	/**
	 * Function to access protected value
	 */
	function wcctp_access_protected( $obj, $prop ) {
		$reflection = new ReflectionClass($obj);
		$property = $reflection->getProperty($prop);
		$property->setAccessible(true);
		return $property->getValue($obj);
	}
}