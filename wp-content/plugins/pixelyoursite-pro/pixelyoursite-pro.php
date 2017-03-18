<?php
/*
	Plugin Name: PixelYourSite PRO
	Description: With PixelYourSite Pro you can add the new Facebook Pixel with just a few clicks, create Standard Events, or use Dynamic Events. Complete WooCommerce integration with out of the box Facebook Dynamic Product Ads setup.
	Plugin URI: http://www.pixelyoursite.com/
	Author: PixelYourSite
	Author URI: http://www.pixelyoursite.com
	License URI: http://www.pixelyoursite.com/pixel-your-site-pro-license
	Text Domain: pys
	Version: 5.0.8
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PYS_PRO_VERSION', '5.0.8');
define( 'PYS_STORE_URL', 'http://www.pixelyoursite.com' );
define( 'PYS_ITEM_NAME', 'PixelYourSite Pro' );

require_once( 'inc/common.php' );
require_once( 'inc/common-edd.php' );
require_once( 'inc/core.php' );
require_once( 'inc/core-edd.php' );
require_once( 'inc/ajax-standard.php' );
require_once( 'inc/ajax-dynamic.php' );
require_once( 'inc/admin_notices.php' );

if ( ! class_exists( 'PYS_Plugin_Updater' ) ) {

	// load our custom updater if it doesn't already exist
	include plugin_dir_path( __FILE__ ) . 'inc/pys-plugin-updater.php';

}

if ( ! function_exists( 'pys_pro_init' ) ) {

	add_action( 'plugins_loaded', 'pys_pro_init' );
	function pys_pro_init() {

		load_plugin_textdomain( 'pys', false, basename( dirname( __FILE__ ) ) . '/languages/' );

		$options = get_option( 'pixel_your_site' );
		if ( ! $options || ! isset( $options['general']['pixel_id'] ) || empty( $options['general']['pixel_id'] ) ) {
			pys_initialize_settings();
		}

		if ( version_compare( get_option( 'pixel_your_site_pro_version' ), PYS_PRO_VERSION ) ) {
			pys_migrate_to_current();
		}


		if ( is_admin() || pys_get_option( 'general', 'enabled' ) == false || pys_is_disabled_for_role() || ! pys_get_option( 'general', 'pixel_id' ) ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', 'pys_public_scripts' );
		add_action( 'wp_head', 'pys_head_comments', 10 );

		/**
		 * Hooks call priority:
		 * wp_head:
		 * 1 - pixel events options;
		 * 2 - init event;
		 * 3 - all events evaluation;
		 * 4 - output events;
		 * 9 (20) - enqueue public scripts (head/footer);
		 * wp_footer
		 */

		add_action( 'wp_head', 'pys_output_options', 1 );
		add_action( 'wp_head', 'pys_pixel_init_event', 2 );

		add_action( 'wp_head', 'pys_page_view_event', 3 );
		add_action( 'wp_head', 'pys_general_event', 3 );
		add_action( 'wp_head', 'pys_search_event', 3 );
		add_action( 'wp_head', 'pys_time_on_page_event', 3 );
		add_action( 'wp_head', 'pys_standard_events', 3 );
		add_action( 'wp_head', 'pys_woocommerce_events', 3 );
		add_action( 'wp_head', 'pys_edd_events', 3 );

		add_action( 'wp_head', 'pys_output_js_events_code', 4 );
		add_action( 'wp_head', 'pys_output_custom_events_code', 4 );

		add_action( 'wp_footer', 'pys_output_noscript_code', 10 );
		add_action( 'wp_footer', 'pys_output_woo_ajax_events_code', 10 );
		add_action( 'wp_footer', 'pys_output_edd_ajax_events_code', 10 );

		// dynamic events content filters
		if ( pys_get_option( 'dyn', 'enabled', false ) == true ) {

			add_action( 'wp_head', 'pys_output_dynamic_events_code', 4 );

			if ( pys_get_option( 'dyn', 'enabled_on_content', false ) ) {
				add_filter( 'the_content', 'pys_process_content', 1000 );
			}

			if ( pys_get_option( 'dyn', 'enabled_on_content', false ) ) {
				add_filter( 'widget_text', 'pys_process_content' );
			}

		}

		// additional matching
		if ( pys_get_option( 'general', 'enable_advance_matching' ) == true ) {

			//@see: https://www.facebook.com/help/ipad-app/606443329504150
			add_filter( 'pys_pixel_init_params', 'pys_add_general_additional_matching_params', 10, 1 );
			add_filter( 'pys_pixel_init_params', 'pys_add_purchase_additional_matching_params', 10, 1 );

		}

		// woocommerce shop page add_to_cart simple and external products, paypal order button events
		if ( pys_get_option( 'woo', 'enabled' ) ) {

			if( pys_get_option( 'woo', 'on_add_to_cart_btn' ) || pys_get_option( 'woo', 'enable_aff_event' ) ) {
				add_filter( 'woocommerce_loop_add_to_cart_link', 'pys_add_code_to_woo_cart_link', 10, 2 );
			}

			if( pys_get_option( 'woo', 'on_thank_you_page' ) ) {
				add_filter( 'woocommerce_order_button_html', 'pys_add_code_to_order_button', 10, 1 );
			}

		}
		
		## add pixel code to EDD add_to_cart buttons
		if( pys_get_option( 'edd', 'enabled' ) && pys_get_option( 'edd', 'on_add_to_cart_btn', false ) ) {
			
			add_filter( 'edd_purchase_link_args', 'pys_edd_purchase_link_args', 10, 1 );
			
		}

		add_filter( 'pys_event_params', 'pys_add_domain_param', 10, 2 );

	}

}

if ( ! function_exists( 'pys_admin_menu' ) ) {

	function pys_admin_menu() {

		if ( false == current_user_can( 'manage_options' ) ) {
			return;
		}

		add_menu_page( 'PixelYourSite PRO', 'PixelYourSite PRO', 'manage_options', 'pixel-your-site', 'pys_admin_page_callback', plugins_url( 'pixelyoursite-pro/img/favicon.png' ) );

	}

	add_action( 'admin_menu', 'pys_admin_menu' );

}

if ( ! function_exists( 'pys_restrict_admin_pages' ) ) {

	function pys_restrict_admin_pages() {

		$screen = get_current_screen();

		if ( $screen->id == 'toplevel_page_pixel-your-site' & false == current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Sorry, you are not allowed to access this page.' ) );
		}

	}

	add_action( 'current_screen', 'pys_restrict_admin_pages' );

}

if ( ! function_exists( 'pys_admin_page_callback' ) ) {

	function pys_admin_page_callback() {

		## update plugin options
		if ( ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'pys_update_options' ) && isset( $_POST['pys'] ) ) {
			update_option( 'pixel_your_site', $_POST['pys'] );
		}

		## delete standard or dynamic events
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'pys_delete_events'
			&& isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'pys_delete_events' )
			&& isset( $_GET['events_ids'] ) && isset( $_GET['events_type'] )
		) {

			pys_delete_events( $_GET['events_ids'], $_GET['events_type'] );

			$redirect_to = add_query_arg(
				array(
					'page'       => 'pixel-your-site',
					'active_tab' => $_GET['events_type'] == 'standard' ? 'posts-events' : 'dynamic-events',
				),
				admin_url( 'admin.php' )
			);

			wp_safe_redirect( $redirect_to );

		}

		$license_status = get_option( 'pys_license_status' );
		if ( $license_status !== false && $license_status == 'valid' && ! isset( $_REQUEST['pys_license_deactivate'] ) ) {
			include( 'inc/html-admin.php' );
		} else {
			include( 'inc/html-license.php' );
		}

	}

}

if ( ! function_exists( 'pys_public_scripts' ) ) {

	function pys_public_scripts() {

		$in_footer = (bool) pys_get_option( 'general', 'in_footer', false );
		
		wp_enqueue_script( 'jquery-bind-first', plugins_url( 'js/jquery.bind-first-0.2.3.min.js', __FILE__ ), array( 'jquery' ), PYS_PRO_VERSION, $in_footer );
		wp_enqueue_script( 'js-cookie', plugins_url( 'js/js.cookie-2.1.3.min.js', __FILE__ ), array(), PYS_PRO_VERSION, $in_footer );
		wp_enqueue_script( 'pys-public', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery', 'js-cookie', 'jquery-bind-first' ), PYS_PRO_VERSION, $in_footer );

	}

}

if ( ! function_exists( 'pys_admin_scripts' ) ) {

	add_action( 'admin_enqueue_scripts', 'pys_admin_scripts' );
	function pys_admin_scripts() {

		// include only on plugin admin pages
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'pixel-your-site' ) {

			add_thickbox();

			wp_enqueue_style( 'pys', plugins_url( 'css/admin.css', __FILE__ ), array(), PYS_PRO_VERSION );
			wp_enqueue_script( 'pys-admin', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), PYS_PRO_VERSION );

		}

	}

}

if ( ! function_exists( 'pys_pro_plugin_activated' ) ) {

	register_activation_hook( __FILE__, 'pys_pro_plugin_activated' );
	function pys_pro_plugin_activated() {

		if ( false == is_admin() || false == current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'pixelyoursite/facebook-pixel-master.php' ) ) {
			wp_die( __( 'Please deactivate PixelYourSite Free Version First.', 'pys' ), __( 'Plugin Activation', 'pys' ) );
		}

		$options = get_option( 'pixel_your_site' );
		if ( ! $options || ! isset( $options['general']['pixel_id'] ) || empty( $options['general']['pixel_id'] ) ) {
			pys_initialize_settings();
		}

	}

}

if ( ! function_exists( 'pys_register_license_option' ) ) {

	add_action( 'admin_init', 'pys_register_license_option' );
	function pys_register_license_option() {

		// creates our settings in the options table
		register_setting( 'pys_license', 'pys_license_key', 'pys_sanitize_license' );

	}

}

if ( ! function_exists( 'pys_activate_license' ) ) {

	add_action( 'admin_init', 'pys_activate_license' );
	function pys_activate_license() {

		if ( false == isset( $_POST['pys_license_activate'] ) || false == current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! isset( $_POST['_wpnonce'] ) || wp_verify_nonce( $_POST['_wpnonce'], 'pys_update_license' ) ) {
			return;
		}

		$license = trim( get_option( 'pys_license_key' ) );

		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( PYS_ITEM_NAME ),
			'url'        => home_url()
		);

		$response = wp_remote_post( PYS_STORE_URL, array(
			'timeout'   => 120,
			'sslverify' => false,
			'body'      => $api_params
		) );

		if ( is_wp_error( $response ) ) {

			//@todo: show error message in admin notices
			return;

		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "valid" or "invalid"
		update_option( 'pys_license_status', $license_data->license );

	}

}

if ( ! function_exists( 'pys_deactivate_license' ) ) {

	add_action( 'admin_init', 'pys_deactivate_license' );
	function pys_deactivate_license() {

		if ( false == isset( $_POST['pys_license_deactivate'] ) || false == current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! isset( $_POST['_wpnonce'] ) || wp_verify_nonce( $_POST['_wpnonce'], 'pys_update_license' ) ) {
			return;
		}

		$license = trim( get_option( 'pys_license_key' ) );

		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( PYS_ITEM_NAME ),
			'url'        => home_url()
		);

		$response = wp_remote_post( PYS_STORE_URL, array(
			'timeout'   => 120,
			'sslverify' => false,
			'body'      => $api_params
		) );

		if ( is_wp_error( $response ) ) {

			//@todo: show error message in admin notices
			return;

		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' ) {
			delete_option( 'pys_license_status' );
		}

	}

}

if ( ! function_exists( 'pys_plugin_updater' ) ) {

	add_action( 'admin_init', 'pys_plugin_updater', 0 );
	function pys_plugin_updater() {

		$license_key = trim( get_option( 'pys_license_key' ) );

		new PYS_Plugin_Updater( PYS_STORE_URL, __FILE__, array(
				'version'   => PYS_PRO_VERSION,
				'license'   => $license_key,
				'item_name' => PYS_ITEM_NAME,
				'author'    => 'PixelYourSite'
			)
		);

	}

}

if ( ! function_exists( 'pys_initialize_settings' ) ) {

	function pys_initialize_settings() {

		if ( false == current_user_can( 'manage_options' ) ) {
			return;
		}

		$defaults = pys_get_default_options();
		update_option( 'pixel_your_site', $defaults );

		// migrate settings from old versions
		if ( get_option( 'woofp_admin_settings' ) || get_option( 'fpmp_facebookpixel_admin_settings' ) ) {

			require_once( 'inc/migrate.php' );
			pys_migrate_from_22x();

		}

		update_option( 'pixel_your_site_pro_version', PYS_PRO_VERSION );

	}

}

if ( ! function_exists( 'pys_migrate_to_current' ) ) {

	function pys_migrate_to_current() {

		if ( false == current_user_can( 'manage_options' ) ) {
			return;
		}

		$options = get_option( 'pixel_your_site' );

		if ( version_compare( get_option( 'pixel_your_site_pro_version' ), '3.2.0' ) ) {

			/**
			 * Option renamed and migrated to another section.
			 *
			 * @since: 3.2.0
			 */
			if ( isset( $options['woo']['purchase_additional_matching'] ) ) {
				$options['general']['enable_advance_matching'] = $options['woo']['purchase_additional_matching'];
				unset( $options['woo']['purchase_additional_matching'] );
			} else {
				$options['general']['enable_advance_matching'] = 1;
			}

		}

		update_option( 'pixel_your_site', $options );
		update_option( 'pixel_your_site_pro_version', PYS_PRO_VERSION );

	}

}