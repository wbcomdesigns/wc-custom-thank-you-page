<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>      
<div class="wcctp-adming-setting">
    <div class="wcctp-tab-header">
        <h3><?php _e( 'Have some questions?', WCCTP_TEXT_DOMAIN );?></h3>
    </div>

    <div class="wcctp-admin-settings-block">
        <div id="wcctp-settings-tbl">
            <div class="wcctp-admin-row">
                <div>
                   <button class="wcctp-accordion">
                    <?php _e( 'What plugin does this plugin require?', WCCTP_TEXT_DOMAIN );?>
                    </button>
                    <div class="panel">
                        <p> 
                            <?php _e( 'As the name of the plugin justifies, this plugin modifies the layout of the <strong>Thank You / Order Received & My Account</strong> page, this plugin requires <strong>WooCommerce</strong> plugin to be installed and active.', WCCTP_TEXT_DOMAIN );?>
                        </p>
                        <p> 
                            <?php _e( 'You\'ll also get an admin notice and the plugin will become ineffective if the required plugin will not be there.', WCCTP_TEXT_DOMAIN );?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="wcctp-admin-row">
                <div>
                   <button class="wcctp-accordion">
                    <?php _e( 'How to go for any custom development?', WCCTP_TEXT_DOMAIN );?>
                    </button>
                    <div class="panel">
                        <p>
                            <?php _e( 'If you need additional help you can contact us for <a href="https://wbcomdesigns.com/contact/" target="_blank" title="Custom Development by Wbcom Designs">Custom Development</a>.', WCCTP_TEXT_DOMAIN );
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>