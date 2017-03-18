<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

?>

<div class="pys-box">
  <div class="pys-col pys-col-full">
    <h2 class="section-title"><?php _e( 'WooCommerce Pixel Settings', 'pys' ); ?></h2>
    <p><?php _e( 'Add all necessary events on WooCommerce with just a few clicks. On this tab you will find powerful options to customize the Facebook Pixel for your store.', 'pys' ); ?></p>

    <hr>
    <h2 class="section-title"><?php _e( 'Facebook Dynamic Product Ads Pixel Settings', 'pys' ); ?></h2>
    <table class="layout">
      <tr class="tall">
        <td colspan="2" class="narrow">
          <input type="checkbox" class="woo-events-toggle"><strong><?php _e( 'Enable Facebook Dynamic Product Ads', 'pys' ); ?></strong>
          <span class="help"><?php _e( 'This will automatically add ViewContent on product pages, AddToCart on add to cart button click and cart page, InitiateCheckout on checkout page and Purchase on thank you page. The events will have the required <code>content_ids</code> and <code>content_type</code> fields.', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label">content_ids:</p></td>
        <td>
          <select name="pys[woo][content_id]">
            <option <?php selected( 'id', pys_get_option( 'woo', 'content_id' ) ); ?> value="id"><?php _e( 'Product ID', 'pys' ); ?></option>
            <option <?php selected( 'sku', pys_get_option( 'woo', 'content_id' ) ); ?> value="sku"><?php _e( 'Product SKU', 'pys' ); ?></option>
          </select>        
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Define Variation ID:', 'pys' ); ?></p></td>
        <td>
          <select name="pys[woo][variation_id]">
            <option <?php selected( 'main', pys_get_option( 'woo', 'variation_id' ) ); ?> value="main"><?php _e( 'Main product data', 'pys' ); ?></option>
            <option <?php selected( 'variation', pys_get_option( 'woo', 'variation_id' ) ); ?> value="variation"><?php _e( 'Variation data', 'pys' ); ?></option>
          </select>
          <span class="help"><?php _e( 'Define what ID should be use for variations of variable product.', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td></td>
        <td>
          <p><?php _e( '<strong>Product Catalog Feed</strong> - use our dedicated plugin to create 100% Dynamic Ads compatible feeds with just a few clicks:', 'pys' ); ?></p>
          <p><a href="http://www.pixelyoursite.com/product-catalog-facebook" target="_blank"><?php _e( 'Download Product Catalog Plugin for a big discount', 'pys' ); ?></a></p>
        </td>
      </tr>
    </table>
    
    <hr>
    <table class="layout">
      <tr style="vertical-align: top;">
        <td>
          
          <h2><?php _e( 'Custom Audiences Optimization', 'pys' ); ?></h2>

          <input type="checkbox" name="pys[woo][enable_additional_params]" value="1"
              <?php pys_checkbox_state( 'woo', 'enable_additional_params' ); ?> ><?php _e( 'Enable Additional Parameters', 'pys' ); ?>

          <span class="help"><?php _e( 'Product name will be pulled as <code>content_name</code>, and Product Category as <code>category_name</code> for all WooCommerce events.', 'pys' ); ?></span>
          <span class="help" style="margin-bottom: 20px;"><?php _e( 'The number of items is <code>num_items</code> for InitiateCheckout and Purchase events.', 'pys' ); ?></span>

	        <input type="checkbox" name="pys[woo][enable_tags]" value="1"
		        <?php pys_checkbox_state( 'woo', 'enable_tags', 'checked' ); ?> ><?php _e( 'Track tags', 'pys' ); ?>
	        <span class="help"><?php _e( 'Will pull <code>tags</code> param on all WooCommerce events.', 'pys' ); ?></span>

        </td>
        <td>
          
          <h2><?php _e( 'Tax Options', 'pys' ); ?></h2>
	        <?php _e( 'Value:', 'pys' ); ?> &nbsp;&nbsp;
          <select name="pys[woo][tax]">
            <option <?php selected( 'incl', pys_get_option( 'woo', 'tax' ) ); ?> value="incl"><?php _e( 'Includes Tax', 'pys' ); ?></option>
            <option <?php selected( 'excl', pys_get_option( 'woo', 'tax' ) ); ?> value="excl"><?php _e( 'Excludes Tax', 'pys' ); ?></option>
          </select>

        </td>
      </tr>
      
      <tr style="vertical-align: top;">
        <td>
          
          <p><?php _e( '<strong>Important for Custom Audiences.</strong> Use this together with the General Event option.', 'pys' ); ?></p>
          <p><?php _e( 'Learn how to <strong>Create Powerful Custom Audiences</strong> based on Events: <strong><a href="http://www.pixelyoursite.com/use-general-event-existing-clients" target="_blank">Click to Download Your Free Guide</a></strong>', 'pys' ); ?></p>
          
        </td>
        <td></td>
      </tr>
      
      
    </table>
    
    <hr>
    <h2 class="section-title"><?php _e( 'ViewContent Event', 'pys' ); ?></h2>
    <p><?php _e( 'ViewContent is added on Product Pages and it is required for Facebook Dynamic Product Ads.', 'pys' ); ?></p>
    <table class="layout">
      <tr class="tall">
        <td colspan="2" class="narrow">
          <input type="checkbox" name="pys[woo][on_view_content]" value="1" class="woo-option"
            <?php pys_checkbox_state( 'woo', 'on_view_content' ); ?> >
            <strong><?php _e( 'Enable ViewContent on Product Pages', 'pys' ); ?></strong>
        </td>
      </tr>

	    <tr>
		    <td class="alignright"><p class="label"><?php _e( 'Delay', 'pys' ); ?></p></td>
		    <td>
			    <input type="number" name="pys[woo][on_view_content_delay]" value="<?php echo pys_get_option( 'woo', 'on_view_content_delay' ); ?>" min="0" step="0.1"> <?php _e( 'seconds', 'pys' ); ?>
		    </td>
	    </tr>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[woo][enable_view_content_value]" value="1"
              <?php pys_checkbox_state( 'woo', 'enable_view_content_value' ); ?> ><?php _e( 'Enable Value', 'pys' ); ?>
          <span class="help"><?php _e( 'Add value and currency - Important for ROI measurement', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
        <td></td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Product price', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][view_content_value_option]" value="price"
              <?php echo pys_radio_state( 'woo', 'view_content_value_option', 'price' ); ?> >
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Percent of product price', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][view_content_value_option]" value="percent"
              <?php echo pys_radio_state( 'woo', 'view_content_value_option', 'percent' ); ?> >
          <input type="text" name="pys[woo][view_content_percent_value]" value="<?php echo pys_get_option( 'woo', 'view_content_percent_value' ); ?>">%
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][view_content_value_option]" value="global"
              <?php echo pys_radio_state( 'woo', 'view_content_value_option', 'global' ); ?> >
          <input type="text" name="pys[woo][view_content_global_value]" value="<?php echo pys_get_option( 'woo', 'view_content_global_value' ); ?>">
        </td>
      </tr>

    </table>
    
    <hr>
    <h2 class="section-title"><?php _e( 'AddToCart Event', 'pys' ); ?></h2>
    <p><?php _e( 'AddToCart event will be added  on add to cart button click and on cart page. It is required for Facebook Dynamic Product Ads.', 'pys' ); ?></p>
    <table class="layout">
      <tr>
        <td colspan="2" class="narrow">
          
          <input type="checkbox" name="pys[woo][on_add_to_cart_btn]" value="1" class="woo-option"
            <?php pys_checkbox_state( 'woo', 'on_add_to_cart_btn' ); ?> >
            <strong><?php _e( 'Enable AddToCart on add to cart button', 'pys' ); ?></strong>

        </td>
      </tr>
      
      <tr class="tall">
        <td colspan="2" class="narrow">
          
          <input type="checkbox" name="pys[woo][on_cart_page]" value="1" class="woo-option"
            <?php pys_checkbox_state( 'woo', 'on_cart_page' ); ?> >
            <strong><?php _e( 'Enable AddToCart on cart page', 'pys' ); ?></strong>
            
        </td>
      </tr>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[woo][enable_add_to_cart_value]" value="1"
              <?php pys_checkbox_state( 'woo', 'enable_add_to_cart_value' ); ?> ><?php _e( 'Enable Value', 'pys' ); ?>
          <span class="help"><?php _e( 'Add value and currency - Important for ROI measurement', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
        <td></td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Products price (subtotal)', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][add_to_cart_value_option]" value="price"
              <?php echo pys_radio_state( 'woo', 'add_to_cart_value_option', 'price' ); ?> >
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Percent of products value (subtotal)', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][add_to_cart_value_option]" value="percent"
              <?php echo pys_radio_state( 'woo', 'add_to_cart_value_option', 'percent' ); ?> >
          <input type="text" name="pys[woo][add_to_cart_percent_value]" value="<?php echo pys_get_option( 'woo', 'add_to_cart_percent_value' ); ?>">%
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][add_to_cart_value_option]" value="global"
              <?php echo pys_radio_state( 'woo', 'add_to_cart_value_option', 'global' ); ?> >
          <input type="text" name="pys[woo][add_to_cart_global_value]" value="<?php echo pys_get_option( 'woo', 'add_to_cart_global_value' ); ?>">
        </td>
      </tr>

    </table>
    
    <hr>
    <h2 class="section-title"><?php _e( 'InitiateCheckout Event', 'pys' ); ?></h2>
    <p><?php _e( 'InitiateCheckout event will be enabled on the Checkout page. It is not mandatory for Facebook Dynamic Product Ads, but it is better to keep it on.', 'pys' ); ?></p>
    <table class="layout">
      
      <tr class="tall">
        <td colspan="2" class="narrow">
          
          <input type="checkbox" name="pys[woo][on_checkout_page]" value="1" class="woo-option"
            <?php pys_checkbox_state( 'woo', 'on_checkout_page' ); ?> >
            <strong><?php _e( 'Enable InitiateCheckout on Checkout page', 'pys' ); ?></strong>

        </td>
      </tr>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[woo][enable_checkout_value]" value="1"
              <?php pys_checkbox_state( 'woo', 'enable_checkout_value' ); ?> ><?php _e( 'Enable Value', 'pys' ); ?>
          <span class="help"><?php _e( 'Add value and currency - Important for ROI measurement', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
        <td></td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Products price (subtotal)', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][checkout_value_option]" value="price"
              <?php echo pys_radio_state( 'woo', 'checkout_value_option', 'price' ); ?> >
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Percent of products value (subtotal)', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][checkout_value_option]" value="percent"
              <?php echo pys_radio_state( 'woo', 'checkout_value_option', 'percent' ); ?> >
          <input type="text" name="pys[woo][checkout_percent_value]" value="<?php echo pys_get_option( 'woo', 'checkout_percent_value' ); ?>">%
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][checkout_value_option]" value="global"
              <?php echo pys_radio_state( 'woo', 'checkout_value_option', 'global' ); ?> >
          <input type="text" name="pys[woo][checkout_global_value]" value="<?php echo pys_get_option( 'woo', 'checkout_global_value' ); ?>">
        </td>
      </tr>

    </table>
    
    <hr>
    <h2 class="section-title"><?php _e( 'Purchase Event', 'pys' ); ?></h2>
    <p><?php _e( 'Purchase event will be enabled on the Thank You page. It is mandatory for Facebook Dynamic Product Ads.', 'pys' ); ?></p>
    <table class="layout">
      
      <tr class="tall">
        <td colspan="2" class="narrow">
          
          <input type="checkbox" name="pys[woo][on_thank_you_page]" value="1" class="woo-option"
            <?php pys_checkbox_state( 'woo', 'on_thank_you_page' ); ?> >
            <strong><?php _e( 'Enable Purchase event on Thank You page', 'pys' ); ?></strong>

        </td>
      </tr>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[woo][enable_purchase_value]" value="1"
              <?php pys_checkbox_state( 'woo', 'enable_purchase_value' ); ?> ><?php _e( 'Enable Value', 'pys' ); ?>
          <span class="help"><?php _e( 'Add value and currency - <strong>Very important for ROI measurement</strong>', 'pys' ); ?></span>
        </td>
      </tr>

	    <tr>
		    <td class="alignright"><p class="label"><?php _e( 'Fire the event on transaction only', 'pys' ); ?></p></td>
		    <td>
			    <select name="pys[woo][purchase_fire_once]">
				    <option <?php selected( 1, pys_get_option( 'woo', 'purchase_fire_once' ) ); ?> value="1"><?php _e( 'On', 'pys' ); ?></option>
				    <option <?php selected( 0, pys_get_option( 'woo', 'purchase_fire_once' ) ); ?> value="0"><?php _e( 'Off', 'pys' ); ?></option>
			    </select>
			    <span class="help"><?php _e( 'This will avoid the Purchase event to be fired when the order-received page is visited but no transaction has occurred. <b>It will improve conversion tracking.</b>', 'pys' ); ?></span>
		    </td>
	    </tr>
      
      <tr>
        <td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
        <td></td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Transport is:', 'pys' ); ?></p></td>
        <td>
          <select name="pys[woo][purchase_transport]" autocomplete="off">
            <option <?php selected( 'included', pys_get_option( 'woo', 'purchase_transport' ) ); ?> value="included"><?php _e( 'Included', 'pys' ); ?></option>
            <option <?php selected( 'excluded', pys_get_option( 'woo', 'purchase_transport' ) ); ?> value="excluded"><?php _e( 'Excluded', 'pys' ); ?></option>
          </select>
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Total', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][purchase_value_option]" value="total"
              <?php echo pys_radio_state( 'woo', 'purchase_value_option', 'total' ); ?> >
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Percent of Total', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][purchase_value_option]" value="percent"
              <?php echo pys_radio_state( 'woo', 'purchase_value_option', 'percent' ); ?> >
          <input type="text" name="pys[woo][purchase_percent_value]" value="<?php echo pys_get_option( 'woo', 'purchase_percent_value' ); ?>">%
        </td>
      </tr>
      
      <tr class="tall">
        <td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][purchase_value_option]" value="global"
              <?php echo pys_radio_state( 'woo', 'purchase_value_option', 'global' ); ?> >
          <input type="text" name="pys[woo][purchase_global_value]" value="<?php echo pys_get_option( 'woo', 'purchase_global_value' ); ?>">
        </td>
      </tr>

	    <tr>
		    <td class="alignright"><p class="label big"><?php _e( 'Custom Audience Optimization:', 'pys' ); ?></p></td>
		    <td>
			    <input type="checkbox" name="pys[woo][purchase_add_address]" value="1"
				    <?php pys_checkbox_state( 'woo', 'purchase_add_address' ); ?> >
			    <strong><?php _e( 'Add Town, State and Country parameters', 'pys' ); ?></strong>
			    <span class="help"><?php _e( 'Will pull <code>town</code>, <code>state</code> and <code>country</code>', 'pys' ); ?></span>

		    </td>
	    </tr>

	    <tr>
		    <td></td>
		    <td>
			    <input type="checkbox" name="pys[woo][purchase_add_payment_method]" value="1"
				    <?php pys_checkbox_state( 'woo', 'purchase_add_payment_method' ); ?> >
			    <strong><?php _e( 'Add Payment Method parameter', 'pys' ); ?></strong>
			    <span class="help"><?php _e( 'Will pull <code>payment</code>', 'pys' ); ?></span>

		    </td>
	    </tr>

	    <tr>
		    <td></td>
		    <td>
			    <input type="checkbox" name="pys[woo][purchase_add_shipping_method]" value="1"
				    <?php pys_checkbox_state( 'woo', 'purchase_add_shipping_method' ); ?> >
			    <strong><?php _e( 'Add Shipping Method parameter', 'pys' ); ?></strong>
			    <span class="help"><?php _e( 'Will pull <code>shipping</code>', 'pys' ); ?></span>
		    </td>
	    </tr>

	    <tr>
		    <td></td>
		    <td>
			    <input type="checkbox" name="pys[woo][purchase_add_coupons]" value="1"
				    <?php pys_checkbox_state( 'woo', 'purchase_add_coupons' ); ?> >
			    <strong><?php _e( 'Add Coupons parameter', 'pys' ); ?></strong>
			    <span class="help"><?php _e( 'Will pull <code>coupon_used</code> and <code>coupon_name</code>', 'pys' ); ?></span>
		    </td>
	    </tr>

    </table>
    
    <p><?php _e( '<strong>Important:</strong> For the Purchase Event to work, the client must be redirected on the default WooCommerce Thank You page after payment.', 'pys' ); ?></p>
    
    <hr>
    <h2 class="section-title"><?php _e( 'WooCommerce Affiliate Products Events', 'pys' ); ?></h2>
    <p><?php _e( 'You can add an event that will trigger each time an affiliate WooCommerce product button is clicked.', 'pys' ); ?></p>
    <table class="layout">
      
      <tr class="tall">
        <td colspan="2" class="narrow">
          <input type="checkbox" name="pys[woo][enable_aff_event]" value="1" class=""
              <?php pys_checkbox_state( 'woo', 'enable_aff_event' ); ?> ><strong><?php _e( 'Activate WooCommerce Affiliate Products Events', 'pys' ); ?></strong>
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Event type:', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][aff_event]" value="predefined"
              <?php echo pys_radio_state( 'woo', 'aff_event', 'predefined' ); ?> >

          <select name="pys[woo][aff_predefined_value]" autocomplete="off">
            <?php pys_event_types_select_options( pys_get_option( 'woo', 'aff_predefined_value' ), false ); ?>
          </select>
        </td>
      </tr>
      
      <tr class="tall">
        <td class="alignright"><p class="label"><?php _e( 'Name of custom event:', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][aff_event]" value="custom"
              <?php echo pys_radio_state( 'woo', 'aff_event', 'custom' ); ?> >

          <input type="text" name="pys[woo][aff_custom_value]" value="<?php echo pys_get_option( 'woo', 'aff_custom_value' ); ?>">

          <span class="help"><?php _e( '* The Affiliate event will have all the parameters values specific for selected event.', 'pys' ); ?></span>
          <span class="help"><?php _e( '* The Custom Affiliate event will have value, currency, content_name, content_type, content_ids.', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Do not pull event value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][aff_value_option]" value="none"
              <?php echo pys_radio_state( 'woo', 'aff_value_option', 'none' ); ?> >
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Event Value = Product Price', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][aff_value_option]" value="price"
              <?php echo pys_radio_state( 'woo', 'aff_value_option', 'price' ); ?> >
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][aff_value_option]" value="global"
              <?php echo pys_radio_state( 'woo', 'aff_value_option', 'global' ); ?> >

          <input type="text" name="pys[woo][aff_global_value]" value="<?php echo pys_get_option( 'woo', 'aff_global_value' ); ?>">

          <span class="help"><?php _e( '* Set this if you want a unique global value every time affiliate product clicked.', 'pys' ); ?></span>
        </td>
      </tr>

    </table>
    
    <hr>
    <h2 class="section-title"><?php _e( 'WooCommerce PayPal Standard Events', 'pys' ); ?></h2>
    <p><?php _e( 'You can add an event that will trigger on PayPal Standard button click.', 'pys' ); ?></p>
    <table class="layout">
      
      <tr class="tall">
        <td colspan="2" class="narrow">
          <input type="checkbox" name="pys[woo][enable_paypal_event]" value="1" class=""
              <?php pys_checkbox_state( 'woo', 'enable_paypal_event' ); ?> ><strong><?php _e( 'Activate PayPal Standard Events', 'pys' ); ?></strong>
        </td>
      </tr>
      
      <tr class="">
        <td class="alignright"><p class="label"><?php _e( 'Event type:', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][pp_event]" value="predefined"
              <?php echo pys_radio_state( 'woo', 'pp_event', 'predefined' ); ?> >

          <select name="pys[woo][pp_predefined_value]">
            <?php pys_event_types_select_options( pys_get_option( 'woo', 'pp_predefined_value' ), false ); ?>
          </select>

        </td>
      </tr>
      
      <tr class="tall">
        <td class="alignright"><p class="label"><?php _e( 'Name of custom event:', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][pp_event]" value="custom"
              <?php echo pys_radio_state( 'woo', 'pp_event', 'custom' ); ?> >

          <input type="text" name="pys[woo][pp_custom_value]" value="<?php echo pys_get_option( 'woo', 'pp_custom_value' ); ?>">

          <span class="help"><?php _e( '* The PayPal Standard event will have all the parameters values specific for selected event.', 'pys' ); ?></span>
          <span class="help"><?php _e( '* The Custom Affiliate event will have value, currency, content_type, content_ids.', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr class="">
        <td class="alignright"><p class="label"><?php _e( 'Do not pull event value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][pp_value_option]" value="none"
              <?php echo pys_radio_state( 'woo', 'pp_value_option', 'none' ); ?> >
        </td>
      </tr>
      
      <tr class="">
        <td class="alignright"><p class="label"><?php _e( 'Event Value = Cart Total', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][pp_value_option]" value="total"
              <?php echo pys_radio_state( 'woo', 'pp_value_option', 'total' ); ?> >
        </td>
      </tr>
      
      <tr class="">
        <td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
        <td>
          <input type="radio" name="pys[woo][pp_value_option]" value="global"
              <?php echo pys_radio_state( 'woo', 'pp_value_option', 'global' ); ?> >

          <input type="text" name="pys[woo][pp_global_value]" value="<?php echo pys_get_option( 'woo', 'pp_global_value' ); ?>">

          <span class="help"><?php _e( '* Set this if you want a unique global value every time affiliate product clicked.', 'pys' ); ?></span>
        </td>
      </tr>

    </table>
    
    <hr>
    
    <table class="layout">
      
      <tr>
        <td class="alignright">
          <p class="label big"><?php _e( 'Activate WooCommerce Pixel Settings', 'pys' ); ?></p>
        </td>
        <td>
          <input type="checkbox" name="pys[woo][enabled]" value="1"
            <?php pys_checkbox_state( 'woo', 'enabled' ); ?> >
        </td>
      </tr>
      
    </table>

    <button class="pys-btn pys-btn-blue pys-btn-big aligncenter"><?php _e( 'Save Settings', 'pys' ); ?></button>
    
  </div>
</div>