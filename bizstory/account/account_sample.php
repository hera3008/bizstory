<?
	include "../common/setting.php";
	include "../common/member_chk.php";

// 파일 위치
	$file_name  ='account_sample.csv';
	$file_path  = $root_dir . "/bizstory/account/" . $file_name;
	$file_type  = filetype($file_path);

	header("Content-Type: " . $file_type);
	Header("Content-Disposition: attachment; filename=" . $file_name . "");
	header("Content-Transfer-Encoding: binary");
	Header("Content-Length: " . (string)(filesize($file_path)));
	Header("Cache-Control: cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	if (is_file($file_path))
	{
		$fp = fopen($file_path, "rb");
		if (!fpassthru($fp))
		{
			fclose($fp);
		}
	}
?>