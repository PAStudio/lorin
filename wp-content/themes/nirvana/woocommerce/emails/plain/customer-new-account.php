<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

# echo "= " . $email_heading . " =\n\n";

# echo sprintf( __( "Thanks for creating an account on %s. Your username is <strong>%s</strong>", 'woocommerce' ), $blogname, $user_login ) . "\n\n";

# if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated )
#	echo sprintf( __( "Your password is <strong>%s</strong>.", 'woocommerce' ), $user_pass ) . "\n\n";

# echo sprintf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), wc_get_page_permalink( 'myaccount' ) ) . "\n\n";

echo "你好，\r\n";

echo "歡迎訂閱我的「佛學專題影片」，你的會員資格已經確認通過。\r\n";

echo "希望這些影片，能夠讓我與你都更加接近解脫之道、更加深刻地利益這個社會與世界。\r\n";

echo "敬祝 長壽無病 共得解脫\r\n";

echo "羅卓仁謙";

# echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

# echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
