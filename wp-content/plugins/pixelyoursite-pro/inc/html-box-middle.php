<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

?>

<div class="pys-box pys-box-middle">
  <div class="pys-col pys-col-narrow">

    <?php if( pys_is_woocommerce_active() ) : ?>

      <h2><?php _e( 'How to create a <strong>Facebook Dynamic Ads Product Catalog</strong> in just <i>5 minutes</i>', 'pys' ); ?></h2>
      <p><?php _e( 'Using Facebook Dynamic Ads lets you create automatic retargeting campaigns, and we made a VIDEO on how to make a Product Catalog with just a few clicks', 'pys' ); ?></p>

      <a href="http://www.pixelyoursite.com/facebook-dynamic-product-catalog-setup" target="_blank" class="pys-btn pys-btn-red"><?php _e( 'Watch Video', 'pys' ); ?></a>

    <?php else: ?>

      <h2><?php _e( 'How to create <strong>Super Powerful Custom Audiences</strong> using the <i>General Event</i> option from PixelYourSite', 'pys' ); ?></h2>
      <p><?php _e( 'The "General Event" option from PixelYourSite plugin can be extremely useful, so we decided to write an actionable guide about how to use it to increase your profit', 'pys' ); ?></p>

      <a href="http://www.pixelyoursite.com/use-general-event-existing-clients" target="_blank" class="pys-btn pys-btn-red"><?php _e( 'Download the guide', 'pys' ); ?></a>

    <?php endif; ?>
    
  </div>
</div>