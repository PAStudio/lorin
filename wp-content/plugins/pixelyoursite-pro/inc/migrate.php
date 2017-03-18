<?php

/**
 * Migrate old settings format without data lost.
 *
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Override all options with old data and remove old option from db.
 * @since 3.0.0
 */
if( !function_exists( 'pys_migrate_from_22x' ) ) {

	function pys_migrate_from_22x() {

		// update license only new is not exist
		if ( get_option( 'pys_license_key' ) == false ) {

			$old_license = get_option( 'fbpmp_license_key' );
			$old_status  = get_option( 'fbpmp_license_status' );

			if ( $old_license ) {
				update_option( 'pys_license_key', $old_license, true );
				update_option( 'pys_license_status', $old_status, true );

				delete_option( 'fbpmp_license_key' );
				delete_option( 'fbpmp_license_status' );
			}

		}

		$options    = get_option( 'pixel_your_site' );
		$std_events = array();
		$dyn_events = array();

		$old_free = get_option( 'woofp_admin_settings' );
		$old_pro  = get_option( 'fpmp_facebookpixel_admin_settings' );

		// try to migrate from old pro, then from old free, or use new pro defaults if old settings are not exist
		if( isset( $old_pro ) && !empty( $old_pro ) ) {

			// general settings
			$options['general']['pixel_id'] = isset( $old_pro['facebookpixel']['ID'] ) ? $old_pro['facebookpixel']['ID'] : '';
			$options['general']['enabled']  = isset( $old_pro['facebookpixel']['activate'] ) ? $old_pro['facebookpixel']['activate'] : 0;

			// standard events enable/disable
			$options['std']['enabled']      = isset( $old_pro['standardevent']['activate'] ) ? $old_pro['standardevent']['activate'] : 0;

			// dynamic events
			$options['dyn']['enabled']            = isset( $old_pro['custom_event']['activate'] ) ? $old_pro['custom_event']['activate'] : 0;
			$options['dyn']['enabled_on_content'] = isset( $old_pro['custom_event']['enable_post_contents'] ) ? $old_pro['custom_event']['enable_post_contents'] : 0;
			$options['dyn']['enabled_on_widget']  = isset( $old_pro['custom_event']['enable_widget_text'] ) ? $old_pro['custom_event']['enable_widget_text'] : 0;

			// woo events settings
			$options['woo']['enabled']            = isset( $old_pro['woocommerce']['activate'] ) ? $old_pro['woocommerce']['activate'] : 0;

			$options['woo']['content_id']         = isset( $old_pro['woocommerce']['contentid'] ) ?  $old_pro['woocommerce']['contentid'] : $options['woo']['content_id'];

			$options['woo']['on_view_content']    = isset( $old_pro['woocommerce_dynamic']['ViewContent'] ) ? $old_pro['woocommerce_dynamic']['ViewContent'] : 0;
			$options['woo']['on_add_to_cart_btn'] = isset( $old_pro['woocommerce_dynamic']['ProductAddToCart'] ) ? $old_pro['woocommerce_dynamic']['ProductAddToCart'] : 0;
			$options['woo']['on_cart_page']       = isset( $old_pro['woocommerce_dynamic']['AddToCart'] ) ? $old_pro['woocommerce_dynamic']['AddToCart'] : 0;
			$options['woo']['on_checkout_page']   = isset( $old_pro['woocommerce_dynamic']['InitiateCheckout'] ) ? $old_pro['woocommerce_dynamic']['InitiateCheckout'] : 0;
			$options['woo']['on_thank_you_page']  = isset( $old_pro['woocommerce_dynamic']['Purchase'] ) ? $old_pro['woocommerce_dynamic']['Purchase'] : 0;
			$options['woo']['enable_aff_event']   = isset( $old_pro['woocommerce_affiliate']['activate'] ) ? $old_pro['woocommerce_affiliate']['activate'] : 0;
			$options['woo']['enable_paypal_event']= isset( $old_pro['woocommerce_paypal']['activate'] ) ? $old_pro['woocommerce_paypal']['activate'] : 0;

			// woo events additional params
			$options['woo']['enable_view_content_value'] = isset( $old_pro['woocommerce_dynamic']['ViewContent_value'] ) ? $old_pro['woocommerce_dynamic']['ViewContent_value'] : 0;
			$options['woo']['enable_add_to_cart_value']  = isset( $old_pro['woocommerce_dynamic']['ProductAddToCart_value'] ) ? $old_pro['woocommerce_dynamic']['ProductAddToCart_value'] : 0;
			$options['woo']['enable_checkout_value']     = isset( $old_pro['woocommerce_dynamic']['InitiateCheckout_value'] ) ? $old_pro['woocommerce_dynamic']['InitiateCheckout_value'] : 0;

			// woo affiliate event params
			if ( isset( $old_pro['woocommerce_affiliate']['affiliate_value'] ) && !empty( $old_pro['woocommerce_affiliate']['affiliate_value'] ) ) {

				if( isset( $old_pro['woocommerce_affiliate']['affiliate_global'] ) && !empty( $old_pro['woocommerce_affiliate']['affiliate_global'] ) ) {

					$options['woo']['aff_value_option'] = 'global';
					$options['woo']['aff_global_value'] = $old_pro['woocommerce_affiliate']['affiliate_global'];

				} else {

					$options['woo']['aff_value_option'] = 'price';

				}

			} else {

				$options['woo']['aff_value_option'] = 'none';

			}

			$options['woo']['aff_predefined_value'] = isset( $old_pro['woocommerce_affiliate']['affiliate_event'] ) && !empty( $old_pro['woocommerce_affiliate']['affiliate_event'] ) ? $old_pro['woocommerce_affiliate']['affiliate_event'] : $options['woo']['aff_predefined_value'];

			if ( isset( $old_pro['woocommerce_affiliate']['affiliate_custom'] ) && !empty( $old_pro['woocommerce_affiliate']['affiliate_custom'] ) ) {
				$options['woo']['aff_event']        = 'custom';
				$options['woo']['aff_custom_value'] = $old_pro['woocommerce_affiliate']['affiliate_custom'];
			}

			// woo paypal event params
			if ( isset( $old_pro['woocommerce_paypal']['event_value'] ) && !empty( $old_pro['woocommerce_paypal']['event_value'] ) ) {

				if( isset( $old_pro['woocommerce_paypal']['event_global'] ) && !empty( $old_pro['woocommerce_paypal']['event_global'] ) ) {

					$options['woo']['pp_value_option'] = 'global';
					$options['woo']['pp_global_value'] = $old_pro['woocommerce_paypal']['event_global'];

				} else {

					$options['woo']['pp_value_option'] = 'price';

				}

			} else {

				$options['woo']['pp_value_option'] = 'none';

			}

			$options['woo']['pp_predefined_value'] = isset( $old_pro['woocommerce_paypal']['event_name'] ) && !empty( $old_pro['woocommerce_paypal']['event_name'] ) ? $old_pro['woocommerce_paypal']['event_name'] : $options['woo']['pp_predefined_value'];;

			if ( isset( $old_pro['woocommerce_paypal']['event_custom'] ) && !empty( $old_pro['woocommerce_paypal']['event_custom'] ) ) {
				$options['woo']['pp_event']        = 'custom';
				$options['woo']['pp_custom_value'] = $old_pro['woocommerce_paypal']['event_custom'];
			}

			// copy standard events
			unset( $old_pro['standardevent']['activate'] );
			if ( isset( $old_pro['standardevent']['pageurl'] ) ) {
				$events_count = count( $old_pro['standardevent']['pageurl'] );

				$i = 0;
				while ( $i < $events_count ) {

					// do not copy empty events
					if( empty( $old_pro['standardevent']['pageurl'][$i] ) ) {
						$i++;
						continue;
					}

					$id = uniqid() . $i; // concat used to avoid equal ids

					foreach ( $old_pro['standardevent'] as $key => $value ) {
						$std_events[ $id ][ $key ] = $value[ $i ];
					}

					if ( isset( $std_events[ $id ]['code'] ) && ! empty( $std_events[ $id ]['code'] ) ) {
						$std_events[ $id ]['eventtype'] = 'CustomCode';
					}

					$i ++;
				}

			}

			// copy dynamic events
			unset( $old_pro['custom_event']['activate'] );
			unset( $old_pro['custom_event']['enable_post_contents'] );
			unset( $old_pro['custom_event']['enable_widget_text'] );
			if ( isset( $old_pro['custom_event']['trigger_type'] ) ) {
				$events_count = count( $old_pro['custom_event']['trigger_type'] );

				$i = 0;
				while ( $i < $events_count ) {

					$id = uniqid() . $i; // concat used to avoid equal ids

					//@todo: skip empty dynamic events (same as std)

					foreach ( $old_pro['custom_event'] as $key => $value ) {

						if ( $key == 'trigger_type' && $value[$i] == 'url' ) {
							$value[ $i ] = 'URL';
						}

						if ( $key == 'trigger_type' && $value[$i] == 'css_selector' ) {
							$value[ $i ] = 'CSS';
						}

						if ( $key == 'hreflink' ) {
							$key = 'url';
						}

						if ( $key == 'selector' ) {
							$key = 'css';
						}

						$dyn_events[ $id ][ $key ] = $value[ $i ];

					}

					if ( isset( $dyn_events[ $id ]['code'] ) && ! empty( $dyn_events[ $id ]['code'] ) ) {
						$dyn_events[ $id ]['eventtype'] = 'CustomCode';
					}

					$i ++;

				}

			}

		} elseif( isset( $old_free ) && !empty( $old_free ) ) {

			// general settings
			$options['general']['pixel_id'] = isset( $old_free['facebookpixel']['ID'] ) ? $old_free['facebookpixel']['ID'] : '';
			$options['general']['enabled']  = isset( $old_free['facebookpixel']['activate'] ) ? $old_free['facebookpixel']['activate'] : 0;

			// standard events enable/disable
			$options['std']['enabled'] = isset( $old_free['standardevent']['activate'] ) ? $old_free['standardevent']['activate'] : 0;

			// woo events settings
			$options['woo']['enabled']            = isset( $old_free['woocommerce']['activate'] ) ? $old_free['woocommerce']['activate'] : 0;
			$options['woo']['on_view_content']    = isset( $old_free['woocommerce']['events']['ViewContent'] ) ? $old_free['woocommerce']['events']['ViewContent'] : 0;
			$options['woo']['on_add_to_cart_btn'] = isset( $old_free['woocommerce']['events']['ProductAddToCart'] ) ? $old_free['woocommerce']['events']['ProductAddToCart'] : 0;
			$options['woo']['on_cart_page']       = isset( $old_free['woocommerce']['events']['AddToCart'] ) ? $old_free['woocommerce']['events']['AddToCart'] : 0;
			$options['woo']['on_checkout_page']   = isset( $old_free['woocommerce']['events']['InitiateCheckout'] ) ? $old_free['woocommerce']['events']['InitiateCheckout'] : 0;
			$options['woo']['on_thank_you_page']  = isset( $old_free['woocommerce']['events']['Purchase'] ) ? $old_free['woocommerce']['events']['Purchase'] : 0;

			// copy standard events
			unset( $old_free['standardevent']['activate'] );
			if ( isset( $old_free['standardevent']['pageurl'] ) ) {
				$events_count = count( $old_free['standardevent']['pageurl'] );

				$i = 0;
				while ( $i < $events_count ) {

					// do not copy empty events
					if( empty( $old_free['standardevent']['pageurl'][$i] ) ) {
						$i++;
						continue;
					}

					$id = uniqid() . $i; // concat used to avoid equal ids

					foreach ( $old_free['standardevent'] as $key => $value ) {
						$std_events[ $id ][ $key ] = $value[ $i ];
					}

					if ( isset( $std_events[ $id ]['code'] ) && ! empty( $std_events[ $id ]['code'] ) ) {
						$std_events[ $id ]['eventtype'] = 'CustomCode';
					}

					$i ++;
				}

			}

		}

		update_option( 'pixel_your_site', $options );
		update_option( 'pixel_your_site_std_events', $std_events );
		update_option( 'pixel_your_site_dyn_events', $dyn_events );

		// remove old settings
		//delete_option( 'woofp_admin_settings' );
		//delete_option( 'fpmp_facebookpixel_admin_settings' );

	}

}