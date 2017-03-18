<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="pys-box">
	<div class="pys-col pys-col-full">
		<h2><?php _e( 'General Event (optional)', 'pys' ); ?></h2>
		<p><?php _e( 'This event can be very useful for building Custom Audiences based on Custom Combination.', 'pys' ); ?></p>
    
        <table class="layout">
			<tr class="tall">
				<td class="alignright"><p class="label"><?php _e( 'Enable general event setup', 'pys' ); ?></p></td>
				<td>
					<input type="checkbox" name="pys[general][general_event_enabled]"
					       value="1" <?php pys_checkbox_state( 'general', 'general_event_enabled' ); ?> >
				</td>
			</tr>

		    <tr class="tall">
			    <td></td>
			    <td>
				    <input type="checkbox" name="pys[general][general_event_add_tags]" value="1"
					    <?php pys_checkbox_state( 'general', 'general_event_add_tags' ); ?> ><?php _e( 'Track tags', 'pys' ); ?>
				    <span class="help"><?php _e( 'Will pull <code>tags</code> param on posts and custom post types', 'pys' ); ?></span>
			    </td>
		    </tr>

			<tr>
				<td class="alignright"><p class="label"><?php _e( 'General event name', 'pys' ); ?></p></td>
				<td>
					<input type="text" name="pys[general][general_event_name]"
					       value="<?php echo pys_get_option( 'general', 'general_event_name' ); ?>">
				</td>
			</tr>

	    <tr>
		    <td class="alignright"><p class="label"><?php _e( 'Delay', 'pys' ); ?></p></td>
		    <td>
			    <input type="number" name="pys[general][general_event_delay]" value="<?php echo pys_get_option( 'general', 'general_event_delay' ); ?>" min="0" step="0.1"> <?php _e( 'seconds', 'pys' ); ?>
			    <span class="help"><?php _e( 'Avoid retargeting bouncing users (It is better to add a lower time that the desired one because the pixel code will not load instantaneously). People that spent less time on the page will not be part of your Custom Audiences. You will not spend money retargeting them and your Lookalike Audiences will be more accurate.', 'pys' ); ?></span>
		    </td>
	    </tr>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[general][general_event_on_posts_enabled]" value="1"
            <?php pys_checkbox_state( 'general', 'general_event_on_posts_enabled' ); ?> ><?php _e( 'Enable on Posts', 'pys' ); ?>
          <span class="help"><?php _e( 'Will pull post title as <code>content_name</code> and post category name as <code>category_name</code>', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[general][general_event_on_pages_enabled]" value="1"
            <?php pys_checkbox_state( 'general', 'general_event_on_pages_enabled' ); ?> ><?php _e( 'Enable on Pages', 'pys' ); ?>
          <span class="help"><?php _e( 'Will pull page title as <code>content_name</code>', 'pys' ); ?></span>
        </td>
      </tr>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[general][general_event_on_tax_enabled]" value="1"
            <?php pys_checkbox_state( 'general', 'general_event_on_tax_enabled' ); ?> ><?php _e( 'Enable on Taxonomies', 'pys' ); ?>
          <span class="help"><?php _e( 'Will pull taxonomy name as <code>content_name</code>', 'pys' ); ?></span>
        </td>
      </tr>

	        <?php if ( pys_is_woocommerce_active() ) : ?>
	        <tr>
				<td></td>
				<td>
					<input type="checkbox" name="pys[general][general_event_on_woo_enabled]"
					       value="1" <?php pys_checkbox_state( 'general', 'general_event_on_woo_enabled' ); ?> >
					<?php _e( 'Enable on WooCommerce Products', 'pys' ); ?>
					<span
						class="help"><?php _e( 'Will pull product title as <code>content_name</code> and product category name as <code>category_name</code>, product price as <code>value</code>, currency as <code>currency</code>, post type as <code>content_type</code>.', 'pys' ); ?></span>
				</td>
			</tr>
	        <?php endif; ?>
      
			<?php if( pys_is_edd_active() ) : ?>
			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="pys[general][general_event_on_edd_enabled]"
					       value="1" <?php pys_checkbox_state( 'general', 'general_event_on_edd_enabled' ); ?> >
					<?php _e( 'Enable on Easy Digital Downloads Products', 'pys' ); ?>
					<span class="help"><?php _e( 'Will pull product title as <code>content_name</code> and product category name as <code>category_name</code>, product price as <code>value</code>, currency as <code>currency</code>, post type as <code>content_type</code>.', 'pys' ); ?></span>
				</td>
			</tr>
			<?php endif; ?>
      
      <?php
      
      // Display settings for all custom post types
      foreach( get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ) as $pt ) :
        
        // skip product post type when woo is active
        if( pys_is_woocommerce_active() && $pt->name == 'product' )
          continue;
          
        // skip download post type when eedd is active
        if( pys_is_edd_active() && $pt->name == 'download' )
          continue;
          
      ?>
      
      <tr>
        <td></td>
        <td>
          <input type="checkbox" name="pys[general][general_event_on_<?php echo $pt->name; ?>_enabled]" value="1"
            <?php pys_checkbox_state( 'general', 'general_event_on_' . $pt->name . '_enabled' ); ?>
          >
	        <?php 
	        printf(
		        __('Enable on %s Post Type' , 'pys' ),
		        $pt->label
	        ); 
	        ?>
          <span class="help">
	          <?php 
	          printf(
		          __( 'Will pull %1$s title as <code>content_name</code> and %2$s category name as <code>category_name</code>, <code>content_type</code> as <code>%3$s</code>.', 'pys' ),
		          $pt->name,
		          $pt->name,
		          $pt->name
	          );
	          ?>
          </span>
        </td>
      </tr>
      
      <?php endforeach; ?>
      
    </table>
    
    <p><?php _e( 'The General Event can help you create Super Powerful Custom Audiences, so we made a guide about how to use it: <a href="http://www.pixelyoursite.com/use-general-event-existing-clients" target="_blank">Click to download the guide</a>', 'pys' ); ?></p>
    
    <hr>

	  <h2><?php _e( 'TimeOnPage Event', 'pys' ); ?></h2>
	  <p><?php _e( 'TimeOnPage event will pull the time spent on each page in seconds, the page name as <code>content_name</code>, and the page ID as <code>content_ids</code>. Use it to create CustomAudiences for key pages <strong>where only people that spend a minimum amount of time matter for your business</strong>. This will improve your retargeting as well as Lookalike Audiences.', 'pys' ); ?></p>

	  <table class="layout">

		  <tr>
			  <td class="alignright"><p class="label"><?php _e( 'Enable TimeOnPage event setup', 'pys' ); ?></p></td>
			  <td>
				  <input type="checkbox" name="pys[general][timeonpage_enabled]" value="1"
					  <?php echo pys_checkbox_state( 'general', 'timeonpage_enabled' ); ?>
				  disabled> <strong>Event temporary disabled. Will be fixed soon.</strong>
			  </td>
		  </tr>

	  </table>

	  <p><?php _e( 'You can read more about this event on <a href="http://www.pixelyoursite.com/facebook-pixel-plugin-help" target="_blank">our help page</a>', 'pys' ); ?></p>

	  <hr>
    
    <h2><?php _e( 'Search Event', 'pys' ); ?></h2>
    <p><?php _e( 'The Search event will be active on Search page and will automatically pull search string as parameter. Useful for creating Custom Audiences.', 'pys' ); ?></p>
    
    <table class="layout">
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Enable Search event setup', 'pys' ); ?></p></td>
        <td>
          <input type="checkbox" name="pys[general][search_event_enabled]" value="1"
            <?php pys_checkbox_state( 'general', 'search_event_enabled' ); ?> >
        </td>
      </tr>
      
    </table>
    
    <hr>
    
    <table class="layout">
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Remove Pixel for:', 'pys' ); ?></p></td>
        <td></td>
      </tr>
      
      <?php
      
      /**
       * List all available roles
       */ 
      
      global $wp_roles;
      
      if( !isset( $wp_roles ) ) {
        $wp_roles = new WP_Roles();
      }
      
      $roles = $wp_roles->get_names();
      foreach( $roles as $role_value => $role_name ) : ?>
      
      <tr>
        <td class="alignright"><?php echo $role_name; ?></td>
        <td>
          <input type="checkbox" name="pys[general][disable_for_<?php echo $role_value; ?>]" value="1"
            <?php pys_checkbox_state( 'general', 'disable_for_' . $role_value ); ?> >
        </td>
      </tr>
        
      <?php endforeach; ?>

	    <tr>
		    <td class="alignright"><?php _e( 'Guest', 'pys' ); ?></td>
		    <td>
			    <input type="checkbox" name="pys[general][disable_for_guest]" value="1"
				    <?php pys_checkbox_state( 'general', 'disable_for_guest' ); ?> >
		    </td>
	    </tr>
      
    </table>
    
  </div>
</div>