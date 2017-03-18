<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// set active tab
$pys_active_tab = 'general';
if( isset( $_REQUEST['active_tab'] ) ) {
	$pys_active_tab = $_REQUEST['active_tab'];
}

?>

<div class="wrap">
	<div class="pys-logo"></div>
	<h1><?php _e( 'Manage your Facebook Pixel like a PRO', 'pys' ); ?></h1>

	<div class="pys-body">

		<ul class="pys-menu">
			<li id="pys-menu-general" class="nav-tab <?php echo $pys_active_tab == 'general' ? 'nav-tab-active selected' : null; ?>"><?php _e( 'Facebook Pixel', 'pys' ); ?></li>
			<li id="pys-menu-posts-events" class="nav-tab <?php echo $pys_active_tab == 'posts-events' ? 'nav-tab-active selected' : null; ?>"><?php _e( 'Events', 'pys' ); ?></li>
			<li id="pys-menu-dynamic-events" class="nav-tab <?php echo $pys_active_tab == 'dynamic-events' ? 'nav-tab-active selected' : null; ?>"><?php _e( 'Dynamic Events', 'pys' ); ?></li>
			<li id="pys-menu-woo" class="nav-tab <?php echo $pys_active_tab == 'woo' ? 'nav-tab-active selected' : null; ?>"><?php _e( 'WooCommerce Setup', 'pys' ); ?></li>
			<li id="pys-menu-edd" class="nav-tab <?php echo $pys_active_tab == 'edd' ? 'nav-tab-active selected' : null; ?>">
				<?php _e( 'Easy Digital Downloads', 'pys' ); ?></li>
		</ul>
		
		<div class="pys-content">
			<form action="<?php echo admin_url('admin.php'); ?>?page=pixel-your-site" method="post">
				<input type="hidden" name="active_tab" value="<?php echo $pys_active_tab; ?>">

				<?php wp_nonce_field( 'pys_update_options' ); ?>

				<div id="pys-panel-general" class="pys-panel" <?php echo $pys_active_tab == 'general' ? 'style="display: block;"' : null; ?> >
					
					<?php include "html-box-top-general.php"; ?>
					
					<?php include "html-tab-pixel-id.php"; ?>
					<?php include "html-tab-pixel-general.php"; ?>
					<?php include "html-box-middle.php"; ?>
					<?php include "html-tab-pixel-activate.php"; ?>
					
				</div><!-- #pys-panel-general -->
							
				<div id="pys-panel-posts-events" class="pys-panel" <?php echo $pys_active_tab == 'posts-events' ? 'style="display: block;"' : null; ?> >
					
					<?php include "html-box-top-post-event.php"; ?>
					
					<?php include "html-tab-std-add-event.php"; ?>
					<?php include "html-tab-std-event-general.php"; ?>
					<?php include "html-tab-std-event-list.php"; ?>
					
					<?php include "html-box-middle.php"; ?>
					
				</div><!-- #pys-panel-posts-events -->
		
				<div id="pys-panel-dynamic-events" class="pys-panel" <?php echo $pys_active_tab == 'dynamic-events' ? 'style="display: block;"' : null; ?> >
					
					<?php include "html-box-top-dynamic.php"; ?>
					
					<?php include "html-tab-dynamic-events-general.php"; ?>
					<?php include "html-tab-dynamic-events-list.php"; ?>

					<?php include "html-box-middle.php"; ?>
					
				</div><!-- #pys-panel-dynamic-events -->

				<div id="pys-panel-woo" class="pys-panel" <?php echo $pys_active_tab == 'woo' ? 'style="display: block;"' : null; ?> >
					
					<?php include "html-box-top-woo.php"; ?>
					
					<?php if( pys_is_woocommerce_active() ): ?>
					
					<?php include "html-tab-woo-general.php"; ?>
					
					<?php else: ?>
					
					<div class="pys-box pys-box-red">
						<h3 style="text-align: center; color: #fff;"><?php _e( 'Please install and activate WooCommerce to enable WooCommerce integration.', 'pys' ); ?></h3>
					</div>
					
					<?php endif; ?>

					<?php include "html-box-middle.php"; ?>

				</div><!-- #pys-panel-woo -->

				<div id="pys-panel-edd" class="pys-panel" <?php echo $pys_active_tab == 'edd' ? 'style="display: block;"' : null; ?>>

					<?php include "html-box-top-edd.php"; ?>

					<?php if ( pys_is_edd_active() ): ?>

						<?php include "html-tab-edd.php"; ?>

					<?php else: ?>

						<div class="pys-box pys-box-red">
							<h3 style="text-align: center; color: #fff;"><?php _e( 'Please install and activate Easy Digital Downloads plugin to enable Easy Digital Downloads integration.',	'pys' ); ?></h3>
						</div>

					<?php endif; ?>

					<?php include "html-box-middle.php"; ?>

				</div><!-- #pys-panel-edd -->

			</form>
		</div><!-- .pys-content -->
		
		<?php include "html-box-bottom.php"; ?>
		
		<p class="pys-rating"><?php _e( 'If you find PixelYourSite helpful <a href="https://wordpress.org/support/view/plugin-reviews/pixelyoursite?rate=5#postform" target="_blank">click here to give us a 5 stars review</a>, because it will really help us.', 'pys' ); ?></p>
		
	</div><!-- .pys-body -->
</div><!-- .wrap -->