<?php

$qry = $_POST['qfiles']; // file1:file2:file3 ...
$arr = explode(':', $qry);
$len = count($arr);

$rdata = "<files>\n";

for ($i=0; $i < $len; $i++) {
	$rdata .= "<file>\n";
	$rdata .= "<name>" . $arr[$i] . "</name>\n";
	$rdata .= "<size>132573096</size>\n";
	$rdata .= "<path>http://works.chcode.com/mrsmooth1.zip</path>\n";
	$rdata .= "</file>\n";
}

$rdata .= "</files>";
echo $rdata;
?>
