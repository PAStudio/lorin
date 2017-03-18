<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !function_exists( 'pys_get_woo_ajax_addtocart_params' ) ) {

	function pys_get_woo_ajax_addtocart_params( $product_id ) {

		$params                 = array();
		$params['content_type'] = 'product';
		$params['content_ids']  = '[' . pys_get_product_content_id( $product_id ) . ']';

		// content_name, category_name
		if ( pys_get_option( 'woo', 'enable_additional_params' ) ) {
			pys_get_additional_woo_params( $product_id, $params );
		}

		// currency, value
		if ( pys_get_option( 'woo', 'enable_add_to_cart_value' ) ) {

			// get price for selected variation
			if( isset( $_REQUEST['variation_id'] ) ) {
				$id = $_REQUEST['variation_id'];
			} else {
				$id = $product_id;
			}

			$include_tax = pys_get_option( 'woo', 'tax' ) == 'incl' ? true : false;
			$option      = pys_get_option( 'woo', 'add_to_cart_value_option' );

			switch ( $option ) {
				case 'global':
					$value = pys_get_option( 'woo', 'add_to_cart_global_value' );
					break;

				case 'price':
					$value = pys_get_product_price( $id, $include_tax );
					break;

				case 'percent':
					$price    = pys_get_product_price( $id, $include_tax );
					$percents = pys_get_option( 'woo', 'add_to_cart_percent_value' );
					$percents = str_replace( '%', null, $percents );
					$percents = floatval( $percents ) / 100;
					$value    = $price * $percents;
					break;

				default:
					$value = null;
			}

			$params['value']    = $value;
			$params['currency'] = get_woocommerce_currency();

		}

		// tags
		if( pys_get_option( 'woo', 'enable_tags' ) && $tags = pys_get_product_tags( $product_id ) ) {
			$params['tags'] = implode( ', ', $tags );
		}

		return $params;

	}

}

if( !function_exists( 'pys_get_woo_code' ) ) {

	/**
	 * Process WooCommerce Events
	 */
	function pys_get_woo_code() {
		global $post, $woocommerce;

		$currency                  = get_woocommerce_currency();
		$include_tax               = pys_get_option( 'woo', 'tax' ) == 'incl' ? true : false;
		$additional_params_enabled = pys_get_option( 'woo', 'enable_additional_params' );
		$track_tags_enabled        = pys_get_option( 'woo', 'enable_tags' );

		// set defaults params
		$params                 = array();
		$params['content_type'] = 'product';

		// ViewContent Event
		if ( pys_get_option( 'woo', 'on_view_content' ) && is_product() ) {

			$delay                 = floatval( pys_get_option( 'woo', 'on_view_content_delay', 0 ) );
			$params['content_ids'] = '[' . pys_get_product_content_id( $post->ID ) . ']';

			// content_name, category_name
			if ( $additional_params_enabled ) {

				pys_get_additional_woo_params( $post, $params );

			}

			// currency, value
			if ( pys_get_option( 'woo', 'enable_view_content_value' ) ) {

				$option = pys_get_option( 'woo', 'view_content_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'woo', 'view_content_global_value' );
						break;

					case 'price':
						$value = pys_get_product_price( $post, $include_tax );
						break;

					case 'percent':
						$price    = pys_get_product_price( $post, $include_tax );
						$percents = pys_get_option( 'woo', 'view_content_percent_value' );
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
			if( $track_tags_enabled && $tags = pys_get_product_tags( $post->ID ) ) {
				$params['tags'] = implode( ', ', $tags );
			}

			pys_add_event( 'ViewContent', $params, $delay );

			return;

		}

		// AddToCart Cart Page Event
		if ( pys_get_option( 'woo', 'on_cart_page' ) && is_cart() ) {

			$ids        = array();  // cart items ids or sku
			$names      = '';       // cart items names
			$categories = '';       // cart items categories
			$tags       = array();  // cart items tags

			foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $item ) {

				$product_id = pys_get_product_id( $item );
				$value      = pys_get_product_content_id( $product_id );
				$ids[]      = $value;

				// content_name, category_name for each cart item
				if ( $additional_params_enabled ) {

					$temp = array();
					pys_get_additional_woo_params( $product_id, $temp );

					$names .= isset( $temp['content_name'] ) ? $temp['content_name'] . ' ' : null;
					$categories .= isset( $temp['category_name'] ) ? $temp['category_name'] . ' ' : null;

				}

				// tags
				if( $track_tags_enabled && $item_tags = pys_get_product_tags( $item['product_id'] ) ) {

					foreach( $item_tags as $tag ) {
						$tags[] = $tag;
					}

				}

			}

			$params['content_ids'] = '[' . implode( ',', $ids ) . ']';

			if ( ! empty( $names ) ) {
				$params['content_name'] = $names;
			}

			if ( ! empty( $categories ) ) {
				$params['category_name'] = $categories;
			}

			// currency, value
			if ( pys_get_option( 'woo', 'enable_add_to_cart_value' ) ) {

				$option = pys_get_option( 'woo', 'add_to_cart_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'woo', 'add_to_cart_global_value' );
						break;

					case 'price':
						$value = pys_get_cart_total( $include_tax );
						break;

					case 'percent':
						$price    = pys_get_cart_total( $include_tax );
						$percents = pys_get_option( 'woo', 'add_to_cart_percent_value' );
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
			if( $track_tags_enabled && $tags ) {

				$tags = array_unique( $tags );
				$tags = array_slice( $tags, 0, 100 );
				$params['tags'] = implode( ', ', $tags );

			}

			pys_add_event( 'AddToCart', $params );

			return;

		}

		// Checkout Page Event
		if ( pys_get_option( 'woo', 'on_checkout_page' ) && is_checkout() && ! is_wc_endpoint_url() ) {

			$params = pys_get_woo_checkout_params( $additional_params_enabled );

			// currency, value
			if ( pys_get_option( 'woo', 'enable_checkout_value' ) ) {

				$option = pys_get_option( 'woo', 'checkout_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'woo', 'checkout_global_value' );
						break;

					case 'price':
						$value = pys_get_cart_total( $include_tax );
						break;

					case 'percent':
						$price    = pys_get_cart_total( $include_tax );
						$percents = pys_get_option( 'woo', 'checkout_percent_value' );
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
			if( $track_tags_enabled ) {

				$tags = array();
				foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $item ) {

					if( $item_tags = pys_get_product_tags( $item['product_id'] ) ) {

						foreach( $item_tags as $tag ) {
							$tags[] = $tag;
						}

					}

				}

				if( $tags ) {

					$tags = array_unique( $tags );
					$tags = array_slice( $tags, 0, 100 );
					$params['tags'] = implode( ', ', $tags );

				}

			}

			pys_add_event( 'InitiateCheckout', $params );

			return;

		}

		// Purchase Event
		if ( pys_get_option( 'woo', 'on_thank_you_page' ) && is_wc_endpoint_url( 'order-received' ) ) {

			$order_id = wc_get_order_id_by_order_key( $_REQUEST['key'] );

			// skip if event was fired before
			if( pys_get_option( 'woo', 'purchase_fire_once', true ) && get_post_meta( $order_id, '_pys_purchase_event_fired', true ) ) {
				return;
			}

			update_post_meta( $order_id, '_pys_purchase_event_fired', true );

			$order    = new WC_Order( $order_id );
			$items    = $order->get_items( 'line_item' );

			$ids        = array();  // order items ids or sku
			$names      = '';       // order items names
			$categories = '';       // order items categories
			$num_items  = 0;        // order items count
			$tags       = array();  // order items tags

			foreach ( $items as $item ) {

				$product_id = pys_get_product_id( $item );
				$value      = pys_get_product_content_id( $product_id );
				$ids[]      = $value;
				$num_items += $item['qty'];

				// content_name, category_name for each cart item
				if ( $additional_params_enabled ) {

					$temp = array();
					pys_get_additional_woo_params( $product_id, $temp );

					$names .= isset( $temp['content_name'] ) ? $temp['content_name'] . ' ' : null;
					$categories .= isset( $temp['category_name'] ) ? $temp['category_name'] . ' ' : null;

				}

				// tags
				if( $track_tags_enabled && $item_tags = pys_get_product_tags( $item['product_id'] ) ) {

					foreach( $item_tags as $tag ) {
						$tags[] = $tag;
					}

				}

			}

			$params['content_ids'] = '[' . implode( ',', $ids ) . ']';

			if ( ! empty( $names ) ) {
				$params['content_name'] = $names;
			}

			if ( ! empty( $categories ) ) {
				$params['category_name'] = $categories;
			}

			if ( $additional_params_enabled ) {
				$params['num_items'] = $num_items;
			}

			// currency, value
			if ( pys_get_option( 'woo', 'enable_purchase_value' ) ) {

				$include_shipping = pys_get_option( 'woo', 'purchase_transport' ) == 'included' ? true : false;

				$option = pys_get_option( 'woo', 'purchase_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'woo', 'purchase_global_value' );
						break;

					case 'total':
						$value = pys_get_order_total( $order, $include_tax, $include_shipping );
						break;

					case 'percent':
						$price    = pys_get_order_total( $order, $include_tax, $include_shipping );
						$percents = pys_get_option( 'woo', 'purchase_percent_value' );
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

			// town, state, country
			if ( pys_get_option( 'woo', 'purchase_add_address' ) ) {

				if ( $order->billing_city ) {
					$params['town'] = $order->billing_city;
				}

				if ( $order->billing_state ) {
					$params['state'] = $order->billing_state;
				}

				if ( $order->billing_country ) {
					$params['country'] = $order->billing_country;
				}

			}

			// payment method
			if ( pys_get_option( 'woo', 'purchase_add_payment_method' ) ) {

				$params['payment'] = $order->payment_method_title;

			}

			// shipping method
			$shipping_methods = $order->get_items( 'shipping' );
			if ( pys_get_option( 'woo', 'purchase_add_shipping_method' ) && $shipping_methods ) {

				$labels = array();
				foreach ( $shipping_methods as $shipping ) {
					$labels[] = $shipping['name'] ? $shipping['name'] : null;
				}

				$params['shipping'] = implode( ', ', $labels );

			}

			// coupons
			$coupons = $order->get_items( 'coupon' );
			if ( pys_get_option( 'woo', 'purchase_add_coupons' ) && $coupons ) {

				$labels = array();
				foreach ( $coupons as $coupon ) {
					$labels[] = $coupon['name'] ? $coupon['name'] : null;
				}

				$params['coupon_used'] = 'yes';
				$params['coupon_name'] = implode( ', ', $labels );

			} elseif ( pys_get_option( 'woo', 'purchase_add_coupons' ) ) {

				$params['coupon_used'] = 'no';

			}

			// tags
			if( $track_tags_enabled && $tags ) {

				$tags = array_unique( $tags );
				$tags = array_slice( $tags, 0, 100 );
				$params['tags'] = implode( ', ', $tags );

			}

			pys_add_event( 'Purchase', $params );

			return;

		}

	}

}

if ( ! function_exists( 'pys_get_post_tags' ) ) {
	
	/**
	 * Return array of post tags.
	 */
	function pys_get_post_tags( $post_id ) {
		return pys_get_object_tags( $post_id, 'post_tag' );
	}
	
}

if ( ! function_exists( 'pys_get_product_tags' ) ) {
	
	/**
	 * Return array of product tags.
	 */
	function pys_get_product_tags( $product_id ) {
		return pys_get_object_tags( $product_id, 'product_tag' );
	}
	
}

if ( ! function_exists( 'pys_process_content' ) ) {

	/**
	 * Add 'pys-dynamic-event' class attribute to link tags on content and widgets.
	 * Function also adds custom data attribute 'data-pys-event-id' with event ID.
	 */
	function pys_process_content( $content ) {
		
		// Skip if no dynamic events
		$dynamic_events = get_option( 'pixel_your_site_dyn_events' );
		if ( empty( $dynamic_events ) ) {
			return $content;
		}

		// Don't do a thing if there's no anchor at all
		if ( false === stripos( $content, '<a ' ) ) {
			return $content;
		}

		// find all occurrences of anchors and fill matches with links
		preg_match_all( '#(<a\s[^>]+?>).*?</a>#iu', $content, $tags, PREG_SET_ORDER );

		foreach ( $tags as $tag ) {

			// get a href attribute value
			$href = preg_replace( '/^.*href="([^"]*)".*$/iu', '$1', $tag[0] );

			if ( ! isset( $href ) || empty( $href ) ) {
				continue;
			}

			// go over all dynamic events
			foreach ( $dynamic_events as $id => $event ) {

				// process only URL trigger type
				if ( ! isset( $event['url'] ) || empty( $event['url'] ) ) {
					continue;
				}

				// apply event-level URL filter
				if( isset( $event['url_filter'] ) && ! empty( $event['url_filter'] ) && ! pys_match_url( $event['url_filter'] ) ) {
					continue;
				}

				// filter content URL
				if ( ! pys_match_url( $href, $event['url'] ) ) {
					continue;
				}

				// add dynamic event ID to element attributes
				$new_tag = pys_insert_attribute( 'data-pys-event-id', $id, $tag[0], true );
				$new_tag = pys_insert_attribute( 'class', 'pys-dynamic-event', $new_tag );

				// add new tag to replacement list
				$old_content[] = $tag[0];
				$new_content[] = $new_tag;

			}

		}

		// replace content
		if ( isset( $old_content ) && isset( $new_content ) ) {
			$content = str_replace( $old_content, $new_content, $content );
		}

		return $content;
	}

}

if ( ! function_exists( 'pys_add_code_to_woo_cart_link' ) ) {

	/**
	 * Adds data-pixelcode attribute to "add to cart" buttons in the WooCommerce loop.
	 *
	 * @param string     $tag
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	function pys_add_code_to_woo_cart_link( $tag, $product ) {
		global $pys_woo_ajax_events;

		/**
		 * @since 5.0.8
		 */
		if ( pys_is_wc_version_gte( '2.7' ) ) {
			$is_simple_product   = $product->is_type( 'simple' );
			$is_external_product = $product->is_type( 'external' );
		} else {
			$is_simple_product   = $product->product_type == 'simple';
			$is_external_product = $product->product_type == 'external';
		}

		if ( false == $is_simple_product && false == $is_external_product ) {
			return $tag;
		}
		
		// btw, external products are always purchasable
		if ( $is_simple_product && false == $product->is_purchasable() ) {
			return $tag;
		}

		$event_id = uniqid();

		/**
		 * @since 5.0.8
		 */
		if ( pys_is_wc_version_gte( '2.6' ) ) {
			$product_id = $product->get_id();
		} else {
			$product_id = $product->post->ID;
		}

		// common params
		$params                 = array();
		$params['content_type'] = 'product';
		$params['content_ids']  = '[' . pys_get_product_content_id( $product_id ) . ']';

		$currency    = get_woocommerce_currency();
		$include_tax = pys_get_option( 'woo', 'tax' ) == 'incl' ? true : false;

		// content_name, category_name
		if ( pys_get_option( 'woo', 'enable_additional_params' ) ) {
			pys_get_additional_woo_params( $product->post, $params );
		}

		if ( $is_simple_product ) {

			// do not add code if AJAX is disabled. Event will be processed by 'pys_woocommerce_events' function
			if ( 'yes' !== get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
				return $tag;
			}

			$tag = pys_insert_attribute( 'data-pys-event-id', $event_id, $tag, true, 'any' );

			// add value if needed
			if ( pys_get_option( 'woo', 'enable_view_content_value' ) ) {

				$params['currency'] = $currency;

				$option = pys_get_option( 'woo', 'view_content_value_option' );
				switch ( $option ) {
					case 'global':
						$value = pys_get_option( 'woo', 'view_content_global_value' );
						break;

					case 'price':
						$value = pys_get_product_price( $product_id, $include_tax );
						break;

					case 'percent':
						$price    = pys_get_product_price( $product_id, $include_tax );
						$percents = pys_get_option( 'woo', 'view_content_percent_value' );
						$percents = str_replace( '%', null, $percents );
						$percents = floatval( $percents ) / 100;
						$value    = $price * $percents;
						break;

					default:
						$value = null;

				}

				$params['value'] = $value;

			}

			// tags
			if ( pys_get_option( 'woo', 'enable_tags' ) && $tags = pys_get_product_tags( $product->id ) ) {
				$params['tags'] = implode( ', ', $tags );
			}

			$pys_woo_ajax_events[ $event_id ] = array(
				'name'   => 'AddToCart',
				'params' => $params
			);

		}

		if ( $is_external_product ) {

			$tag = pys_insert_attribute( 'data-pys-event-id', $event_id, $tag, true, 'any' );

			// add value if needed
			$option = pys_get_option( 'woo', 'aff_value_option' );
			switch ( $option ) {
				case 'global':
					$params['currency'] = $currency;
					$params['value']    = pys_get_option( 'woo', 'aff_global_value' );
					break;

				case 'price':
					$params['currency'] = $currency;
					$params['value']    = pys_get_product_price( $product_id, $include_tax );
					break;

				default:
					break;
			}

			if ( pys_get_option( 'woo', 'aff_event' ) == 'predefined' ) {
				$event_name = pys_get_option( 'woo', 'aff_predefined_value' );
			} else {
				$event_name = pys_get_option( 'woo', 'aff_custom_value' );
			}

			// tags
			if( pys_get_option( 'woo', 'enable_tags' ) && $tags = pys_get_product_tags( $product->id ) ) {
				$params['tags'] = implode( ', ', $tags );
			}

			$pys_woo_ajax_events[ $event_id ] = array(
				'name'   => $event_name,
				'params' => $params
			);

		}

		return $tag;

	}

}

if ( ! function_exists( 'pys_add_code_to_order_button' ) ) {

	/**
	 * Adds data-pixelcode attribute to "place order" button.
	 */
	function pys_add_code_to_order_button( $tag ) {
		global $woocommerce, $pys_woo_ajax_events;

		if ( ! pys_get_option( 'woo', 'enable_paypal_event' ) ) {
			return $tag;
		}

		$currency                  = get_woocommerce_currency();
		$include_tax               = pys_get_option( 'woo', 'tax' ) == 'incl' ? true : false;
		$additional_params_enabled = pys_get_option( 'woo', 'enable_additional_params' );

		$params = pys_get_woo_checkout_params( $additional_params_enabled );

		// currency, value
		$option = pys_get_option( 'woo', 'pp_value_option' );
		switch ( $option ) {
			case 'global':
				$value = pys_get_option( 'woo', 'pp_global_value' );
				break;

			case 'total':
				$value = pys_get_cart_total( $include_tax );
				break;

			default:
				$value = null;
		}

		$params['value']    = $value;
		$params['currency'] = $currency;

		if ( pys_get_option( 'woo', 'pp_event' ) == 'predefined' ) {
			$event_name = pys_get_option( 'woo', 'pp_predefined_value' );
		} else {
			$event_name = pys_get_option( 'woo', 'pp_custom_value' );
		}

		// tags
		if ( pys_get_option( 'woo', 'enable_tags' ) ) {

			$tags = array();
			foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $item ) {

				if ( $item_tags = pys_get_product_tags( $item['product_id'] ) ) {
					foreach ( $item_tags as $item_tag ) {
						$tags[] = $item_tag;
					}
				}

			}

			if ( $tags ) {

				$tags           = array_unique( $tags );
				$tags           = array_slice( $tags, 0, 100 );
				$params['tags'] = implode( ', ', $tags );

			}

		}

		$event = array(
			'type'   => pys_is_standard_event( $event_name ) ? 'track' : 'trackCustom',
			'name'   => $event_name,
			'params' => $params
		);

		return pys_insert_attribute( 'data-pys-code', json_encode( $event ), $tag, true, 'input' );

	}

}

if ( ! function_exists( 'pys_get_order_total' ) ) {

	/**
	 * Calculates order 'value' param depends on WooCommerce and PYS settings
	 */
	function pys_get_order_total( $order, $include_tax, $include_shipping ) {

		$cart_subtotal = $order->get_subtotal();    // only products price without taxes

		if ( $include_shipping && $include_tax ) {

			$total = $order->get_total();   // full order price

		} elseif ( ! $include_shipping && ! $include_tax ) {

			$total = $cart_subtotal;

		} elseif ( ! $include_shipping && $include_tax ) {

			$tax_total = 0;
			foreach( $order->get_taxes() as $id => $tax ) {
				$tax_total += isset( $tax['tax_amount'] ) ? $tax['tax_amount'] : 0;
			}

			$total = $cart_subtotal + $tax_total;

		} else { // $include_shipping && !$include_tax

			$total = $cart_subtotal + $order->order_shipping - $order->order_shipping_tax;

		}

		//wc_get_price_thousand_separator is ignored
		return number_format( $total, wc_get_price_decimals(), '.', '' );

	}

}

if ( ! function_exists( 'pys_get_additional_woo_params' ) ) {

	/**
	 * Adds `content_name` and `category_name` product args.
	 *
	 * @param $post   WP_Post|int
	 * @param $params array reference to $params array
	 */
	function pys_get_additional_woo_params( $post, &$params ) {
		
		pys_get_additional_post_params( $post, $params, 'product_cat' );

	}

}

if ( ! function_exists( 'pys_get_product_price' ) ) {

	/**
	 * Return product price depends on plugin, product and WooCommerce settings.
	 *
	 * @param $product_id
	 * @param $include_tax boolean Include tax or not
	 *
	 * @return null|int Product price
	 */
	function pys_get_product_price( $product_id, $include_tax ) {

		$product = wc_get_product( $product_id );

		if ( $product->is_taxable() && $include_tax ) {

			/**
			 * @since 5.0.8
			 */
			if( pys_is_wc_version_gte( '2.7' ) ) {
				$value = wc_get_price_including_tax( $product, $product->get_price() );
			} else {
				$value = $product->get_price_including_tax( 1, $product->get_price() );
			}

		} else {

			/**
			 * @since 5.0.8
			 */
			if ( pys_is_wc_version_gte( '2.7' ) ) {
				$value = wc_get_price_excluding_tax( $product, $product->get_price() );
			} else {
				$value = $product->get_price_excluding_tax( 1, $product->get_price() );
			}

		}

		return $value;

	}

}

if ( ! function_exists( 'pys_get_cart_total' ) ) {

	function pys_get_cart_total( $include_tax ) {
		global $woocommerce;

		if ( $include_tax ) {

			$total = $woocommerce->cart->cart_contents_total + $woocommerce->cart->tax_total;

		} else {

			$total = $woocommerce->cart->cart_contents_total;

		}

		return $total;

	}

}

if ( ! function_exists( 'pys_sanitize_license' ) ) {


	function pys_sanitize_license( $new ) {

		$old = get_option( 'pys_license_key' );

		if ( $old && $old != $new ) {

			// new license has been entered, so must reactivate
			delete_option( 'pys_license_status' );

		}

		return $new;
	}

}

if ( ! function_exists( 'pys_add_purchase_additional_matching_params' ) ) {

	function pys_add_purchase_additional_matching_params( $params ) {

		// add extra params only on thank you page and if option enabled
		if( ! pys_is_woocommerce_active() || ! is_order_received_page() ) {
			return $params;
		}

		$order_id = wc_get_order_id_by_order_key( $_REQUEST['key'] );
		$order    = wc_get_order( $order_id );

		if( ! $order ) {
			return $params;
		}

		if( $order->billing_email ) {
			$params['email'] = $order->billing_email;
		}

		if( $order->billing_phone ) {
			$params['phone'] = $order->billing_phone;
		}

		if( $order->billing_first_name ) {
			$params['fn'] = $order->billing_first_name;
		}

		if( $order->billing_last_name ) {
			$params['ln'] = $order->billing_last_name;
		}

		if( $order->billing_city ) {
			$params['ct'] = $order->billing_city;
		}

		if( $order->billing_state ) {
			$params['st'] = $order->billing_state;
		}

		if( $order->billing_postcode ) {
			$params['zip'] = $order->billing_postcode;
		}

		if( $order->billing_country ) {
			$params['country'] = $order->billing_country;
		}

		return $params;

	}

}

if ( ! function_exists( 'pys_add_general_additional_matching_params' ) ) {

	function pys_add_general_additional_matching_params( $params ) {

		$user = wp_get_current_user();

		// something wrong
		if ( ! ( $user instanceof WP_User ) ) {
			return $params;
		}

		// it is a guest
		if ( is_user_logged_in() == false ) {
			return $params;
		}

		// get user regular data
		$params['fn']    = $user->user_firstname;
		$params['ln']    = $user->user_lastname;
		$params['email'] = $user->user_email;

		if ( ! pys_is_woocommerce_active() ) {
			return $params;
		}

		// if first name is not set in regular wp user meta
		if ( empty( $params['fn'] ) ) {
			$params['fn'] = $user->get( 'billing_first_name' );
		}

		// if last name is not set in regular wp user meta
		if ( empty( $params['ln'] ) ) {
			$params['ln'] = $user->get( 'billing_last_name' );
		}

		$params['phone']   = $user->get( 'billing_phone' );
		$params['ct']      = $user->get( 'billing_city' );
		$params['st']      = $user->get( 'billing_state' );
		$params['zip']     = $user->get( 'billing_postcode' );
		$params['country'] = $user->get( 'billing_country' );

		return $params;

	}

}

if( ! function_exists( 'pys_time_on_page_event' ) ) {

	/**
	 * Function only output event params to CDATA. Request body will be added on front-end.
	 * 
	 * @deprecated 5.0.0
	 */
	function pys_time_on_page_event() {
		global $post;

		if ( pys_get_option( 'general', 'timeonpage_enabled' ) == false ) {
			return;
		}

		$params     = array();

		// Posts
		if ( is_singular() ) {

			$params['content_name'] = $post->post_title;
			$params['content_ids']  = $post->ID;

		}

		// Pages or Front Page
		if ( is_singular( 'page' ) || is_home() ) {

			$params['content_name'] = is_home() == true ? get_bloginfo( 'name' ) : $post->post_title;
			$params['content_ids'] = is_home() != true ? $post->ID : null;

		}

		// Taxonomies (built-in and custom)
		if ( is_category() || is_tax() || is_tag() ) {

			$term = null;
			$type = null;

			if ( is_category() ) {

				$cat  = get_query_var( 'cat' );
				$term = get_category( $cat );

				$params['content_name'] = $term->name;
				$params['content_ids']  = $cat;

			} elseif ( is_tag() ) {

				$slug = get_query_var( 'tag' );
				$term = get_term_by( 'slug', $slug, 'post_tag' );

				$params['content_name'] = $term->name;
				$params['content_ids']  = $term->term_id;

			} else {

				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

				$params['content_name'] = $term->name;
				$params['content_ids']  = $term->term_id;

			}

		}

		wp_localize_script( 'pys-public', 'pys_timeOnPage', $params );

	}

}

if ( ! function_exists( 'pys_output_dynamic_events_code' ) ) {

	function pys_output_dynamic_events_code() {

		$dynamic_events = get_option( 'pixel_your_site_dyn_events', array() );
		if ( empty( $dynamic_events ) ) {
			return;
		}

		$events               = array();
		$css_selectors        = array();
		$scroll_positions     = array();
		$mouse_over_selectors = array();

		foreach ( $dynamic_events as $id => $params ) {

			if ( ! isset( $params['trigger_type'] ) ) {
				continue;
			}

			// apply event-level URL filter
			if( isset( $params['url_filter'] ) && ! empty( $params['url_filter'] ) && ! pys_match_url( $params['url_filter'] ) ) {
				continue;
			}

			switch( $params['trigger_type'] ) {
				case 'CSS':
					$css_selectors[ $id ] = stripslashes( $params['css'] );
					break;

				case 'scroll':
					$scroll_positions[ $id ] = $params['scroll_pos'];
					break;

				case 'mouse-over':
					$mouse_over_selectors[ $id ] = stripslashes( $params['css'] );
					break;
			}

			$type = $params['eventtype'];

			if ( $type == 'CustomCode' ) {

				$events[ $id ] = array(
					'custom' => isset( $params['code'] ) ? stripcslashes( $params['code'] ) : null
				);

			} else {

				$params = apply_filters( 'pys_event_params', $params, $type );
				$params = pys_clean_system_event_params( $params );

				// sanitize params
				$sanitized = array();
				foreach ( $params as $name => $value ) {

					if ( empty( $value ) && $value !== "0" ) {
						continue;
					}

					$key               = esc_js( $name );
					$sanitized[ $key ] = $value;

				}

				$events[ $id ] = array(
					'type'   => pys_is_standard_event( $type ) ? 'track' : 'trackCustom',
					'name'   => $type,
					'params' => $sanitized
				);

			}

		}

		wp_localize_script( 'pys-public', 'pys_dynamic_events', $events );
		wp_localize_script( 'pys-public', 'pys_css_selectors', $css_selectors );
		wp_localize_script( 'pys-public', 'pys_scroll_positions', $scroll_positions );
		wp_localize_script( 'pys-public', 'pys_mouse_over_selectors', $mouse_over_selectors );

	}

}

if ( ! function_exists( 'pys_general_woo_event' ) ) {
	
	/**
	 * Add General event on Woo Product page. PRO only.
	 *
	 * @param $post       WP_Post|int
	 * @param $track_tags bool
	 * @param $delay      int
	 * @param $event_name string
	 */
	function pys_general_woo_event( $post, $track_tags, $delay, $event_name ) {
		
		$product = wc_get_product( $post->ID );
		
		$params['post_type']    = 'product';
		$params['content_name'] = $post->post_title;
		$params['post_id']      = $post->ID;
		$params['value']        = $product->get_price();
		$params['currency']     = get_woocommerce_currency();
		
		if ( $terms = pys_get_content_taxonomies( 'product_cat' ) ) {
			$params['content_category'] = $terms;
		}
		
		if ( $track_tags && $tags = pys_get_product_tags( $post->ID ) ) {
			$params['tags'] = implode( ', ', $tags );
		}
		
		pys_add_event( $event_name, $params, $delay );
		
	}
	
}

if ( ! function_exists( 'pys_general_edd_event' ) ) {
	
	/**
	 * Add General event on EDD Download page. PRO only.
	 *
	 * @param $post       WP_Post|int
	 * @param $track_tags bool
	 * @param $delay      int
	 * @param $event_name string
	 */
	function pys_general_edd_event( $post, $track_tags, $delay, $event_name ) {
		
		$download = new EDD_Download( $post->ID );
		
		$params['post_type']    = 'download';
		$params['content_name'] = $download->post_title;
		$params['post_id']      = $post->ID;
		$params['value']        = pys_get_edd_price( $post->ID );
		$params['currency']     = edd_get_currency();
		
		if ( $terms = pys_get_content_taxonomies( 'download_category' ) ) {
			$params['content_category'] = $terms;
		}
		
		if ( $track_tags && $tags = pys_get_edd_tags( $post->ID ) ) {
			$params['tags'] = implode( ', ', $tags );
		}
		
		pys_add_event( $event_name, $params, $delay );
		
	}
	
}