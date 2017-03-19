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
	<div class="renewal-provision" style="margin: 20px 0 20px 5%; width: 90%; height: 130px; overflow-y: scroll;">
		<p style="font-size: 11px;">訂閱本網站期間，您可以不限次數觀看以前至今發布的所有影片。會員權限視您訂閱種類，基本費為一季（一月或一週），暨訂閱後此基本期內費用不可退費。</p>
		<p style="font-size: 11px;">當您選擇訂閱，您授權本網站（羅卓仁謙）在每個會員到期日自動向您收取與您當前會員資格相符之訂閱金額，以繼續訂閱本網站，為期12個月。</p>
		<p style="font-size: 11px;">當您選擇訂閱當季會員，本網站將會在每個月向您收取當季會員1/3之金額。在該季會員到期前（三個月內），本網站將不接受停止訂閱。您必須至少訂閱一期後才可提出終止授權，否則視同違約。</p>
		<p style="font-size: 11px;">訂閱本網站視同同意不可重新錄製、下載或重製影片內容。您的個人資料受本網站遵循個人隱私權法保護。</p>
	</div>
	<input type="checkbox" class="input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
	<label for="terms" class="checkbox">
		<?php printf( __( '我已經瞭解並同意上述內容。', 'woocommerce' ) ); ?>
		<!--<span class="required">*</span>-->
	</label>
	<input type="hidden" name="terms-field" value="1" />
</p>
<p>若您選擇升級會員，請於成功購買後通知<a href="mailto:admin@lodrorinchen.org">admin@lodrorinchen.org</a> 您的訂單編號以更新您的授權委託。</p>

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

