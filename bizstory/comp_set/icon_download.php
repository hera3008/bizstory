<?
	include "../common/setting.php";
	include "../common/no_direct.php";
	include "../common/member_chk.php";

	$where = " and ci.ci_idx = '" . $ci_idx . "'";
	$data = client_info_data("view", $where);

	$file_path = $comp_icon_path . '/' . $data["client_code"] . '.exe';
	if (is_file($file_path))
	{
		$file_name = utf_han($data["client_name"]);
		$file_name = str_replace(" ", "_", $file_name);

		//header("Content-Type: " . $data["img_type"]);
		Header("Content-Disposition: attachment; filename=" . $file_name . "");
		header("Content-Transfer-Encoding: binary");
		Header("Content-Length: " . (string)(filesize($file_path)));
		Header("Cache-Control: cache, must-revalidate");
		header("Pragma: no-cache");
		header("Expires: 0");

		if (is_file($file_dir))
		{
			$fp = fopen($file_dir, "rb");
			if (!fpassthru($fp)) fclose($fp);
		}
	}
	else echo "파일이 없습니다.";
?>