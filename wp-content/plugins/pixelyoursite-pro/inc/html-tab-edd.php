<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="pys-box">
	<div class="pys-col pys-col-full">
		<h2 class="section-title"><?php _e( 'Easy Digital Downloads Settings', 'pys' ); ?></h2>
		<p><?php _e( 'Add all necessary events on Easy Digital Downloads with just a few clicks. On this tab you will find powerful options to customize the Facebook Pixel for your store.', 'pys' ); ?></p>

		<hr>
		<h2 class="section-title"><?php _e( 'Facebook Dynamic Product Ads Pixel Settings', 'pys' ); ?></h2>
		<table class="layout">

			<tr class="tall">
                <td colspan="2" class="narrow">
                    <input type="checkbox" class="edd-events-toggle">
	                <strong><?php _e( 'Enable Facebook Dynamic Product Ads', 'pys' ); ?></strong>
                    <span class="help"><?php _e( 'This will automatically add ViewContent on download pages, AddToCart on add to cart button click, InitiateCheckout on checkout page and Purchase on thank you page. The events will have the required <code>content_ids</code> and <code>content_type</code> fields.', 'pys' ); ?></span>
                </td>
            </tr>

			<tr>
				<td class="alignright"><p class="label">content_ids:</p></td>
                <td>
	                <select name="pys[edd][content_id]">
                        <option <?php selected( 'id', pys_get_option( 'edd', 'content_id' ) ); ?> value="id"><?php _e( 'Download ID', 'pys' ); ?></option>
		                <option <?php selected( 'sku', pys_get_option( 'edd', 'content_id' ) ); ?> value="sku"><?php _e( 'Download SKU', 'pys' ); ?></option>
	                </select>
                </td>
            </tr>
		</table>

		<hr>
		<table class="layout">
			<tr style="vertical-align: top;">
				<td>
					<h2><?php _e( 'Custom Audiences Optimization', 'pys' ); ?></h2>
					<?php pys_checkbox( 'edd', 'enable_additional_params' ); ?>
					<?php _e( 'Enable Additional Parameters', 'pys' ); ?>
					<span class="help"><?php _e( 'Download name will be pulled as <code>content_name</code>, and Download Category as <code>category_name</code> for all EDD events.', 'pys' ); ?></span>
					<span class="help" style="margin-bottom: 20px;"><?php _e( 'The number of items is <code>num_items</code> for InitiateCheckout and Purchase events.', 'pys' ); ?></span>
					<?php pys_checkbox( 'edd', 'enable_tags' ); ?>
					<?php _e( 'Track tags', 'pys' ); ?>
					<span class="help"><?php _e( 'Will pull <code>tags</code> param on all EDD events.', 'pys' );
						?></span>
				</td>

				<td>
					<h2><?php _e( 'Tax Options', 'pys' ); ?></h2>
					<?php _e( 'Value:', 'pys' ); ?> &nbsp;&nbsp;
					<select name="pys[edd][tax]">
						<option <?php selected( 'incl', pys_get_option( 'edd', 'tax' ) ); ?>
							value="incl"><?php _e( 'Includes Tax', 'pys' ); ?></option>
						<option <?php selected( 'excl', pys_get_option( 'edd', 'tax' ) ); ?>
							value="excl"><?php _e( 'Excludes Tax', 'pys' ); ?></option>
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

		<!-- ViewContent -->
		<hr>
		<h2 class="section-title"><?php _e( 'ViewContent Event', 'pys' ); ?></h2>
		<p><?php _e( 'ViewContent is added on Download Pages and it is required for Facebook Dynamic Product Ads.', 'pys' ); ?></p>
		<table class="layout">

			<tr class="tall">
				<td colspan="2" class="narrow">
					<?php pys_checkbox( 'edd', 'on_view_content', 'edd-option' ); ?>
                    <strong><?php _e( 'Enable ViewContent on Download Pages', 'pys' ); ?></strong>
                </td>
            </tr>

			<tr>
		        <td class="alignright"><p class="label"><?php _e( 'Delay', 'pys' ); ?></p></td>
		        <td>
			        <input type="number" name="pys[edd][on_view_content_delay]"
			               value="<?php echo pys_get_option( 'edd', 'on_view_content_delay' ); ?>" min="0" step="0.1">
			        <?php _e( 'seconds', 'pys' ); ?>
		        </td>
	        </tr>

			<tr>
				<td></td>
				<td>
					<?php pys_checkbox( 'edd', 'enable_view_content_value' ); ?>
					<?php _e( 'Enable Value', 'pys' ); ?>
					<span class="help"><?php _e( 'Add value and currency - Important for ROI measurement', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
				<td></td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Download price', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'view_content_value_option', 'price' ); ?>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Percent of download price', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'view_content_value_option', 'percent' ); ?>
					<?php pys_text_field( 'edd', 'view_content_percent_value' ); ?>&nbsp;%
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'view_content_value_option', 'global' ); ?>
					<?php pys_text_field( 'edd', 'view_content_global_value' ); ?>
				</td>
			</tr>

		</table>

		<!-- AddToCart -->
		<hr>
		<h2 class="section-title"><?php _e( 'AddToCart Event', 'pys' ); ?></h2>
		<p><?php _e( 'AddToCart event will be added  on add to cart button click and on cart page. It is required for Facebook Dynamic Product Ads.', 'pys' ); ?></p>
		<table class="layout">

			<tr>
				<td colspan="2" class="narrow">
					<?php pys_checkbox( 'edd', 'on_add_to_cart_btn', 'edd-option' ); ?>
					<strong><?php _e( 'Enable AddToCart on add to cart button', 'pys' ); ?></strong>
                </td>
            </tr>
			
			<tr>
				<td></td>
				<td>
					<?php pys_checkbox( 'edd', 'enable_add_to_cart_value' ); ?>
					<?php _e( 'Enable Value', 'pys' ); ?>
					<span class="help"><?php _e( 'Add value and currency - Important for ROI measurement', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
				<td></td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Downloads price (subtotal)', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'add_to_cart_value_option', 'price' ); ?>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Percent of downloads value (subtotal)', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'add_to_cart_value_option', 'percent' ); ?>
					<?php pys_text_field( 'edd', 'add_to_cart_percent_value' ); ?>&nbsp;%
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'add_to_cart_value_option', 'global' ); ?>
					<?php pys_text_field( 'edd', 'add_to_cart_global_value' ); ?>
				</td>
			</tr>

		</table>
		
		<!-- InitiateCheckout -->
		<hr>
		<h2 class="section-title"><?php _e( 'InitiateCheckout Event', 'pys' ); ?></h2>
		<p><?php _e( 'InitiateCheckout event will be enabled on the Checkout page. It is not mandatory for Facebook Dynamic Product Ads, but it is better to keep it on.', 'pys' ); ?></p>
		<table class="layout">

			<tr class="tall">
				<td colspan="2" class="narrow">
					<?php pys_checkbox( 'edd', 'on_checkout_page', 'edd-option' ); ?>
					<strong><?php _e( 'Enable InitiateCheckout on Checkout page', 'pys' ); ?></strong>
                </td>
            </tr>
			
			<tr>
				<td></td>
				<td>
					<?php pys_checkbox( 'edd', 'enable_checkout_value' ); ?>
					<?php _e( 'Enable Value', 'pys' ); ?>
					<span class="help"><?php _e( 'Add value and currency - Important for ROI measurement', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
				<td></td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Downloads price (subtotal)', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'checkout_value_option', 'price' ); ?>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p
						class="label"><?php _e( 'Percent of downloads value (subtotal)', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'checkout_value_option', 'percent' ); ?>
					<?php pys_text_field( 'edd', 'checkout_percent_value' ); ?>&nbsp;%
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'checkout_value_option', 'global' ); ?>
					<?php pys_text_field( 'edd', 'checkout_global_value' ); ?>
				</td>
			</tr>

		</table>

		<!-- Purchase -->
		<hr>
		<h2 class="section-title"><?php _e( 'Purchase Event', 'pys' ); ?></h2>
		<p><?php _e( 'Purchase event will be enabled on the Success Page. It is mandatory for Facebook Dynamic Product Ads.', 'pys' ); ?></p>
		<table class="layout">

			<tr class="tall">
				<td colspan="2" class="narrow">
					<?php pys_checkbox( 'edd', 'on_success_page', 'edd-option' ); ?>
					<strong><?php _e( 'Enable Purchase event on Success Page', 'pys' ); ?></strong>
				</td>
			</tr>

			<tr>
				<td></td>
				<td>
					<?php pys_checkbox( 'edd', 'enable_purchase_value' ); ?>
					<?php _e( 'Enable Value', 'pys' ); ?>
					<span class="help"><?php _e( 'Add value and currency - <strong>Very important for ROI 
					measurement</strong>', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td class="alignright">
					<p class="label"><?php _e( 'Fire the event on transaction only', 'pys' ); ?></p>
				</td>
				<td>
					<select name="pys[edd][purchase_fire_once]">
						<option <?php selected( 1, pys_get_option( 'edd', 'purchase_fire_once' ) ); ?>
							value="1"><?php _e( 'On', 'pys' ); ?></option>
						<option <?php selected( 0, pys_get_option( 'edd', 'purchase_fire_once' ) ); ?>
							value="0"><?php _e( 'Off', 'pys' ); ?></option>
					</select>
					<span class="help"><?php _e( 'This will avoid the Purchase event to be fired when the success page is visited but no transaction has occurred. <b>It will improve conversion tracking.</b>', 'pys' ); ?></span>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label big"><?php _e( 'Define value:', 'pys' ); ?></p></td>
				<td></td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Total', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'purchase_value_option', 'total' ); ?>
				</td>
			</tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'Percent of Total', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'purchase_value_option', 'percent' ); ?>
					<?php pys_text_field( 'edd', 'purchase_percent_value' ); ?>&nbsp;%
				</td>
			</tr>

			<tr class="tall">
				<td class="alignright"><p class="label"><?php _e( 'Use Global value', 'pys' ); ?></p></td>
				<td>
					<?php pys_radio_box( 'edd', 'purchase_value_option', 'global' ); ?>
					<?php pys_text_field( 'edd', 'purchase_global_value' ); ?>
				</td>
			</tr>

			<tr>
			    <td class="alignright">
				    <p class="label big"><?php _e( 'Custom Audience Optimization:', 'pys' ); ?></p>
			    </td>
			    <td>
				    <?php pys_checkbox( 'edd', 'purchase_add_address' ); ?>
				    <strong><?php _e( 'Add Town, State and Country parameters', 'pys' ); ?></strong>
				    <span class="help"><?php _e( 'Will pull <code>town</code>, <code>state</code> and <code>country</code>', 'pys' ); ?></span>
			    </td>
	        </tr>

			<tr>
			    <td></td>
			    <td>
				    <?php pys_checkbox( 'edd', 'purchase_add_payment_method' ); ?>
				    <strong><?php _e( 'Add Payment Method parameter', 'pys' ); ?></strong>
				    <span class="help"><?php _e( 'Will pull <code>payment</code>', 'pys' ); ?></span>
			    </td>
			</tr>

			<tr>
			    <td></td>
			    <td>
				    <?php pys_checkbox( 'edd', 'purchase_add_coupons' ); ?>
				    <strong><?php _e( 'Add Coupons parameter', 'pys' ); ?></strong>
			        <span class="help"><?php _e( 'Will pull <code>coupon_used</code> and <code>coupon_name</code>', 'pys' ); ?></span>
			    </td>
			</tr>

		</table>

		<p><?php _e( '<strong>Important:</strong> For the Purchase Event to work, the client must be redirected on the EDD Success Page after payment.', 'pys' ); ?></p>

		<!-- Activate EDD -->
		<hr>
        <table class="layout">
	        <tr>
		        <td class="alignright">
			        <p class="label big"><?php _e( 'Activate Easy Digital Downloads Pixel Settings', 'pys' ); ?></p>
		        </td>
		        <td>
			        <?php pys_checkbox( 'edd', 'enabled' ); ?>
		        </td>
	        </tr>
        </table>

		<button class="pys-btn pys-btn-blue pys-btn-big aligncenter"><?php _e( 'Save Settings', 'pys' ); ?></button>

	</div>
</div>