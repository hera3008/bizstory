<?php
	include "../common/setting.php";
	include "../common/no_direct.php";

	$api_key = 'a71346563a2c47b461325723385599';
	$txtdong = utf_han($txtdong);

	$ie_address  = 'http://biz.epost.go.kr/KpostPortal/openapi?regkey=' . $api_key . '&target=post&query=' . $txtdong;
	//$ied_address = 'http://biz.epost.go.kr/KpostPortal/openapied?regkey=' . $api_key . '&target=post&query=' . $txtdong;

	$header[0]  = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[] = "Accept-Language: ko,en;q=0.5"; 
	$header[] = "Pragma: "; // browsers keep this blank. 

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $ie_address);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER , $header);

	$result = curl_exec($ch);
	curl_close($ch);
	unset($ch);
	$post_data = han_utf($result);
	unset($result);

	echo $post_data;
?>