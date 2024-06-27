<?
	include "../common/setting.php";
	include "../common/no_direct.php";

	$where = " and ci.ci_idx = '" . $client_idx . "'";
	$data = client_info_data('view', $where);

	$file_path = $local_path . '/agent/data/' . $data['comp_idx'] . '/BizstorySetup_' . $data['client_code'] . '.exe';

	$file_name = utf_han($data["client_name"]) . '.exe';
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