<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PYS_PRO_NOTICES_VERSION', '5.0.0' );

## WOO activated, PCF is not
$GLOBALS['PYS_PRO_WOO_AND_NO_PCF'] = array(
	
	array(
		'from'    => 1,
		'to'      => 1,
		'content' => '<strong>PixelYourSite Recommendation:</strong> Create a Facebook Product Catalog for Dynamic Ads with just a few clicks with our dedicated <a href="http://www.pixelyoursite.com/product-catalog-facebook" target="_blank">Product Catalog Feed for WooCommerce</a>.',
		'visible' => true
	)

);

## EDD activated, WOO is not
$GLOBALS['PYS_PRO_EDD_ONLY'] = array(
	
	array(
		'from'    => 1,
		'to'      => 1,
		'content' => '<strong>PixelYourSite Update:</strong> Out of the box Facebook Pixel Implementation for Easy Digital Downloads. Open PixelYourSite admin or <a href="http://www.pixelyoursite.com/easy-digital-download-facebook-pixel-help" target="_blank">check this help page</a>.',
		'visible' => true
	)

);

## WOO and EDD not activated
$GLOBALS['PYS_PRO_NO_WOO_NO_EDD'] = array(
	
	array(
		'from'    => 1,
		'to'      => 1,
		'content' => null,
		'visible' => true
	)

);