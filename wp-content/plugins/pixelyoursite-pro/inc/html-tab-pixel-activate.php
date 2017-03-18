<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="pys-box">
  <div class="pys-col pys-col-full">
    <h2><?php _e( 'Activate Facebook Pixel on Your Site', 'pys' ); ?></h2>

    <table class="layout">
      <tr>
        <td class="alignright"><p class="label big"><?php _e( 'Activate plugin general settings', 'pys' ); ?></p></td>
        <td>
          <input type="checkbox" name="pys[general][enabled]" value="1" class="big"
            <?php pys_checkbox_state( 'general', 'enabled' ); ?> >
        </td>
      </tr>
    </table>
    
    <button class="pys-btn pys-btn-big pys-btn-blue aligncenter"><?php _e( 'Save Settings', 'pys' ); ?></button>
    
  </div>
</div>

<div class="pys-box">
	<div class="pys-col pys-col-full">
		<h2><?php _e( 'PixelYourSite PRO License', 'pys' ); ?></h2>

		<div style="text-align: center; margin-top: 13px;">
			<a href="<?php echo admin_url('admin.php?page=pixel-your-site&pys_license_deactivate=true'); ?>" class="pys-btn pys-btn-red"><?php _e( 'Deactivate License', 'pys' ); ?></a>
		</div>

	</div>
</div>