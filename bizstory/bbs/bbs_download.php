<?
	include "../common/setting.php";
	include "../common/no_direct.php";

	$where = " and bf.bf_idx = '" . $bf_idx . "'";
	$data = bbs_file_data('view', $where);

	$file_path = $comp_bbs_path . '/' . $data['bs_idx'] . '/' . $data['b_idx'] . '/' . $data['img_sname'];

	$file_name = utf_han($data["img_fname"]);
	$file_name = str_replace(' ', '_', $file_name);

	header("Content-Type: " . $data["img_type"]);
	Header("Content-Disposition: attachment; filename=" . $file_name . "");
	header("Content-Transfer-Encoding: binary");
	Header("Content-Length: " . (string)(filesize($file_path)));
	Header("Cache-Control: cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	if (is_file($file_path))
	{
		$fp = fopen($file_path, "rb");
		if (!fpassthru($fp)) fclose($fp);
	}
?>