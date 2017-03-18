<?php

function encrypt($key = "", $iv = "", $str = "") {
	$str = trim(bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, addpadding($str), MCRYPT_MODE_CBC, $iv)));
	return $str;
}

#function addpadding($string, $blocksize = 32) {
#	$len = strlen($string);
#	$pad = $blocksize - ($len % $blocksize);
#	$string .= str_repeat(chr($pad), $pad);
#	return $string;
#}

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

function getPeriodAmt() {
	// For seanson members
	if(WC()->cart->total == "1197") {
		return "399";
	}
	// Return total pay amount
	return WC()->cart->total;
}

function getPeriodType($periodAmt) {
	// Weekly
	if($periodAmt == "199") {
		return "W";
	}
	// Monthly
	else if($periodAmt == "499" || $periodAmt == "399") {
		return "M";
	}
	// Invalid periodType
	else {
		return "";
	}
}

function getPeriodPoint($periodAmt) {
	// Weekly
	if($periodAmt == "199") {
		return date("w");
	}
	// Monthly
	else if($periodAmt == "499" || $periodAmt == "399") {
		return date("d");
	}
	// Invalid periodPoint
	else {
		return "";
	}
}

function getPeriodTimes($periodAmt) {
	// Weekly
	if($periodAmt == "199") {
		return "55";
	}
	// Monthly
	else if($periodAmt == "499" || $periodAmt == "399") {
		return "12";
	}
	// Invalid periodTimes
	else {
		return "";
	}
}

$periodURL = "https://core.spgateway.com/MPG/period";
$shopMerID = "MS111105162";
$shopKey = "VdhbCRuuN4EUqKGrcmS7uR3In7zofcgb";
$shopIV = "qyn3pRxcVc6mWNN8";
# $periodURL = "https://ccore.spgateway.com/MPG/period";
# $shopMerID = "MS11114914";
# $shopKey = "S9VrZpnplIEOssEGgkhcGCXtJu7Aljvd";
# $shopIV = "trEsR2ScKw0cZFTf";
$periodAmt = getPeriodAmt();
$postData = array(
	"RespondType" => "JSON",
	"TimeStamp" => time(),
	"Version" => "1.0",
	"MerOrderNo" => $shopMerID . time(),
	"ProdDesc" => "會員權限",
	"PeriodAmt" => $periodAmt,
	"PeriodType" => getPeriodTypes($periodAmt),
	"PeriodPoint" => getPeriodPoint($periodAmt),
	"PeriodStartType" => 2,
	"PeriodTimes" => getPeriodTimes($periodAmt),
	// "ReturnURL" => "",
	"PeriodMemo" => "",
	"PayerEmail" => "",
	// "EmailModify" => "",	default enabled
	"PaymentInfo" => "N",
	"OrderInfo" => "N",
	// "NotifyURL" => "",	default disabled
	"BackURL" => "lodrorinchen.org/shop"
);

?>
