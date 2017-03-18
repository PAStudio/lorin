<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$license_key    = get_option( 'pys_license_key' );
$license_status = get_option( 'pys_license_status' );

?>

<div class="wrap">
	<h2><?php _e( 'Activate Plugin License', 'pys' ); ?></h2>

	<form method="post" action="options.php">

		<?php wp_nonce_field( 'pys_update_license' ); ?>
		
		<?php settings_fields( 'pys_license' ); ?>
		
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row" valign="top"><?php _e( 'License Key', 'pys' ); ?></th>
				<td>
					<input id="pys_license_key" name="pys_license_key" type="text" class="regular-text"
					       value="<?php esc_attr_e( $license_key ); ?>"/>
					<label class="description"
					       for="pys_license_key"><?php _e( 'Enter your license key', 'pys' ); ?></label>
				</td>
			</tr>
			
			<?php if ( false !== $license_key ) { ?>
				
				<?php if ( $license_status !== false && $license_status == 'valid' ) : ?>
					
					<tr valign="top">

						<th scope="row" valign="top"><?php _e( 'License Status', 'pys' ); ?></th>
						<td>
							<span style="color:green;"><?php _e( 'active', 'pys' ); ?></span>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Deactivate License', 'pys' ); ?></th>
						<td>
							<p>
							<input type="submit" class="button-secondary" name="pys_license_deactivate"
							       value="<?php _e( 'Deactivate License', 'pys' ); ?>"/>
							&nbsp;&nbsp;&nbsp;

							<a href="<?php echo admin_url( 'admin.php?page=pixel-your-site' ); ?>"
							   class="button"><?php _e( 'Back to Settings', 'pys' ); ?></a>
							</p>
						</td>
					</tr>
				
				<?php else : ?>
					
					<tr>
						<th valign="top"><?php _e( 'Activate License', 'pys' ); ?></th>
						<td>
							<input type="submit" class="button-secondary" name="pys_license_activate"
							       value="<?php _e( 'Activate License', 'pys' ); ?>"/>
						</td>
					</tr>
				
				<?php endif; ?>
			
			<?php } ?>

			</tbody>
		</table>
		
		<?php submit_button(); ?>

	</form>
</div>