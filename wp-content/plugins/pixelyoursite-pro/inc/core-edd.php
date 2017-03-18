<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'pys_edd_events' ) ) {

	function pys_edd_events() {

		if ( pys_get_option( 'edd', 'enabled' ) == false || pys_is_edd_active() == false ) {
			return;
		}

		global $post;

		$currency                  = edd_get_currency();
		$additional_params_enabled = pys_get_option( 'edd', 'enable_additional_params' );
		$track_tags_enabled        = pys_get_option( 'edd', 'enable_tags' );

		// set defaults params
		$params                 = array();
		$params['content_type'] = 'product';

		// ViewContent Event
		if ( pys_get_option( 'edd', 'on_view_content' ) && is_singular( array( 'download' ) ) ) {

			$delay                 = floatval( pys_get_option( 'edd', 'on_view_content_delay', 0 ) );
			$params['content_ids'] = '[' . pys_get_edd_content_id( $post->ID ) . ']';

			// content_name, category_name
			if ( $additional_params_enabled ) {
				pys_get_additional_edd_params( $post, $params );
			}

			// currency, value
			if ( pys_get_option( 'edd', 'enable_view_content_value' ) ) {

				$option = pys_get_option( 'edd', 'view_content_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'edd', 'view_content_global_value' );
						break;

					case 'price':
						$value = pys_get_edd_price( $post->ID );
						break;

					case 'percent':
						$price    = pys_get_edd_price( $post->ID );
						$percents = pys_get_option( 'edd', 'view_content_percent_value' );
						$percents = str_replace( '%', null, $percents );
						$percents = floatval( $percents ) / 100;
						$value    = $price * $percents;
						break;

					default:
						$value = null;
				}

				$params['value']    = $value;
				$params['currency'] = $currency;

			}

			// tags
			if ( $track_tags_enabled && $tags = pys_get_edd_tags( $post->ID ) ) {
				$params['tags'] = implode( ', ', $tags );
			}
			
			$license = pys_edd_get_license_data( $post->ID );
			$params  = array_merge( $params, $license );

			pys_add_event( 'ViewContent', $params, $delay );

			return;

		}

		/**
		 * AddToCart Event (button)
		 *
		 * @see pys_edd_purchase_link_args()
		 */

		// InitiateCheckout Event
		if ( pys_get_option( 'edd', 'on_checkout_page' ) && edd_is_checkout() ) {

			$ids        = array();
			$names      = array();
			$categories = array();
			$num_items  = 0;
			$total      = 0;

			$licenses = array(
				'transaction_type'   => null,
				'license_site_limit' => null,
				'license_time_limit' => null,
				'license_version'    => null
			);

			foreach ( edd_get_cart_contents() as $cart_item ) {

				$download_id = intval( $cart_item['id'] );
				$ids[] = pys_get_edd_content_id( $download_id );

				// content_name, category_name for each cart item
				if ( $additional_params_enabled ) {

					$temp = array();
					pys_get_additional_edd_params( $download_id, $temp );

					$names[] = $temp['content_name'];

					if( isset( $temp['category_name'] ) ) {
						$categories[] = $temp['category_name'];
					}

				}

				$num_items += $cart_item['quantity'];

				## calculate cart items total
				if ( pys_get_option( 'edd', 'enable_checkout_value' ) ) {
					$total += pys_get_edd_price( $download_id, $cart_item['options'] ) * $cart_item['quantity'];
				}

				## get download license data
				array_walk( $licenses, function( &$value, $key, $license ) {

					if( ! isset( $license[ $key ] ) ) {
						return;
					}

					if( $value ) {
						$value = $value . ', ' . $license[ $key ];
					} else {
						$value = $license[ $key ];
					}

				}, pys_edd_get_license_data( $download_id ) );

			}

			if ( $additional_params_enabled ) {
				$params['num_items'] = $num_items;
			}

			$params['content_ids'] = '[' . implode( ',', $ids ) . ']';

			if ( ! empty( $names ) ) {
				$params['content_name'] = implode( ', ', $names );
			}

			if ( ! empty( $categories ) ) {
				$params['category_name'] = implode( ', ', array_unique( $categories ) );
			}

			// currency, value
			if ( pys_get_option( 'edd', 'enable_checkout_value' ) ) {

				$option = pys_get_option( 'edd', 'checkout_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'edd', 'checkout_global_value' );
						break;

					case 'price':
						$value = $total;
						break;

					case 'percent':
						$percents = pys_get_option( 'edd', 'checkout_percent_value' );
						$percents = str_replace( '%', null, $percents );
						$percents = floatval( $percents ) / 100;
						$value    = $total * $percents;
						break;

					default:
						$value = null;
				}

				$params['value']    = $value;
				$params['currency'] = $currency;

			}

			// tags
			if ( $track_tags_enabled ) {

				$tags = array();
				foreach ( edd_get_cart_contents() as $cart_item ) {

					if ( $item_tags = pys_get_edd_tags( intval( $cart_item['id'] ) ) ) {
						$tags = array_merge( $tags, $item_tags );
					}

				}

				if ( $tags ) {

					$tags           = array_unique( $tags );
					$tags           = array_slice( $tags, 0, 100 );
					$params['tags'] = implode( ', ', $tags );

				}

			}

			$params = array_merge( $params, $licenses );
			pys_add_event( 'InitiateCheckout', $params );

			return;

		}

		// Purchase Event
		if ( pys_get_option( 'edd', 'on_success_page' ) && edd_is_success_page() ) {
			
			## skip payment confirmation page
			if( isset( $_GET['payment-confirmation'] ) ) {
				return;
			}

			global $edd_receipt_args;

			$session = edd_get_purchase_session();
			if ( isset( $_GET['payment_key'] ) ) {
				$payment_key = urldecode( $_GET['payment_key'] );
			} else if ( $session ) {
				$payment_key = $session['purchase_key'];
			} elseif ( $edd_receipt_args['payment_key'] ) {
				$payment_key = $edd_receipt_args['payment_key'];
			}

			if ( ! isset( $payment_key ) ) {
				return;
			}

			$payment_id = edd_get_purchase_id_by_key( $payment_key );
			$user_can_view = edd_can_view_receipt( $payment_key );

			if ( ! $user_can_view && ! empty( $payment_key ) && ! is_user_logged_in() && ! edd_is_guest_payment( $payment_id ) ) {
				return;
			}

			// skip if event was fired before
			if ( pys_get_option( 'edd', 'purchase_fire_once', true )
				&& get_post_meta( $payment_id, '_pys_purchase_event_fired', true ) ) {
				return;
			}

			update_post_meta( $payment_id, '_pys_purchase_event_fired', true );

			$meta   = edd_get_payment_meta( $payment_id );
			$cart   = edd_get_payment_meta_cart_details( $payment_id, true );
			$user   = edd_get_payment_meta_user_info( $payment_id );
			$status = edd_get_payment_status( $payment_id, true );

			## pending payment status used because we can't fire event on IPN
			if( strtolower( $status ) != 'complete' && strtolower( $status ) != 'pending' ) {
				return;
			}

			$ids        = array();
			$names      = array();
			$categories = array();
			$num_items  = 0;
			$total      = 0;

			$licenses = array(
				'transaction_type'   => null,
				'license_site_limit' => null,
				'license_time_limit' => null,
				'license_version'    => null
			);

			$include_tax = pys_get_option( 'edd', 'tax' ) == 'incl' ? true : false;

			foreach ( $cart as $cart_item ) {

				$download_id = intval( $cart_item['id'] );
				$ids[]       = pys_get_edd_content_id( $download_id );

				// content_name, category_name for each cart item
				if ( $additional_params_enabled ) {

					$temp = array();
					pys_get_additional_edd_params( $download_id, $temp );

					$names[]      = $temp['content_name'];

					if ( isset( $temp['category_name'] ) ) {
						$categories[] = $temp['category_name'];
					}

				}

				$num_items += $cart_item['quantity'];

				## item price
				if( $include_tax ) {
					$total += $cart_item['subtotal'] + $cart_item['tax'] - $cart_item['discount'];
				} else {
					$total += $cart_item['subtotal'] - $cart_item['discount'];
				}

				## get download license data
				array_walk( $licenses, function( &$value, $key, $license ) {

					if ( ! isset( $license[ $key ] ) ) {
						return;
					}

					if ( $value ) {
						$value = $value . ', ' . $license[ $key ];
					} else {
						$value = $license[ $key ];
					}

				}, pys_edd_get_license_data( $download_id ) );

			}

			if ( $additional_params_enabled ) {
				$params['num_items'] = $num_items;
			}

			$params['content_ids'] = '[' . implode( ',', $ids ) . ']';

			if ( ! empty( $names ) ) {
				$params['content_name'] = implode( ', ', $names );
			}

			if ( ! empty( $categories ) ) {
				$params['category_name'] = implode( ', ', array_unique( $categories ) );
			}

			// currency, value
			if ( pys_get_option( 'edd', 'enable_purchase_value' ) ) {

				$option = pys_get_option( 'edd', 'purchase_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'edd', 'purchase_global_value' );
						break;

					case 'total':
						$value = $total;
						break;

					case 'percent':
						$percents = pys_get_option( 'edd', 'purchase_percent_value' );
						$percents = str_replace( '%', null, $percents );
						$percents = floatval( $percents ) / 100;
						$value    = $total * $percents;
						break;

					default:
						$value = null;
				}

				$params['value']    = $value;
				$params['currency'] = $meta['currency'];

			}

			// tags
			if ( $track_tags_enabled ) {

				$tags = array();
				foreach ( $cart as $cart_item ) {

					if ( $item_tags = pys_get_edd_tags( intval( $cart_item['id'] ) ) ) {
						$tags = array_merge( $tags, $item_tags );
					}

				}

				if ( $tags ) {

					$tags           = array_unique( $tags );
					$tags           = array_slice( $tags, 0, 100 );
					$params['tags'] = implode( ', ', $tags );

				}

			}

			// town, state, country
			if ( pys_get_option( 'edd', 'purchase_add_address' ) && isset( $user['address'] ) ) {

				if ( ! empty( $user['address']['city'] ) ) {
					$params['town'] = $user['address']['city'];
				}

				if ( ! empty( $user['address']['state'] ) ) {
					$params['state'] = $user['address']['state'];
				}

				if ( ! empty( $user['address']['country'] ) ) {
					$params['country'] = $user['address']['country'];
				}

			}

			// payment method
			if ( pys_get_option( 'edd', 'purchase_add_payment_method' && isset( $session['gateway'] ) ) ) {
				$params['payment'] = $session['gateway'];
			}

			// coupons
			$coupons = isset( $user['discount'] ) && $user['discount'] != 'none' ? $user['discount'] : null;
			if ( pys_get_option( 'edd', 'purchase_add_coupons' ) && ! empty( $coupons ) ) {
				
				$params['coupon_used'] = 'yes';
				$params['coupon_name'] = $coupons;

			} elseif ( pys_get_option( 'edd', 'purchase_add_coupons' ) ) {

				$params['coupon_used'] = 'no';

			}

			## add transaction date
			$params['transaction_year']  = strftime( '%Y', strtotime( $meta['date'] ) );
			$params['transaction_month'] = strftime( '%m', strtotime( $meta['date'] ) );
			$params['transaction_day']   = strftime( '%d', strtotime( $meta['date'] ) );

			$params = array_merge( $params, $licenses );
			pys_add_event( 'Purchase', $params );

			return;

		}

	}

}

if ( ! function_exists( 'pys_edd_purchase_link_args' ) ) {
	
	function pys_edd_purchase_link_args( $args = array() ) {
		global $pys_edd_ajax_events;
		
		$download_id = $args['download_id'];
		$event_id    = uniqid();
		
		$currency                  = edd_get_currency();
		$additional_params_enabled = pys_get_option( 'edd', 'enable_additional_params' );
		$track_tags_enabled        = pys_get_option( 'edd', 'enable_tags' );
		
		$params                 = array();
		$params['content_type'] = 'product';
		$params['content_ids']  = '[' . pys_get_edd_content_id( $download_id ) . ']';
		
		// content_name, category_name
		if ( $additional_params_enabled ) {
			pys_get_additional_edd_params( $download_id, $params );
		}
		
		// currency, value
		if ( pys_get_option( 'edd', 'enable_add_to_cart_value' ) ) {
			
			$option = pys_get_option( 'edd', 'add_to_cart_value_option' );
			switch ( $option ) {
				case 'global':
					$value = pys_get_option( 'edd', 'add_to_cart_global_value' );
					break;
				
				case 'price':
					$value = pys_get_edd_price( $download_id );
					break;
				
				case 'percent':
					$price    = pys_get_edd_price( $download_id );
					$percents = pys_get_option( 'edd', 'add_to_cart_percent_value' );
					$percents = str_replace( '%', null, $percents );
					$percents = floatval( $percents ) / 100;
					$value    = $price * $percents;
					break;
				
				default:
					$value = null;
			}
			
			$params['value']    = $value;
			$params['currency'] = $currency;
			
		}
		
		// tags
		if ( $track_tags_enabled && $tags = pys_get_edd_tags( $download_id ) ) {
			$params['tags'] = implode( ', ', $tags );
		}
		
		$license = pys_edd_get_license_data( $download_id );
		$params  = array_merge( $params, $license );
		
		$pys_edd_ajax_events[ $event_id ] = array(
			'name'   => 'AddToCart',
			'params' => $params
		);
		
		$classes       = isset( $args['class'] ) ? $args['class'] : null;
		$args['class'] = $classes . " pys-event-id-{$event_id}";
		
		return $args;
		
	}
	
}

if ( ! function_exists( 'pys_get_additional_edd_params' ) ) {

	/**
	 * Adds additional download parameters like `content_name` and `category_name`.
	 *
	 * @param $post   WP_Post|int
	 * @param $params array reference to $params array
	 */
	function pys_get_additional_edd_params( $post, &$params ) {

		pys_get_additional_post_params( $post, $params, 'download_category' );

	}

}

if ( ! function_exists( 'pys_get_edd_tags' ) ) {

	/**
	 * @param int|WP_Post $post     Download ID or object
	 *
	 * @return array Array of download tags titles on success or empty array
	 */
	function pys_get_edd_tags( $post ) {

		return pys_get_object_tags( $post, 'download_tag' );

	}

}

if ( ! function_exists( 'pys_edd_get_license_data' ) ) {

	function pys_edd_get_license_data( $download_id ) {

		## license management disabled for product
		if( false == get_post_meta( $download_id, '_edd_sl_enabled', true ) ) {
			return array();
		}

		$params     = array();

		$limit      = get_post_meta( $download_id, '_edd_sl_limit', true );
		$exp_unit   = get_post_meta( $download_id, '_edd_sl_exp_unit', true );
		$exp_length = get_post_meta( $download_id, '_edd_sl_exp_length', true );
		$version    = get_post_meta( $download_id, '_edd_sl_version', true );

		$is_limited = get_post_meta( $download_id, 'edd_sl_download_lifetime', true );
		$is_limited = empty( $is_limited );

		$params['transaction_type']   = pys_get_edd_price( $download_id ) == 0 ? 'free' : 'paid';
		$params['license_site_limit'] = $limit;
		$params['license_time_limit'] = $is_limited ? "{$exp_length} {$exp_unit}" : 'lifetime';
		$params['license_version']    = $version;

		return $params;

	}

}

if ( ! function_exists( 'pys_get_edd_price' ) ) {
	
	/**
	 * Return Download price depends on plugin, post and EDD settings.
	 *
	 * @param $post_id int Download post ID
	 * @param $options array Optional. Cart item options
	 *
	 * @return float Download price
	 */
	function pys_get_edd_price( $post_id, $options = array() ) {
		
		$price       = edd_get_download_price( $post_id );
		$include_tax = pys_get_option( 'edd', 'tax' ) == 'incl' ? true : false;
		
		if ( edd_has_variable_prices( $post_id ) ) {
			
			$prices = edd_get_variable_prices( $post_id );
			
			if ( ! empty( $options ) ) {
				
				## get selected price option
				$price = isset( $prices[ $options['price_id'] ] ) ? $prices[ $options['price_id'] ]['amount'] : 0;
				
			} else {
				
				## get default price option				
				$default_option = edd_get_default_variable_price( $post_id );
				$price          = $prices[ $default_option ]['amount'];
				
			}
			
		}
		
		$price = floatval( $price );
		$tax   = edd_get_cart_item_tax( $post_id, array(), $price );
		
		if ( $include_tax == false && edd_prices_include_tax() ) {
			
			$price -= $tax;
			
		} elseif ( $include_tax == true && edd_prices_include_tax() == false ) {
			
			$price += $tax;
			
		}
		
		return $price;
		
	}
	
}