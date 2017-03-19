<?php
if( !isset($_SESSION) ) {
	session_start();
}
/**
 * Spgateway decrypt AES functions
 */
function strippadding($string) {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
                $string = substr($string, 0, strlen($string) - $slast);
                return $string;
        } else {
                return false;
        }
}

function decrypt($key = '', $iv = '', $encrypt = ''){
        $str = strippadding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, hex2bin($encrypt), MCRYPT_MODE_CBC, $iv));
	return $str;
}	

function get_payment_status($postdata) {
	// $shopMerID = "MS11114914";
        // $HashKey = "S9VrZpnplIEOssEGgkhcGCXtJu7Aljvd";
        // $HashIV = "trEsR2ScKw0cZFTf";
	// $shopMerID = "MS111105162";
	$HashKey = "VdhbCRuuN4EUqKGrcmS7uR3In7zofcgb";
	$HashIV = "qyn3pRxcVc6mWNN8";
	
	$period_decrypted = decrypt($HashKey, $HashIV, $postdata);
	$period_datas = explode("&", $period_decrypted);
	$status_message = $period_datas[0];	// Status=....
	$status = substr($status_message, strlen("SUCCESS")) == "SUCCESS" ? "SUCCESS" : "FAILED";
	return $status;
}

$payment_status = get_payment_status($_POST["Period"]);
?>
