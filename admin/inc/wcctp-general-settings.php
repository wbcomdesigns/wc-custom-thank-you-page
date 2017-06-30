<?php
if( !defined('ABSPATH') ) exit; //Exit if accessed firectly.

//Save general settings
if( isset( $_POST['wcctp_submit_general_settings'] ) && wp_verify_nonce( $_POST['wcctp-general-settings-nonce'], 'wcctp' ) ) {
	$upload_dir = wp_upload_dir();
	$settings_validations_errors = $admin_settings = array();

	$target_dir = $upload_dir['path'];

	$uploaded_file_url = '';
	if( !empty( $_FILES["wcctp_thankyou_logo"]["name"] ) ) {
		$target_file = $target_dir.'/'.basename($_FILES["wcctp_thankyou_logo"]["name"]);

		if (move_uploaded_file($_FILES["wcctp_thankyou_logo"]["tmp_name"], $target_file)) {
			$uploaded_file_url = $upload_dir['url'].'/'.basename( $_FILES["wcctp_thankyou_logo"]["name"]);
		} else {
			$settings_validations_errors[] = 'The logo was not uploaded due to some error.';
		}
	} else {
		$settings = get_option( 'wcctp_general_settings', true );
		if( isset( $settings['thankyou_logo'] ) ) {
			$uploaded_file_url = $settings['thankyou_logo'];
		}
	}
	

	$admin_settings['thankyou_logo'] = $uploaded_file_url;
	$admin_settings['thankyou_message'] = sanitize_text_field( $_POST['wcctp_thankyou_message'] );

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
$thankyou_logo = $thankyou_message = '';
$thankyou_products = $thankyou_social_share = array();
$logo_already_uploaded = 0;
if( isset( $settings['thankyou_logo'] ) ) {
	$thankyou_logo = $settings['thankyou_logo'];
	$logo_already_uploaded = 1;
}

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
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="thank-you-logo"><?php _e( 'Thank You Logo', WCCTP_TEXT_DOMAIN );?></label></th>
			<td class="wcctp-tooltips-td">
				<span data-tip="<?php _e( 'This logo will appear before the order details', WCCTP_TEXT_DOMAIN );?>" class="wcctp-tooltips">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
			</td>
			<td class="wcctp-general-settings-elements-td">
				<input name="wcctp_thankyou_logo" type="file">
				<input type="hidden" name="wcctp_logo_already_uploaded" value="<?php echo $logo_already_uploaded;?>">
			</td>
			<td>
				<?php 
				if( isset( $settings['thankyou_logo'] ) ) {
					$thankyou_logo = $settings['thankyou_logo'];

					echo '<div class="wcctp-preview-logo-box">';
					echo '<img class="wcctp-thank-you-logo-preview" src="'.$thankyou_logo.'" />';
					echo '<span class="wcctp-remove-logo"><i class="fa fa-trash-o" aria-hidden="true"></i></span>';
					echo '</div>';
				}
				?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="thank-you-message"><?php _e( 'Customize Thank You Message', WCCTP_TEXT_DOMAIN );?></label></th>
			<td class="wcctp-tooltips-td">
				<span data-tip="<?php _e( 'This message will appear below the logo that you upload above.', WCCTP_TEXT_DOMAIN );?>" class="wcctp-tooltips">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
			</td>
			<td class="wcctp-general-settings-elements-td">
				<textarea name="wcctp_thankyou_message" rows="5" cols="50" placeholder="<?php _e( 'Message', WCCTP_TEXT_DOMAIN );?>"><?php echo $thankyou_message;?></textarea>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="thank-you-products"><?php _e( 'Products', WCCTP_TEXT_DOMAIN );?></label></th>
			<td class="wcctp-tooltips-td">
				<span data-tip="<?php _e( 'These products you select here will appear after the order details.', WCCTP_TEXT_DOMAIN );?>" class="wcctp-tooltips">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
			</td>
			<td class="wcctp-general-settings-elements-td">
				<?php if( !empty( $woo_products ) ) {?>
					<select name="wcctp_thankyou_products[]" id="wcctp-thankyou-products" multiple>
						<option value="">--Select--</option>
						<?php foreach( $woo_products as $woo_product ) {?>
							<option value="<?php echo $woo_product->ID;?>" <?php if( !empty( $thankyou_products ) && in_array( $woo_product->ID, $thankyou_products ) ) echo 'selected="selected"';?>><?php echo $woo_product->post_title;?></option>	
						<?php }?>
					</select>
				<?php }?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="thank-you-social-share"><?php _e( 'Social Share Your Purchase', WCCTP_TEXT_DOMAIN );?></label></th>
			<td class="wcctp-tooltips-td">
				<span data-tip="<?php _e( 'Select the social sites where you can share your current purchase.', WCCTP_TEXT_DOMAIN );?>" class="wcctp-tooltips">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
			</td>
			<td class="wcctp-general-settings-elements-td">
				<?php if( !empty( $social_sites ) ) {?>
					<?php foreach( $social_sites as $slug => $site ) {?>
						<input type="checkbox" name="wcctp_thankyou_social_share[]" class="wcctp-thankyou-social-share" id="<?php echo $slug;?>" value="<?php echo $slug;?>" <?php if( in_array( $slug, $thankyou_social_share ) ) echo 'checked="checked"';?>>
						<label for="<?php echo $slug;?>"><?php echo $site;?></label><br />
					<?php }?>
				<?php }?>
			</td>
		</tr>
	</tbody>
</table>
<p class="submit">
	<?php wp_nonce_field( 'wcctp', 'wcctp-general-settings-nonce'); ?>
	<input type="submit" name="wcctp_submit_general_settings" class="button button-primary" value="Save Changes">
</p>