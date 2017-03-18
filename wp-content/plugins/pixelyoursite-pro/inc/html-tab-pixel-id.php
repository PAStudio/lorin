<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="pys-box">
	<div class="pys-col pys-col-full">
		<h2 class="section-title"><?php _e( 'Add your Pixel ID:', 'pys' ); ?></h2>

		<table class="layout">
			<tr class="tall">
				<td class="legend"><p class="label"><?php _e( 'Add your Facebook Pixel ID:', 'pys' ); ?></p></td>
				<td>
					<input type="text" name="pys[general][pixel_id]"
					       placeholder="<?php _e( 'Enter your Facebook Pixel ID', 'pys' ); ?>"
					       value="<?php echo pys_get_option( 'general', 'pixel_id' ); ?>">
					<span class="help"><?php _e( 'Where to find the Pixel ID? <a href="http://www.pixelyoursite.com/facebook-pixel-plugin-help" target="_blank">Click here for help</a>', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="pys[general][add_traffic_source]"
					       value="1" <?php pys_checkbox_state( 'general', 'add_traffic_source' ); ?> ><?php _e( 'Track traffic source and type', 'pys' ); ?>
					<span class="help"><?php _e( 'Add traffic source as <code>traffic_source</code> param on all your events. URL tags (UTM) will be also tracked for each event. Use this to segment your Custom Audiences and improve your retargeting (retarget people based on when they come from, like Google, Facebook or a particular ad, for example).', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="pys[general][enable_advance_matching]" value="1"
						<?php pys_checkbox_state( 'general', 'enable_advance_matching' ); ?> ><?php _e( 'Enable Advance Matching', 'pys' ); ?>
					<span class="help"><?php _e( 'Advance Matching can lead to 10% increase in attributed conversions and 20% increase in reach of retargeting campaigns - <a href="http://www.pixelyoursite.com/enable-advance-matching-woocommerce" target="_blank">Click to read more</a>', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td class="legend"><p class="label"><?php _e( 'Output Pixel Code to:', 'pys' ); ?></p></td>
				<td>
					<p style="margin-top: 0;"><input type="radio" name="pys[general][in_footer]" value="0" <?php echo pys_radio_state( 'general', 'in_footer', 0 ); ?> ><?php _e( 'Head', 'pys' ); ?></p>
					<p style="margin-top: 0;"><input type="radio" name="pys[general][in_footer]" value="1" <?php echo pys_radio_state( 'general', 'in_footer', 1 ); ?>><?php _e( 'Footer', 'pys' ); ?></p>
				</td>
			</tr>

		</table>
	</div>
</div>