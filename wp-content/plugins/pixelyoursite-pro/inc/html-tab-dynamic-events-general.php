<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

?>

<div class="pys-box">
  <div class="pys-col pys-col-full">
    <div style="text-align: center; margin-top: 13px;">

      <?php

      $show_modal_url = add_query_arg(
          array(
              'action'   => 'pys_edit_dyn_event',
              '_wpnonce' => wp_create_nonce( 'pys_show_event_modal' )
          ),
          admin_url( 'admin-ajax.php' )
      );

      ?>

      <a href="<?php echo esc_url( $show_modal_url ); ?>" class="pys-btn pys-btn-big pys-btn-blue thickbox"><?php _e( 'Add New Dynamic Event', 'pys' ); ?></a>
      <p><?php _e( 'Add standard or custom events that will trigger when a visitor clicks a link or a button on your website.', 'pys' ); ?></p>
    </div>

  </div>
</div>

<div class="pys-box">
  <div class="pys-col pys-col-full">
    
    <table class="layout">
      <tr>
        <td class="alignright"><p class="label big"><?php _e( 'Activate Dynamic Events', 'pys' ); ?></p></td>
        <td>
          <input type="checkbox" name="pys[dyn][enabled]" value="1"
              <?php pys_checkbox_state( 'dyn', 'enabled' ); ?> >
        </td>
      </tr>
      
      <tr>
        <td class="alignright"><p class="label"><?php _e( 'Process links', 'pys' ); ?></p></td>
        <td>
          <input type="checkbox" name="pys[dyn][enabled_on_content]" value="1"
              <?php pys_checkbox_state( 'dyn', 'enabled_on_content' ); ?> ><?php _e( 'Process links in Post Content', 'pys' ); ?>
          <span class="help"><?php _e( 'The <code>the_content()</code> hook.', 'pys' ); ?></span>

          <input type="checkbox" name="pys[dyn][enabled_on_widget]" value="1"
              <?php pys_checkbox_state( 'dyn', 'enabled_on_widget' ); ?> ><?php _e( 'Process links in Widgets Text', 'pys' ); ?>
          <span class="help"><?php _e( 'The <code>widget_text()</code> hook.', 'pys' ); ?></span>
        </td>
      </tr>
    </table>
    
    <button class="pys-btn pys-btn-blue pys-btn-big aligncenter"><?php _e( 'Save Settings', 'pys' ); ?></button>
    
  </div>

</div>