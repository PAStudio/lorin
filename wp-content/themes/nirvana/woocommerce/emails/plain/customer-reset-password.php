<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/customer-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails/Plain
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Custom Email Content */
echo "你好，\n\n";

echo "我們收到重設密碼的要求，如果你並沒有提出相關要求，請忽略這封郵件。\n\n";

echo "如果你確實有提出申請，則連結如下：\n\n";

echo esc_url( add_query_arg( array( 'key' => $reset_key, 'login' => $user_login ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ) . "\n\n";

echo "敬祝 長壽無病 共得解脫\n\n";

echo "羅卓仁謙";

# echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
