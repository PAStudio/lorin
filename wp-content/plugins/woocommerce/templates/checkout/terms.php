<?php
/**
 * Checkout terms and conditions checkbox
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php do_action( 'woocommerce_checkout_before_terms_and_conditions' ); ?>
<p class="form-row terms wc-terms-and-conditions">
	<div class="renewal-provision" style="margin: 20px 0 20px 25%; width: 50%; height: 60px; overflow-y: scroll;">
		<p style="font-size: 11px;">訂閱本網站期間，您可以不限次數觀看以前至今發布的所有影片。會員權限視您訂閱種類，基本費為一季（一月或一週），暨訂閱後此基本期內費用不可退費。</p>
		<p style="font-size: 11px;">當您選擇使用信用卡付款，您授權本網站（羅卓仁謙）在每個會員到期日自動向您收取與您當前會員資格相符之訂閱金額，以繼續訂閱本網站，為期12個月。</p>
		<p style="font-size: 11px;">訂閱本網站視同同意不可重新錄製、下載或重製影片內容。您的個人資料受本網站遵循個人隱私權法保護。</p>
	</div>
	<input type="checkbox" class="input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
	<label for="terms" class="checkbox">
		<?php printf( __( '我已經瞭解並同意上述內容。', 'woocommerce' ) ); ?>
		<!--<span class="required">*</span>-->
	</label>
	<input type="hidden" name="terms-field" value="1" />
</p>

<script>
        jQuery(document).ready(function($) {
                var terms = $('#terms');
                var proceed_btn = $('#place_order');
		proceed_btn.attr('disabled', true);

                terms.on('click', function() {
                        if( terms.is(':checked') ) {
                                proceed_btn.attr('disabled', false);
                        }
                        else {
                                proceed_btn.attr('disabled', true);
                        }
                });
        });
</script>

<?php do_action( 'woocommerce_checkout_after_terms_and_conditions' ); ?>
