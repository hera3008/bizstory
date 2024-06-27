<?php

// 파일 저장 경로 설정
$fileDirectory = "/volume1/web/filecenter/demo/download/files";

$filename = $_POST['filename'];
$filesize = $_POST['filesize'];
$filetype = $_POST['filetype'];

$file = $fileDirectory . '/' . $filename;

if (file_exists($file)) {
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$attachment = (strpos($agent, "Mozilla/4") === false ||
	   	strpos($agent, "MSIE") !== false) ? ' attachment;' : ''; 

	$disposition = "Content-Disposition:" . $attachment . " filename=\"" . urlencode($filename)."\"";
	header ("Cache-Control:");
	header ("Cache-Control: public");
	header ("Accept-Ranges: bytes");
	header ($disposition);
	header ("Content-Type: ". $filetype);
	header ("Content-Length: ". filesize($file)); 

	$fh = fopen($file, "r");
	while (!feof($fh)) print(fread($fh, 8192));
   	fclose($fh);
}

?>
