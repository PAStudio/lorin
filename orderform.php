<?php

function encrypt($key = "", $iv = "", $str = "") {
	$str = trim(bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, addpadding($str), MCRYPT_MODE_CBC, $iv)));
	return $str;
}

function addpadding($string, $blocksize = 32) {
	$len = strlen($string);
	$pad = $blocksize - ($len % $blocksize);
	$string .= str_repeat(chr($pad), $pad);
	return $string;
}

function decrypt($key = '', $iv = '', $encrypt = ''){
	$str = strippadding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, hex2bin($encrypt), MCRYPT_MODE_CBC, $iv));
	return $str;
}
function strippadding($string) {
	$slast = ord(substr($string, -1));
	$slastc = chr($slast);
	if (preg_match("/$slastc{" . $slast . "}/", $string)) {
		$string = substr($string, 0, strlen($string) - $slast);
		return $string;
	}
	else {
		return false;
	}
}

$shopMerID = "MS111105162";
$shopKey = "VdhbCRuuN4EUqKGrcmS7uR3In7zofcgb";
$shopIV = "qyn3pRxcVc6mWNN8";
$postData = array(
	"RespondType" => "JSON",
	"TimeStamp" => time(),
	"Version" => "1.0",
	"MerOrderNo" => $shopMerID . time(),
	// "ProdDesc" => _e( 'Product', 'woocommerce' ),
	// "PeriodAmt" => _e( 'Total', 'woocommerce' ),
	"PeriodType" => "M",
	"PeriodPoint" => date("d"),
	"PeriodStartType" => 2,
	"PeriodTimes" => "12",
	// "ReturnURL" => "facebook.com",
	"PeriodMemo" => "",
	"PayerEmail" => "seanchen47@gmail.com",
	// "EmailModify" => "",	default enabled
	"PaymentInfo" => "N",
	"OrderInfo" => "N",
	// "NotifyURL" => "",	default disabled
	"BackURL" => "lodrorinchen.org/shop"
);

?>

<form action='https://core.spgateway.com/MPG/period' method='POST'>
<input type='hidden' name='MerchantID_' value='<?php echo $shopMerID; ?>'>
<input type='hidden' name='PostData_' value='<?php echo encrypt($shopKey, $shopIV, http_build_query($postData)); ?>'>
<input type='submit' value='一年訂閱'>
</form>

