<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="pys-box">
  <div class="pys-col pys-col-full">
    <h2 class="section-title"><?php _e( 'Active Dynamic Events', 'pys' ); ?></h2>
	
	  <?php
	
	  $show_modal_url = add_query_arg(
		  array(
			  'action'   => 'pys_edit_dyn_event',
			  '_wpnonce' => wp_create_nonce( 'pys_show_event_modal' )
		  ),
		  admin_url( 'admin-ajax.php' )
	  );
	
	  ?>

    <div class="tablenav top">
     <a href="<?php echo esc_url( $show_modal_url ); ?>"
        class="button button-primary action thickbox"><?php _e( 'Add new event', 'pys' ); ?></a>
      <a href="#" class="button action" id="pys-bulk-delete-dyn-events"><?php _e( 'Delete selected', 'pys' ); ?></a>
    </div>
  
    <table class="widefat fixed pys-list ">
      <thead>
        <tr>
          <td class="check-column"><input type="checkbox"></td>
          <th scope="col" class="column-type"><?php _e( 'Trigger On', 'pys' ); ?></th>
          <th scope="col" class="column-url"><?php _e( 'URL / CSS / Position', 'pys' ); ?></th>
          <th scope="col" class="column-type"><?php _e( 'Type', 'pys' ); ?></th>
          <th scope="col" class="column-code"><?php _e( 'Code', 'pys' ); ?></th>
          <th scope="col" class="column-actions"><?php _e( 'Actions', 'pys' ); ?></th>
        </tr>
      </thead>
      <tbody>

      <?php if( $dyn_events = get_option( 'pixel_your_site_dyn_events' ) ) : ?>

        <?php foreach( $dyn_events as $key => $params ) : ?>

		      <?php

		      // skip empty dynamic events
		      if( ! isset( $params['eventtype'] ) ) {
			      continue;
		      }

		      switch( $params['trigger_type'] ) {
			      case 'URL':
				      $type = __( 'Click on URL', 'pys' );
				      break;

			      case 'CSS':
				      $type = __( 'Click on CSS Selector', 'pys' );
				      break;

			      case 'scroll':
				      $type = __( 'Scroll', 'pys' );
				      break;

			      case 'mouse-over':
				      $type = __( 'Mouse Over', 'pys' );
				      break;

			      default:
				      $type = $params['trigger_type'];
		      }

		      switch( $params['trigger_type'] ) {
			      case 'URL':
				      $value = $params['url'];
				      break;

			      case 'CSS':
				  case 'mouse-over':
				      $value = stripcslashes( $params['css'] );
				      break;

			      case 'scroll':
				      $value = $params['scroll_pos'] . '%';
				      break;

			      default:
				      $value = null;
		      }

		      ?>

        <tr>
          <th scope="row" class="check-column">
            <input type="checkbox" class="dyn-event-check" data-id="<?php esc_attr_e( $key ); ?>">
          </th>

          <td><?php echo esc_html( $type ); ?></td>
          <td><pre><?php echo $value; ?></pre></td>
          <td><?php echo $params['eventtype']; ?></td>

          <td>
            <?php

            $code = '';
            if ( $params['eventtype'] == 'CustomCode' ) {

	            $code = $params['code'];

            } else {

	            $code = pys_render_event_code( $params['eventtype'], $params );

            }

            $code = stripcslashes( $code );
            $code = trim( $code );
            echo '<pre>' . $code . '</pre>';

            ?>
          </td>
          <td>

	          <?php

	          $edit_event_url = add_query_arg(
		          array(
			          'action'   => 'pys_edit_dyn_event',
			          '_wpnonce' => wp_create_nonce( 'pys_show_event_modal' ),
			          'id'       => $key,
		          ),
		          admin_url( 'admin-ajax.php' )
	          );

	          $delete_event_url = add_query_arg(
		          array(
			          'action'      => 'pys_delete_events',
			          '_wpnonce'    => wp_create_nonce( 'pys_delete_events' ),
			          'events_ids'  => array( $key ),
			          'events_type' => 'dynamic'
		          ),
		          admin_url( 'admin.php?page=pixel-your-site' )
	          );

	          ?>

				<a href="<?php echo esc_url( $edit_event_url ); ?>" class="button action thickbox"><?php _e( 'Edit', 'pys' ); ?></a>
				<a href="<?php echo esc_url( $delete_event_url ); ?>" class="button btn-delete-std-event action"><?php _e( 'Delete', 'pys' ); ?></a>

          </td>

        </tr>

        <?php endforeach; ?>

      <?php endif; ?>

      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
	jQuery(function ($) {

		/**
		 * Bulk delete Standard events
		 */
		$('#pys-bulk-delete-dyn-events').on('click', function (e) {

			e.preventDefault();
			$(this).addClass('disabled');

			// collect all selected rows to ids array
			var ids = [];
			$.each($('.dyn-event-check'), function (index, check) {
				if ($(check).prop('checked') == true) {
					ids.push($(check).data('id'));
				}
			});

			$.ajax({
				url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				type: 'post',
				dataType: 'json',
				data: {
					action: 'pys_bulk_delete_dyn_events',
					_wpnonce: '<?php echo wp_create_nonce( "pys_bulk_delete_dyn_events" ); ?>',
					events_ids: ids
				}
			})
				.done(function (data) {
					location.href = '<?php echo admin_url( "admin.php?page=pixel-your-site&active_tab=dynamic-events" ); ?>';
				});

		});

	});
</script>