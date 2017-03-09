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
	"TimeStamp" => (string)time(),
	"Version" => "1.0",
	"MerOrderNo" => $shopMerID . time(),
	"ProdDesc" => "當月會員",
	"PeriodAmt" => 499,
	"PeriodType" => "M",
	"PeriodPoint" => "01",
	"PeriodStartType" => 2,
	"PeriodTimes" => "12",
	"ReturnURL" => "facebook.com",
	"PeriodMemo" => "123",
	"PayerEmail" => "seanchen47@gmail.com",
	"EmailModify" => 1,
	"PaymentInfo" => "N",
	"OrderInfo" => "N",
	"NotifyURL" => "facebook.com",
	"BackURL" => "facebook.com"
);

?>

<form action='https://core.spgateway.com/MPG/period' method='POST'>
<input type='text' name='MerchantID_' value='<?php echo $shopMerID; ?>'>
<input type='text' name='PostData_' value='<?php echo encrypt($shopKey, $shopIV, http_build_query($postData)); ?>'>
<input type='submit' value='go'>
</form>

<?php

echo decrypt($shopKey, $shopIV, encrypt($shopKey, $shopIV, http_build_query($postData)));

?>

