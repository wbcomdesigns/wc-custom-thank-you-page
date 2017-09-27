<?php
if( !defined('ABSPATH') ) exit; //Exit if accessed firectly.

//Save general settings
if( isset( $_POST['wcctp_submit_general_settings'] ) && wp_verify_nonce( $_POST['wcctp-general-settings-nonce'], 'wcctp' ) ) {

	$settings_validations_errors = $admin_settings = array();

	$admin_settings['thankyou_message'] = stripslashes(wp_filter_post_kses(addslashes($_POST['wcctp_thankyou_message'])));

	if( isset( $_POST['wcctp_thankyou_products'] ) ) {
		$admin_settings['thankyou_products'] = $_POST['wcctp_thankyou_products'];
	}

	if( isset( $_POST['wcctp_thankyou_social_share'] ) ) {
		$admin_settings['thankyou_social_share'] = $_POST['wcctp_thankyou_social_share'];
	}

	/**
	 * Check if there are any errors
	 */
	if( !empty( $settings_validations_errors ) ) {
		$err_msg = "<div class='error is-dismissible' id='message'>";
		foreach ( $settings_validations_errors as $key => $failure ) {
			$err_msg .= "<p>".__( $failure, WCCTP_TEXT_DOMAIN )."</p>";
		}
		$err_msg .= "</div>";
		echo $err_msg;
	}

	update_option( 'wcctp_general_settings', $admin_settings );
	$success_msg = "<div class='notice updated is-dismissible' id='message'>";
	$success_msg .= "<p>".__( 'Settings Saved.', WCCTP_TEXT_DOMAIN )."</p>";
	$success_msg .= "</div>";
	echo $success_msg;
	
}

//Social websites
$social_sites = array(
	'wcctp-facebook' => 'Facebook',
	'wcctp-twitter' => 'Twitter',
	'wcctp-google-plus' => 'Google+'
);

//Fetch woocommerce products
$args = array(
	'post_type'			=> 'product',
	'posts_per_page'	=> -1,
	'post_status'		=> 'publish',
	'orderby'			=> 'title',
	'order'				=> 'ASC'
);
$woo_products = get_posts( $args );

//Retrieve Settings
$settings = get_option( 'wcctp_general_settings', true );
$thankyou_message = '';
$thankyou_products = $thankyou_social_share = array();

if( isset( $settings['thankyou_message'] ) ) {
	$thankyou_message = $settings['thankyou_message'];
}
if( isset( $settings['thankyou_products'] ) ) {
	$thankyou_products = $settings['thankyou_products'];
}
if( isset( $settings['thankyou_social_share'] ) ) {
	$thankyou_social_share = $settings['thankyou_social_share'];
}
?>
<table class="form-table wcctp-admin-page-table">
	<tbody>
		<tr>
			<th scope="row"><label for="thank-you-message"><?php _e( 'Customize Thank You Message', WCCTP_TEXT_DOMAIN );?></label></th>
			<td class="wcctp-general-settings-elements-td">
				<?php $content = $thankyou_message; wp_editor( $content, 'wcctp_thankyou_message', $settings = array('textarea_rows'=>'8') );?>
				<p class="description"><?php _e( 'This message will appear below the logo that you upload above.', WCCTP_TEXT_DOMAIN );?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="thank-you-products"><?php _e( 'Products', WCCTP_TEXT_DOMAIN );?></label></th>
			<td class="wcctp-general-settings-elements-td">
				<?php if( !empty( $woo_products ) ) {?>
					<select name="wcctp_thankyou_products[]" id="wcctp-thankyou-products" multiple>
						<option value="">--Select--</option>
						<?php foreach( $woo_products as $woo_product ) {?>
							<option value="<?php echo $woo_product->ID;?>" <?php if( !empty( $thankyou_products ) && in_array( $woo_product->ID, $thankyou_products ) ) echo 'selected="selected"';?>><?php echo $woo_product->post_title;?></option>	
						<?php }?>
					</select>
				<?php }?>
				<p class="description"><?php _e( 'These products you select here will appear after the order details.', WCCTP_TEXT_DOMAIN );?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="thank-you-social-share"><?php _e( 'Social Share Your Purchase', WCCTP_TEXT_DOMAIN );?></label></th>
			<td class="wcctp-general-settings-elements-td">
				<?php if( !empty( $social_sites ) ) {?>
					<?php foreach( $social_sites as $slug => $site ) {?>
						<input type="checkbox" name="wcctp_thankyou_social_share[]" class="wcctp-thankyou-social-share" id="<?php echo $slug;?>" value="<?php echo $slug;?>" <?php if( in_array( $slug, $thankyou_social_share ) ) echo 'checked="checked"';?>>
						<label for="<?php echo $slug;?>"><?php echo $site;?></label><br />
					<?php }?>
				<?php }?>
				<p class="description"><?php _e( 'Select the social sites where you can share your current purchase.', WCCTP_TEXT_DOMAIN );?></p>
			</td>
		</tr>
	</tbody>
</table>
<p class="submit">
	<?php wp_nonce_field( 'wcctp', 'wcctp-general-settings-nonce'); ?>
	<input type="submit" name="wcctp_submit_general_settings" class="button button-primary" value="Save Changes">
</p>